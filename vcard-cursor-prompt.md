# V Card — complete build prompt

Paste this whole file into Cursor as the opening instruction. It specifies a
full product: a Pakistani NFC digital business card platform, from an empty
folder to a deployed site.

Read the **Hard-won rules** section before writing any code. Every item in it
is a bug that was actually hit and cost a rebuild. They are not style
preferences.

---

## 0. How to work

- Build in the phase order given in §13. Each phase must leave the app
  running, typechecking and with its tests passing before starting the next.
- After every phase run: `npx tsc --noEmit`, `npm run lint`, `npm test`,
  `npx next build`. Do not proceed with any of them failing.
- Write tests as you go, not at the end. Target the rules in §12.
- Comment the *why*, not the *what*. A comment that restates the code is
  noise; a comment explaining a non-obvious constraint is the only record of it.
- When a requirement here would produce a worse product, say so and propose the
  alternative before building it.

---

## 1. What the product is

A platform where someone creates a digital business card, publishes it at
`vcard.pk/their-name`, and optionally buys a printed NFC card that opens it.

- **Market:** Pakistan. All prices in **PKR only**.
- **Money:** payments are confirmed by hand — Easypaisa, JazzCash, bank
  transfer, cash. A card gateway is supported but not the primary route.
- **Two card types:** personal and business. One account may hold one of each;
  each is a separate subscription.
- **Business model:** freemium. 30-day free trial with everything unlocked, then
  it pauses until a plan is bought. The printed card is always a separate
  purchase.

---

## 2. Stack

| Concern | Choice |
|---|---|
| Framework | Next.js 15 (App Router), TypeScript, Turbopack |
| Database | PostgreSQL (Neon), Prisma with the `@prisma/adapter-pg` driver adapter |
| Styling | Plain CSS with custom properties. **No Tailwind.** |
| Auth | Own implementation: opaque SHA-256 session tokens in an httpOnly cookie. **No JWTs, no NextAuth.** |
| Tests | Vitest |
| Hosting | Cloudflare Workers via OpenNext |
| Images | Cloudinary (optional, behind env vars) |

### Scripts to provide

```jsonc
{
  "dev": "node scripts/dev.mjs",            // db:check then next dev
  "build": "next build",
  "verify": "tsc --noEmit && eslint . && vitest run",
  "test": "vitest run",
  "db:sync": "prisma db push && npm run db:types",
  "db:types": "node tools/generate-prisma-stub.mjs",
  "db:check": "node scripts/db-check.mjs",  // names missing tables/columns
  "db:seed": "tsx prisma/seed.ts",
  "clean:stale": "node scripts/clean-stale.mjs"
}
```

`db:sync` rather than `prisma migrate dev`: the schema changes constantly during
the build and `migrate dev` refuses when local history differs from the
database, then offers to reset it. `db push` has no history to disagree with.
Keep `prisma/migrations/` for production use.

---

## 3. Hard-won rules — read before coding

Each of these was a real failure. Build them in from the start.

### 3.1 Never use Prisma `include` on profile tables
Always an explicit `select`. `include` returns every scalar column, including
ones added in a later migration — so the day a column is added, every page that
used `include` starts failing against a database that has not been pushed yet.
Keep all profile selects in **one file** (`src/lib/profile/publicSelect.ts`) and
write a test that fails if `include` appears on `personalProfile` or
`businessProfile`.

### 3.2 Look profiles up sequentially, never in parallel
A public page must try the personal table, and only on a miss try the business
table. Querying both with `Promise.all` means a fault in the table the profile
does **not** live in still takes the page down.

### 3.3 Optional columns load separately and fail soft
Newer, non-essential columns (opening hours, team, map) are loaded in a second
query whose failure is swallowed. The core card must render without them.

### 3.4 Mirror every Prisma enum in TypeScript, and test the mirror
Keep `src/lib/enums.ts` as string-literal unions matching the schema, with a
test asserting both directions. **Specific trap:** `SubscriptionStatus` needs a
`TRIALING` member. Code once wrote `status: "TRIALING"` to an enum that lacked
it, and Prisma rejected the row *after* the account was created — registration
appeared to work, then failed at the last step.

### 3.5 A trial must count as an active subscription
Two places get this wrong independently:
- the subscription lookup that feeds the viewer (`status in (...)`) must
  include `TRIALING`;
- the entitlement check must treat `TRIALING` as active.

Miss either and the free month grants nothing: publishing refused, uploads
refused, link limits hit immediately — **and profile saves silently discard
everything**, because the whole PATCH throws on one refused field.

### 3.6 Never export JSX from a `"use client"` module for server use
Icons defined as JSX constants in a client module and rendered by a server
component arrive as *client references*, not elements, and render as nothing.
The same class of bug makes an exported array arrive without `.includes`.
Put shared data and JSX constants in plain modules, and test that no server
module imports non-component values from a client module.

### 3.7 The colour scheme lives on `<html>` and nowhere else
A wrapper `<div data-scheme="system">` overrides the attribute on `<html>` for
everything inside it. The result is light-mode *rules* with dark-mode
*variables*: white text on white cards. Set `data-scheme` only on
`document.documentElement`, and write a test that fails if any component sets
it.

### 3.8 Write light mode for `system` as well as `light`
Rules keyed only on `[data-scheme="light"]` never apply to someone who never
touched the toggle. Emit the light overrides twice: once for
`[data-scheme="light"]`, once inside
`@media (prefers-color-scheme: light) { [data-scheme="system"] { … } }`.

### 3.9 Configure the Postgres pool for serverless
Neon closes idle connections from its side; a pool that hands one out anyway
fails with **P1017** and takes the page with it. Set
`idleTimeoutMillis: 10_000` (below Neon's own timeout), `max: 5` (a dev server
opens a pool per reload), `connectionTimeoutMillis: 15_000`, `keepAlive: true`.

### 3.10 `upgrade-insecure-requests` is production-only
This CSP directive rewrites every `http` subresource to `https`. Browsers
exempt `localhost`, so a laptop never notices — but testing from a phone on
`http://192.168.1.8:3000` loads the page with **no CSS at all**. Same for HSTS,
which makes the LAN address unreachable until the policy is cleared by hand.

### 3.11 Pass the value into `onComplete`
An OTP input firing `onComplete` in the same render as its final state update
must pass the completed value to the handler. Reading state inside the handler
sees five digits and returns early — which looks like "the button does nothing".

### 3.12 Verify a code before advancing a step
A multi-step reset that advances on code *length* lets anyone reach the
new-password screen with six wrong digits, and only fails at the end — which
reads as the password being rejected, not the code. Check the code against the
server first, **without consuming it**, so a dropped connection does not strand
someone with a dead code.

### 3.13 Never add a charge the customer has not seen
Delivery was added at checkout and never displayed, so a card quoted at
Rs 2,500 reached the admin queue at Rs 3,050. Itemise every line and put the
**total** on the button.

### 3.14 Neutralise CSV cells
Excel executes a cell beginning `=`, `+`, `-` or `@` when the file opens. A
customer named `=HYPERLINK(…)` would run code on the machine that exported the
list. Prefix such cells with `'`, escape quotes by doubling, and write a UTF-8
byte-order mark so Urdu names survive.

### 3.15 Don't refuse to render an owner's own QR
Gating the QR endpoint on `published` means every new account sees a broken
image on its own dashboard. Allow the owner and admins to see an unpublished
card's code.

### 3.16 Seed data must be removed when a feature is
Category attributes (`material`, `finish`) stayed required after materials were
removed from the product, so ordering failed with "material is required" on a
form that never showed one. Seeds must delete what no longer applies —
`db push` does not remove rows.

### 3.17 Ship a stale-file cleaner
If builds are delivered as archives, extraction adds and overwrites but never
deletes. Ship `build-manifest.json` and a `clean:stale` script that removes
files not in it. **Protect `prisma/migrations/`.**

---

## 4. Money, and the data model

### 4.1 Money is integer paisa, always
Never a float. Columns are `*Paisa Int`. One `formatPkr()` helper formats.
Cap at `MAX_PRICE_PAISA = 1_000_000_000 * 100`. (An early version capped at
100M paisa — Rs 1M — and rejected a legitimate Rs 15M catalogue item.)

### 4.2 Core models

```prisma
model User {
  id              String   @id @default(cuid())
  email           String   @unique
  emailNormalized String   @unique
  passwordHash    String
  fullName        String?
  phone           String?
  role            Role     @default(CUSTOMER)
  status          UserStatus @default(REGISTERED)
  marketingOptIn  Boolean  @default(false)   // consent, never assumed
  emailVerifiedAt DateTime?
  lastLoginAt     DateTime?
  deletedAt       DateTime?
  createdAt       DateTime @default(now())
}

enum Role { CUSTOMER ADMIN SUPER_ADMIN }
enum UserStatus { REGISTERED VERIFIED ACTIVE ON_HOLD SUSPENDED CANCELLED }

model Session {
  id         String   @id @default(cuid())
  userId     String
  tokenHash  String   @unique
  expiresAt  DateTime
  lastSeenAt DateTime @default(now())   // drives the idle timeout
  ipHash     String?
  userAgent  String?
  createdAt  DateTime @default(now())
}

model PersonalProfile {
  id                String  @id @default(cuid())
  userId            String  @unique
  username          String  @unique
  fullName          String
  jobTitle          String?
  organisation      String?
  about             String?
  photo             String?
  cover             String?
  theme             String  @default("MINIMAL")
  colorScheme       String  @default("DARK")
  published         Boolean @default(false)
  usernameChangedAt DateTime?
  usernameLocked    Boolean @default(false)
  // contacts, address fields, relations…
}

model BusinessProfile {
  // as above plus:
  businessName String
  tagline      String?
  ownerName    String?
  ownerTitle   String?
  ownerPhone   String?
  showHours    Boolean @default(false)
  showTeam     Boolean @default(false)
  showMap      Boolean @default(false)
  mapUrl       String?
}

model Plan {
  id          String      @id @default(cuid())
  key         String      @unique
  name        String
  profileType ProfileType @default(PERSONAL)
  months      Int         @default(12)   // 1, 12 or 24 — stored, not derived
  pricePaisa  Int
  badge       String?
  isActive    Boolean     @default(true)
}

model Subscription {
  id                 String   @id @default(cuid())
  userId             String
  planId             String
  status             SubscriptionStatus @default(PENDING)
  trialEndsAt        DateTime?    // set on verification, never at signup
  currentPeriodStart DateTime?
  currentPeriodEnd   DateTime?
}

enum SubscriptionStatus { PENDING TRIALING ACTIVE PAST_DUE CANCELLED EXPIRED }

model Visitor {
  id          String @id @default(cuid())
  fingerprint String
  personalProfileId String?
  businessProfileId String?
  firstSeenAt DateTime @default(now())
  lastSeenAt  DateTime @default(now())
  visitCount  Int      @default(1)
  city String?  country String?
  device String? platform String? browser String?
  @@unique([fingerprint, personalProfileId, businessProfileId])
}

model VisitorEvent {
  id        String   @id @default(cuid())
  visitorId String
  createdAt DateTime @default(now())
  path String?  referrer String?  source String?
  // per-visit, not read off the visitor: a phone scan last week and a laptop
  // link today are different events
  device String? platform String? browser String? city String? country String?
  // precise fields, admin-only, purged after 90 days
  ipAddress String? latitude Float? longitude Float?
}
```

Also: `Product`, `ProductCategory`, `Order`, `OrderItem`,
`OrderStatusHistory`, `Payment`, `LedgerAccount`, `LedgerCategory`,
`LedgerTransaction`, `AppSetting`, `AuditLog`, `VerificationToken`,
`PasswordResetToken`, `SocialLink`, `CustomLink`, `BusinessHours`,
`TeamMember`, `QrCode`.

---

## 5. Pricing

Editable from admin without a deploy. Seed these:

| Plan | 1 month | 12 months | 24 months |
|---|---|---|---|
| Personal | Rs 1,400 | Rs 9,800 | Rs 16,500 |
| Business | Rs 2,800 | Rs 19,600 | Rs 33,000 |

- Printed card: **Rs 2,500**, stored in `AppSetting` under
  `shop.card.pricePaisa`. Never bundled into a plan.
- Delivery: `orders.shipping.flatPaisa`, and it must be shown (§3.13).
- **Sanity rule:** the 24-month price must be below two 12-month terms. An
  earlier draft had it above, which made the longest commitment the worst deal.

---

## 6. Accounts and access

### 6.1 Signing up — one screen
First name, last name, email, account type, password, confirm. Phone optional.
Two checkboxes: terms (required) and marketing opt-in (optional). **No plan
step, no billing step, no wizard.** Creating the account signs the person in
and sends them to verification.

- Google sign-in offered first; those accounts skip verification because Google
  has already verified the address. If credentials are absent, the button
  explains that in place rather than redirecting to a broken consent screen.
- Block disposable domains (~430 of them plus pattern matching). Allow any
  real domain — a business signing up as `info@theircompany.pk` is the target
  customer.

### 6.2 Verification
- Six digits, own screen, auto-submits on the sixth (§3.11).
- Resend has a **60-second cooldown**, counting down on the button; a new code
  invalidates every earlier one.
- Attempts rate-limited to 10/hour.
- **Until an email provider is configured, print the code to the terminal** in a
  boxed block. Route everything through one `deliver()` function so adding a
  provider later is one function body.

### 6.3 The only dashboard gate is an unverified email
Not payment. Under freemium, gating the dashboard on payment means nobody can
ever start, and a lapsed customer needs that page most — it is where they pay.

### 6.4 Usernames
Generated from the name (`saad-ahmad`, then `-2`), unique across both profile
tables and against reserved words. The owner may change it **once**, and only
before a card has been printed; after that it is locked. An admin may still
change it and is told how many cards it would break.

### 6.5 Sessions
- Absolute expiry from the token TTL, **plus a 12-hour idle timeout** driven by
  `lastSeenAt`, refreshed at most every 5 minutes.
- **In development only**, clear all sessions on server boot. Restarting to
  test a change should give a clean slate. Never do this in production — a
  deploy must not sign everyone out.

---

## 7. The free month

- **30 days from verification**, not from signup, so an unverified account
  cannot burn a trial it never used.
- **Everything is unlocked during it.** The only difference from paid is the
  watermark.
- `resolveAccess(subscription)` returns `trialing | active | lapsed` and every
  screen reads that rather than checking dates itself. **It fails closed:** no
  subscription row is `lapsed`, never accidentally free forever. Paying
  mid-trial becomes `active` immediately — the watermark comes off the moment
  it is paid for.

### After it ends
The card **does not 404 and is never deleted.** It shows the owner's name and
phone, a line saying the profile is paused, and an invitation to create one.
Fifty people may be holding the printed card, and a card that leads nowhere
gets blamed on the card.

What locks when lapsed: cover photo, owner information, appearance options,
custom fields, team, catalogue, and social links above 3.

---

## 8. Customer dashboard

Nine items. Anything not listed should not exist.

| Page | Contents |
|---|---|
| **Overview** | Gradient hero stating where the account stands (days left, turning amber inside a week, paused treatment when lapsed) · four metric tiles (reads, not links) · card link panel: one line, status badge, Open · QR panel with online/offline toggle and icon buttons |
| **My card** | A rotating card preview that pauses on hover and stops under reduced motion · itemised price (card, delivery, total) · **Order now** beside it · five-stage tracker that appears only once an order exists |
| **Statistics** | Metrics, then a table of **people, not visits** — one row per visitor — each opening to their full history |
| **My profile** | Identity, contacts, photo, cover, address. Links to Social links and Appearance rather than containing them |
| **Social links** | Platform dropdown, custom links with uploaded logo, click counts, reorder, delete |
| **Catalogue** | Business only. Products with image, price, auto-generated SKU, WhatsApp order button |
| **Appearance** | Theme (Minimal, Glass, Clay, Liquid, Neo) · **light or dark only — no "match my device"** · QR colours and downloads. Saves on click, no Save button |
| **Subscription** | Current plan, price, term, start, renewal, days left · plans grouped by term with a per-month figure beside each · full payment history (date, purpose, method, status) |
| **Settings** | Name, phone, email, password reset via the OTP flow |
| **Help** | Phone, WhatsApp, email, and six real FAQs. **No ticket system** — for a business whose payments happen on a call, a ticket is a slower route to the same conversation |

### Mobile preview
A **drawer**, opened by a Preview button in a sticky toolbar — not a permanent
column. Size the phone from `window.innerHeight` so the whole device always
fits, and scroll **inside** the frame with `overscroll-behavior: contain`. A
fixed height clips top and bottom and scrolls the page instead.

### QR codes
Two, both free:
- **Online** — encodes the profile URL. Always current, needs internet.
- **Offline** — encodes a vCard. Works with no connection but must be
  reprinted when details change.

Restrict colours to **tested contrast pairs**, enforced server-side. Free
choice is a trap: a low-contrast code looks fine on screen, fails to a camera
in indoor light, and the owner finds out after printing. Offer PNG (rasterised
in the browser from the SVG, with the quiet zone filled — a transparent PNG on
a dark background is unreadable) and SVG for printers.

---

## 9. The public card

- `/{username}`, server-rendered, fast, no login.
- Unpublished: 404 for the public, renders for the owner and admins.
- **Free tier:** a watermark as a real footer bar — not grey text — plus a
  dismissible invitation after ~4 seconds, once per visitor. **No third-party
  ads, ever.** A card handed to a client showing a stranger's advert embarrasses
  its owner, and the revenue is negligible against one subscription.
- **Save contact on phones:** a sheet rises a moment after load offering the
  contact. Tapping it serves a `.vcf`, which opens the platform's own
  new-contact screen with every field filled in. Offer it; never fire it on
  load — a download that starts by itself reads as an attack and browsers block
  it without an interaction anyway.
- Business sections: catalogue, team slider (max 5), opening hours, map.
  Google reviews behind a feature flag: the Places API returns at most five and
  **forbids showing only the best ones**.

---

## 10. Visitors and privacy

- Recognise a returning visitor by a **fingerprint**: SHA-256 of network +
  platform + browser + language. Reduce the IP to its network (first three
  octets of IPv4, four groups of IPv6) so a changing address is still one
  person, and reduce the user agent so a version bump is not a new visitor.
- Parse browsers **most-specific-first** — Edge, Opera and Samsung all claim to
  be Chrome, and Chrome claims to be Safari.
- It is roughly 80% accurate. **Say so in the interface** rather than implying
  precision it does not have.
- Location comes from proxy headers (`cf-ipcountry`, `cf-ipcity`,
  `x-vercel-ip-*`) — free, no latency, accurate to the city. Local addresses
  report "This device" rather than inventing a country.
- **The owner sees city and country only.** IP and coordinates are admin-only
  and purged after 90 days. Showing a card owner the coordinates of whoever
  scanned their card is a privacy problem, not a feature.
- Recording **never throws**. A card that will not open because a counter
  failed is far worse than a missing row.

---

## 11. Admin

| Page | Contents |
|---|---|
| **Overview** | Confirmed vs pending income, sign-ups, orders by state, payments queue. **All bar and line charts** — two categories in a donut take longer to read than the two numbers in them |
| **Users** | Table with Customer, **Type (Personal / Business / Both)**, card addresses, plan, status. Detail page lists **every** card with its own link and state — never "whichever profile exists first", which hides the other. Editing a username must name **which card** it belongs to |
| **Roles** | Three fixed roles. Only a super admin may change roles; nobody may change their own; the last super admin cannot be demoted; a change signs that person out everywhere. Do not build a permission matrix until there are staff — until then every combination it allows is one more state to debug |
| **Orders** | Accept → print → encode → ship → delivered, with history |
| **Products** | One product (V Card). Keep the models for future products |
| **Ledger** | Balances per account above the table · totals in a `tfoot` · tabular figures, ruled rows, double-ruled closing figure · prints with the rail and form hidden |
| **Settings** | Plan prices, card price, contact details, landing copy, logo for dark **and** light, whether to show logo/name/both, both footer credit lines. **Not products** — two places to edit one price means whichever was saved second wins |
| **Send a message** | One message, one group, and a **preview naming the count before anything can send**. Consent enforced in the query, not offered as a filter. Unsubscribe line appended automatically |
| **Audit log** | Every admin action, attributed. An invisible admin edit is a problem the day you hire someone |
| **Exports** | CSV for users, orders, payments (§3.14) |

---

## 12. Tests to write

Beyond unit coverage, these guard the failures above:

- `schemaContract` — parse the schema, scan every query in the app *and the
  seed* for fields that do not exist.
- `enumContract` — the TypeScript mirror matches the schema both ways.
- `drivenBySelect` — no `include` on profile tables; optional columns stay out
  of the core select.
- `clientBoundary` — no server module imports data from a `"use client"`
  module.
- `colourScheme` — nothing but `<html>` sets `data-scheme`.
- `access` — trial, active and lapsed; trial counts as active; fails closed with
  no row.
- `fingerprint` — stable across IP change within a network and across browser
  version; browsers impersonating each other are told apart.
- `geo` — private ranges detected (including that `172.32` is *public*); proxy
  headers read; nothing invented when absent.
- `csvExport` — formula characters neutralised, quotes escaped, BOM present.
- `securityHeaders` — CSP, and `upgrade-insecure-requests` / HSTS absent
  outside production.
- `usernameRules` — one change, locked after printing, admin warned.
- `hours` — opening hours including shifts crossing midnight.

---

## 13. Build order

1. **Foundation** — Next.js, Prisma, the pool config (§3.9), tokens, enums and
   their mirror, test harness, `db:sync`, `db:check`, stale cleaner.
2. **Accounts** — one-screen signup, OTP verification to the terminal, Google
   behind env vars, disposable blocking, username rules, sessions with idle
   timeout, plans and the trial clock, `resolveAccess`, entitlement gating.
3. **Design system** — navy glass: gradient page, cards as lighter panels,
   borders as white at low alpha, indigo-to-cyan accents, 18px cards, 11px
   controls. Light mode written for both `light` and `system` (§3.7, §3.8).
4. **Dashboard** — shell, overview, My card with the order flow and tracker.
5. **Visitors** — tracking, fingerprinting, geo, the statistics page, 90-day
   purge.
6. **Profile pages** — profile, social links, appearance, preview drawer.
7. **Public card** — rendering, themes, watermark, invitation, save-contact
   sheet, paused page, business sections.
8. **Admin** — overview, users, roles, orders, ledger, settings, broadcast,
   audit, exports.
9. **Email** — provider (Resend or Brevo; **SMTP does not work on Cloudflare
   Workers**), real verification, receipts, unsubscribe handling.
10. **Deployment** — see below.

---

## 14. Deployment

- **Database:** Neon. Use the **pooled** connection string. Run
  `prisma migrate deploy` against production, not `db push`.
- **Hosting:** Cloudflare Workers via OpenNext. Provide `wrangler.jsonc` and
  `open-next.config.ts`.
- **Environment:**
  `DATABASE_URL`, `NEXTAUTH_SECRET` (session signing), `DOMAIN`, `APP_NAME`,
  `PAYMENT_PROVIDER` (`manual` in production, `mock` in development),
  `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `CLOUDINARY_*`, `RESEND_API_KEY`.
- **Headers:** CSP, HSTS, `X-Frame-Options: DENY`,
  `Referrer-Policy: strict-origin-when-cross-origin`, and an NFC permission
  policy — with §3.10 respected.
- **Before launch:** a privacy policy covering visit data, IP and approximate
  location, why it is held and for how long. Not optional once a single EU or
  UK visitor scans a card.

---

## 15. Tone of the product

Write every piece of interface text as a person would say it.

- Plain sentences. No "Oops!", no exclamation marks, no corporate filler.
- Errors name the field and say what to do: "Please add a full delivery
  address", not "Check highlighted fields" with nothing highlighted.
- Never say a thing is free and then take it away without warning.
- State what something costs before taking money, including delivery.
- When a feature is paid, show it **locked rather than hidden**. A feature
  nobody can see is a feature nobody asks for.

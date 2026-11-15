<template>
  <div class="landing">
    <!-- Hero: brand + one line + CTA + full-bleed visual -->
    <section class="hero">
      <div class="hero-copy">
        <p class="brand-mark">{{ appName }}</p>
        <h1>Your card. One tap. Always up to date.</h1>
        <p class="lede">
          Publish a digital business card at your own link, then order a printed NFC card that opens it.
        </p>
        <div class="cta">
          <NuxtLink class="vc-btn" to="/auth/register">Start free for 30 days</NuxtLink>
          <a class="vc-btn vc-btn--ghost" href="#how">See how it works</a>
        </div>
      </div>
      <div class="hero-visual" aria-hidden="true">
        <img src="/images/vCards-hero.png" alt="" class="hero-img" />
      </div>
    </section>

    <section id="how" class="section">
      <h2>Three steps</h2>
      <p class="section-lede">No billing wizard on signup. Verify email, build the card, share the link.</p>
      <ol class="steps">
        <li>
          <span class="n">1</span>
          <strong>Create your page</strong>
          <p>Personal or business. Add contacts, links, and a look that fits you.</p>
        </li>
        <li>
          <span class="n">2</span>
          <strong>Share the URL</strong>
          <p>Anyone opens <em>your-name</em> on your domain — phone, laptop, or NFC tap.</p>
        </li>
        <li>
          <span class="n">3</span>
          <strong>Order the print</strong>
          <p>A physical NFC card that opens your live profile. Delivery shown before you pay.</p>
        </li>
      </ol>
    </section>

    <section class="section split">
      <div>
        <h2>Built for Pakistan</h2>
        <p class="section-lede">
          Prices in PKR. Pay by Easypaisa, JazzCash, bank transfer, or cash — confirmed by hand.
        </p>
        <ul class="ticks">
          <li>Personal and business accounts</li>
          <li>Templates or a custom page builder</li>
          <li>Shop catalogue with WhatsApp orders</li>
          <li>Free month with everything unlocked</li>
        </ul>
      </div>
      <div class="split-visual">
        <img src="/images/section.png" alt="Digital card on a phone" />
      </div>
    </section>

    <section class="section">
      <h2>Printed NFC card</h2>
      <p class="section-lede">Always a separate purchase — never bundled into a plan.</p>
      <div class="price-block">
        <div>
          <p class="price-label">Card</p>
          <p class="price">Rs 2,500</p>
        </div>
        <div>
          <p class="price-label">Delivery</p>
          <p class="price">Shown at checkout</p>
        </div>
        <NuxtLink class="vc-btn" to="/auth/register">Get started</NuxtLink>
      </div>
    </section>

    <section class="section close">
      <h2>Ready when you are</h2>
      <p class="section-lede">Create the account in one screen. Your free month starts when you verify email.</p>
      <NuxtLink class="vc-btn" to="/auth/register">Create your card</NuxtLink>
    </section>

    <footer class="foot">
      <span>{{ appName }}</span>
      <nav>
        <NuxtLink to="/auth/login">Sign in</NuxtLink>
        <NuxtLink to="/auth/register">Register</NuxtLink>
      </nav>
    </footer>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'default' })
const config = useRuntimeConfig()
const appName = config.public.appName

useSeoMeta({
  title: () => `${appName} — Digital & NFC business cards`,
  description: 'Create a digital business card, share your link, and order a printed NFC card. Free for 30 days.',
})
</script>

<style scoped>
.landing {
  overflow-x: hidden;
}

.hero {
  min-height: calc(100vh - 72px);
  display: grid;
  grid-template-columns: 1.05fr 0.95fr;
  align-items: stretch;
  gap: 0;
}

.hero-copy {
  padding: clamp(2rem, 5vw, 4.5rem);
  display: flex;
  flex-direction: column;
  justify-content: center;
  animation: rise 0.8s ease both;
}

.brand-mark {
  font-family: var(--vc-display);
  font-size: clamp(1.6rem, 3vw, 2.2rem);
  margin: 0 0 1rem;
  background: var(--vc-gradient);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  font-weight: 700;
}

.hero h1 {
  font-size: clamp(2.4rem, 5.5vw, 3.8rem);
  line-height: 1.05;
  margin: 0 0 1rem;
  max-width: 12ch;
}

.lede {
  font-size: 1.15rem;
  color: var(--vc-muted);
  max-width: 36ch;
  margin: 0 0 1.75rem;
  line-height: 1.55;
}

.cta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.hero-visual {
  position: relative;
  min-height: 420px;
  background:
    radial-gradient(circle at 30% 20%, rgba(91, 140, 255, 0.35), transparent 55%),
    radial-gradient(circle at 70% 80%, rgba(47, 211, 200, 0.25), transparent 50%),
    var(--vc-navy-soft);
  display: flex;
  align-items: flex-end;
  justify-content: center;
  overflow: hidden;
  animation: fade 1.1s ease both;
}

.hero-img {
  width: min(100%, 560px);
  height: auto;
  object-fit: contain;
  transform: translateY(4%);
  filter: drop-shadow(0 24px 48px rgba(0, 0, 0, 0.35));
  animation: float 6s ease-in-out infinite;
}

.section {
  padding: clamp(3.5rem, 8vw, 6rem) clamp(1.25rem, 4vw, 3rem);
  max-width: 1100px;
  margin: 0 auto;
}

.section h2 {
  font-size: clamp(1.8rem, 3vw, 2.4rem);
  margin: 0 0 0.5rem;
}

.section-lede {
  color: var(--vc-muted);
  max-width: 42ch;
  margin: 0 0 2rem;
  line-height: 1.55;
}

.steps {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.25rem;
}

.steps li {
  padding: 1.25rem;
  border: 1px solid var(--vc-border);
  border-radius: var(--vc-radius);
  background: var(--vc-panel-solid);
  animation: rise 0.7s ease both;
}

.steps li:nth-child(2) { animation-delay: 0.08s; }
.steps li:nth-child(3) { animation-delay: 0.16s; }

.steps .n {
  display: inline-flex;
  width: 2rem;
  height: 2rem;
  align-items: center;
  justify-content: center;
  border-radius: 999px;
  background: var(--vc-gradient);
  color: #06101f;
  font-weight: 700;
  margin-bottom: 0.75rem;
}

.steps p {
  color: var(--vc-muted);
  margin: 0.4rem 0 0;
  line-height: 1.5;
}

.split {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2.5rem;
  align-items: center;
  max-width: 1100px;
}

.ticks {
  margin: 0;
  padding: 0;
  list-style: none;
}

.ticks li {
  padding: 0.55rem 0 0.55rem 1.4rem;
  position: relative;
  border-bottom: 1px solid var(--vc-border);
}

.ticks li::before {
  content: '';
  position: absolute;
  left: 0;
  top: 1rem;
  width: 0.55rem;
  height: 0.55rem;
  border-radius: 50%;
  background: var(--vc-accent-2);
}

.split-visual img {
  width: 100%;
  border-radius: var(--vc-radius);
  border: 1px solid var(--vc-border);
}

.price-block {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem 2.5rem;
  align-items: flex-end;
  padding: 1.5rem;
  border: 1px solid var(--vc-border);
  border-radius: var(--vc-radius);
  background: linear-gradient(135deg, rgba(91, 140, 255, 0.16), rgba(47, 211, 200, 0.08));
}

.price-label {
  margin: 0;
  color: var(--vc-muted);
  font-size: 0.9rem;
}

.price {
  margin: 0.2rem 0 0;
  font-family: var(--vc-display);
  font-size: 1.75rem;
}

.close {
  text-align: center;
}

.close .section-lede {
  margin-left: auto;
  margin-right: auto;
}

.foot {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem clamp(1.25rem, 4vw, 3rem);
  border-top: 1px solid var(--vc-border);
  color: var(--vc-muted);
  font-size: 0.95rem;
}

.foot nav {
  display: flex;
  gap: 1rem;
}

@keyframes rise {
  from { opacity: 0; transform: translateY(16px); }
  to { opacity: 1; transform: none; }
}

@keyframes fade {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes float {
  0%, 100% { transform: translateY(4%); }
  50% { transform: translateY(0%); }
}

@media (max-width: 900px) {
  .hero {
    grid-template-columns: 1fr;
    min-height: auto;
  }
  .hero-visual {
    min-height: 320px;
    order: -1;
  }
  .hero h1 {
    max-width: none;
  }
  .steps,
  .split {
    grid-template-columns: 1fr;
  }
}

@media (prefers-reduced-motion: reduce) {
  .hero-copy,
  .hero-visual,
  .steps li,
  .hero-img {
    animation: none;
  }
}
</style>

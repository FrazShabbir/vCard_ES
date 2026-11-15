# vCard Nuxt 3 frontend

Laravel remains the API at the repo root. This app is the Nuxt 3 SSR UI.

## Local

```bash
# Terminal 1 — Laravel (MAMP / php artisan serve)
# Terminal 2
cd nuxt
npm install
npm run dev
```

Open http://localhost:3000 — `/api/**` proxies to Laravel.

Set in Laravel `.env`:

```
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:3000
SESSION_DOMAIN=localhost
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://127.0.0.1:3000
```

OTP codes print to `storage/logs/laravel.log` until a mailer is configured.

## Cutover

Point the public hostname at Nuxt SSR. Keep Laravel for `/api` and jobs. Blade member/admin stays as fallback until traffic is stable.

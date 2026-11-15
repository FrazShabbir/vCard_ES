# Nuxt cutover checklist

Blade member/admin remain available at `/auth/member` and `/admin/dashboard` as fallback.

## Production shape

1. Serve Nuxt SSR on the public host (port 3000 or behind nginx).
2. Proxy `/api` and `/sanctum` to Laravel (MAMP / php-fpm).
3. Keep Laravel queue worker running for `RecordCardView`.
4. Run migrations + `php artisan db:seed --class=PlanSeeder` on each environment.
5. Set `SANCTUM_STATEFUL_DOMAINS`, `CORS_ALLOWED_ORIGINS`, `NUXT_API_INTERNAL`.

## When to retire Blade

After Nuxt covers register → verify → overview → appearance → card order → admin payments for a full week with no critical gaps, remove or redirect:

- `routes/member.php` Blade UI (keep download QR if still used)
- `routes/admin.php` Blade UI

Public Blade `HomeController::slug` can stay one release as emergency fallback; prefer Nuxt `/{username}` SSR.

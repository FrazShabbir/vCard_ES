<template>
  <div>
    <header class="vc-top">
      <NuxtLink to="/" class="brand">{{ appName }}</NuxtLink>
      <nav>
        <a href="/#how" class="nav-quiet">How it works</a>
        <NuxtLink to="/auth/login">Sign in</NuxtLink>
        <NuxtLink to="/auth/register" class="vc-btn top-cta">Create card</NuxtLink>
      </nav>
    </header>
    <slot />
  </div>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()
const appName = config.public.appName
</script>

<style scoped>
.vc-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--vc-border);
  position: sticky;
  top: 0;
  z-index: 20;
  backdrop-filter: blur(12px);
  background: rgba(11, 28, 51, 0.72);
}
.brand {
  font-family: var(--vc-display);
  font-size: 1.35rem;
  color: var(--vc-text);
  text-decoration: none;
}
nav {
  display: flex;
  gap: 1rem;
  align-items: center;
}
.nav-quiet {
  color: var(--vc-muted);
  text-decoration: none;
}
.top-cta {
  min-height: 36px;
  padding: 0.4rem 0.9rem;
}
@media (max-width: 640px) {
  .nav-quiet { display: none; }
}
</style>

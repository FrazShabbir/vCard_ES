<template>
  <div class="vc-shell">
    <aside class="vc-sidebar">
      <div class="brand">{{ appName }}</div>
      <p class="vc-muted" style="font-size:.85rem;margin:.25rem 0 1rem">Member</p>
      <NuxtLink to="/member">Overview</NuxtLink>
      <NuxtLink to="/member/card">My card</NuxtLink>
      <NuxtLink to="/member/stats">Statistics</NuxtLink>
      <NuxtLink to="/member/profile">My profile</NuxtLink>
      <NuxtLink to="/member/appearance">Appearance</NuxtLink>
      <NuxtLink to="/member/subscription">Subscription</NuxtLink>
      <NuxtLink to="/member/onboarding">Get started</NuxtLink>
      <button class="vc-btn vc-btn--ghost" style="width:100%;margin-top:1rem" @click="logout">Sign out</button>
    </aside>
    <main class="vc-main">
      <slot />
    </main>
  </div>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()
const appName = config.public.appName
const { api, setToken } = useApi()

async function logout() {
  try {
    await api('/auth/logout', { method: 'POST' })
  } catch (_) {}
  setToken(null)
  navigateTo('/auth/login')
}
</script>

<style scoped>
.brand {
  font-family: var(--vc-display);
  font-size: 1.25rem;
}
</style>

<template>
  <div class="vc-shell">
    <aside class="vc-sidebar">
      <div class="brand">Admin</div>
      <NuxtLink to="/admin">Overview</NuxtLink>
      <NuxtLink to="/admin/users">Users</NuxtLink>
      <NuxtLink to="/admin/orders">Orders</NuxtLink>
      <NuxtLink to="/admin/payments">Payments</NuxtLink>
      <NuxtLink to="/admin/plans">Plans</NuxtLink>
      <NuxtLink to="/member">Back to member</NuxtLink>
    </aside>
    <main class="vc-main">
      <slot />
    </main>
  </div>
</template>

<script setup lang="ts">
const brand = 'Admin'
</script>

<style scoped>
.brand {
  font-family: var(--vc-display);
  font-size: 1.25rem;
  margin-bottom: 1rem;
}
</style>

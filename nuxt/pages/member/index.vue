<template>
  <div>
    <div class="vc-hero">
      <p class="vc-muted" style="margin:0">Where you stand</p>
      <h1 style="margin:.25rem 0">{{ data?.access?.access === 'lapsed' ? 'Paused' : data?.access?.access === 'trialing' ? 'Free month' : 'Active' }}</h1>
      <p class="vc-muted">{{ statusLine }}</p>
    </div>

    <div class="vc-grid-metrics">
      <div class="vc-panel vc-metric"><span class="vc-muted">Reads</span><strong>{{ data?.metrics?.reads ?? 0 }}</strong></div>
      <div class="vc-panel vc-metric"><span class="vc-muted">Reach</span><strong>{{ data?.metrics?.reach ?? 0 }}</strong></div>
      <div class="vc-panel vc-metric"><span class="vc-muted">Devices</span><strong>{{ data?.metrics?.devices ?? 0 }}</strong></div>
      <div class="vc-panel vc-metric"><span class="vc-muted">Places</span><strong>{{ data?.metrics?.locations ?? 0 }}</strong></div>
    </div>

    <div class="vc-panel" style="margin-top:1rem;padding:1.25rem">
      <h2 style="margin-top:0">Your card</h2>
      <p><span class="badge">{{ data?.card?.live_label }}</span> {{ data?.card?.url }}</p>
      <div style="display:flex;gap:.5rem;flex-wrap:wrap">
        <a class="vc-btn" :href="data?.card?.url" target="_blank" rel="noopener">Open</a>
        <NuxtLink class="vc-btn vc-btn--ghost" to="/member/appearance">Appearance</NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'member' })
const { api } = useApi()
const { data, error } = await useAsyncData('member-overview', () => api<any>('/member/overview'))

const statusLine = computed(() => {
  const a = data.value?.access
  if (!a) return ''
  if (a.access === 'trialing' && a.subscription?.trial_ends_at) {
    const days = Math.max(0, Math.ceil((new Date(a.subscription.trial_ends_at).getTime() - Date.now()) / 86400000))
    return `${days} day${days === 1 ? '' : 's'} left in your free month.`
  }
  if (a.access === 'lapsed') return 'Your profile is paused. Renew a plan to unlock editing.'
  return 'Your subscription is active.'
})

if (error.value?.statusCode === 403 && error.value?.data?.needs_verification) {
  await navigateTo('/auth/verify')
}
</script>

<style scoped>
.badge {
  display: inline-block;
  padding: .2rem .55rem;
  border-radius: 999px;
  border: 1px solid var(--vc-border);
  font-size: .8rem;
  margin-right: .5rem;
}
</style>

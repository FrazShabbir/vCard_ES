<template>
  <div>
    <h1>Subscription</h1>
    <div class="vc-panel" style="padding:1.25rem;margin-bottom:1rem">
      <p>Status: <strong>{{ access?.access }}</strong></p>
      <p v-if="access?.subscription?.plan">Plan: {{ access.subscription.plan.name }} · {{ access.subscription.plan.price_label }}</p>
    </div>

    <h2>Plans</h2>
    <div class="plans">
      <div v-for="p in plans" :key="p.id" class="vc-panel plan">
        <h3>{{ p.name }} · {{ p.months }} mo</h3>
        <p class="price">{{ p.price_label }}</p>
        <p class="vc-muted">{{ p.per_month_label }} / month</p>
        <p v-if="p.badge" class="badge">{{ p.badge }}</p>
        <button class="vc-btn" @click="pay(p)">Pay by hand</button>
      </div>
    </div>

    <h2>Payment history</h2>
    <ul>
      <li v-for="payRow in payments" :key="payRow.id">
        {{ payRow.created_at }} · {{ payRow.amount_label }} · {{ payRow.method }} · {{ payRow.status }}
      </li>
    </ul>
    <p v-if="msg">{{ msg }}</p>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'member' })
const { api } = useApi()
const { data: accessData } = await useAsyncData('access', () => api<any>('/auth/access'))
const { data: planData } = await useAsyncData('plans', () => api<any>('/plans'))
const { data: payData, refresh } = await useAsyncData('mypay', () => api<any>('/member/payments'))
const access = computed(() => accessData.value)
const plans = computed(() => planData.value?.plans || [])
const payments = computed(() => payData.value?.payments || [])
const msg = ref('')

async function pay(p: any) {
  msg.value = ''
  try {
    const res = await api<any>('/member/payments', {
      method: 'POST',
      body: { plan_id: p.id, method: 'bank', notes: 'Submitted from Nuxt subscription page' },
    })
    msg.value = res.message
    await refresh()
  } catch (e: any) {
    msg.value = e?.data?.message || 'Could not submit payment.'
  }
}
</script>

<style scoped>
.plans { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:.85rem; }
.plan { padding:1rem; }
.price { font-size:1.35rem; font-weight:700; margin:.25rem 0; }
.badge { display:inline-block; padding:.15rem .5rem; border:1px solid var(--vc-border); border-radius:999px; font-size:.8rem; }
</style>

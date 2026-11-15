<template>
  <div>
    <h1>Admin overview</h1>
    <div class="vc-grid-metrics">
      <div class="vc-panel vc-metric"><span class="vc-muted">Sign-ups</span><strong>{{ data?.signups }}</strong></div>
      <div class="vc-panel vc-metric"><span class="vc-muted">Pending payments</span><strong>{{ data?.pending_payments }}</strong></div>
      <div class="vc-panel vc-metric"><span class="vc-muted">Confirmed income</span><strong>{{ data?.confirmed_income_label }}</strong></div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'admin' })
const { api } = useApi()
const { data } = await useAsyncData('admin-overview', () => api<any>('/admin/overview'))
</script>

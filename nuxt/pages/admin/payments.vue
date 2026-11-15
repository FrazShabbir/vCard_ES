<template>
  <div>
    <h1>Payments queue</h1>
    <div class="vc-panel table-wrap">
      <table>
        <thead><tr><th>User</th><th>Amount</th><th>Method</th><th>Status</th><th></th></tr></thead>
        <tbody>
          <tr v-for="p in data?.payments || []" :key="p.id">
            <td>{{ p.user }}</td>
            <td>{{ p.amount_label }}</td>
            <td>{{ p.method }}</td>
            <td>{{ p.status }}</td>
            <td>
              <button v-if="p.status === 'pending'" class="vc-btn" @click="confirm(p.id)">Confirm</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'admin' })
const { api } = useApi()
const { data, refresh } = await useAsyncData('admin-payments', () => api<any>('/admin/payments'))

async function confirm(id: number) {
  await api(`/admin/payments/${id}/confirm`, { method: 'POST' })
  await refresh()
}
</script>

<style scoped>
.table-wrap { overflow:auto; }
table { width:100%; border-collapse:collapse; }
th, td { text-align:left; padding:.65rem; border-bottom:1px solid var(--vc-border); }
</style>

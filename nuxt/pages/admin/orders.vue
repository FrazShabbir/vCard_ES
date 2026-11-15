<template>
  <div>
    <h1>Orders</h1>
    <div class="vc-panel table-wrap">
      <table>
        <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Advance</th></tr></thead>
        <tbody>
          <tr v-for="o in data?.orders || []" :key="o.id">
            <td>{{ o.id }}</td>
            <td>{{ o.user?.username || o.email }}</td>
            <td>{{ o.total }}</td>
            <td>{{ o.status }}</td>
            <td>
              <select class="vc-input" :value="o.status" @change="setStatus(o.id, ($event.target as HTMLSelectElement).value)">
                <option>Pending</option>
                <option>Accepted</option>
                <option>Print</option>
                <option>Encode</option>
                <option>Ship</option>
                <option>Delivered</option>
              </select>
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
const { data, refresh } = await useAsyncData('admin-orders', () => api<any>('/admin/orders'))

async function setStatus(id: number, status: string) {
  await api(`/admin/orders/${id}/status`, { method: 'POST', body: { status } })
  await refresh()
}
</script>

<style scoped>
.table-wrap { overflow:auto; }
table { width:100%; border-collapse:collapse; }
th, td { text-align:left; padding:.65rem; border-bottom:1px solid var(--vc-border); }
.vc-input { min-height:36px; }
</style>

<template>
  <div>
    <h1>Statistics</h1>
    <p class="vc-muted">{{ data?.summary?.note }}</p>
    <div class="vc-grid-metrics">
      <div class="vc-panel vc-metric"><span class="vc-muted">Reads</span><strong>{{ data?.summary?.reads }}</strong></div>
      <div class="vc-panel vc-metric"><span class="vc-muted">Reach</span><strong>{{ data?.summary?.reach }}</strong></div>
    </div>
    <h2>People by place</h2>
    <div class="vc-panel table-wrap">
      <table>
        <thead><tr><th>City</th><th>Region</th><th>Country</th><th>First seen</th></tr></thead>
        <tbody>
          <tr v-for="row in data?.people_locations || []" :key="row.id">
            <td>{{ row.city_name || '—' }}</td>
            <td>{{ row.region_name || '—' }}</td>
            <td>{{ row.country_name || '—' }}</td>
            <td>{{ row.created_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'member' })
const { api } = useApi()
const { data } = await useAsyncData('stats', () => api<any>('/member/stats'))
</script>

<style scoped>
.table-wrap { overflow:auto; margin-top:1rem; }
table { width:100%; border-collapse:collapse; }
th, td { text-align:left; padding:.65rem .75rem; border-bottom:1px solid var(--vc-border); }
</style>

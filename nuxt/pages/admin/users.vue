<template>
  <div>
    <div class="bar">
      <h1>Users</h1>
      <a class="vc-btn vc-btn--ghost" href="/api/v1/admin/exports/users.csv">Export CSV</a>
    </div>
    <div class="vc-panel table-wrap">
      <table>
        <thead><tr><th>Name</th><th>Type</th><th>Username</th><th>Verified</th></tr></thead>
        <tbody>
          <tr v-for="u in data?.users || []" :key="u.id">
            <td>{{ u.full_name }}</td>
            <td>{{ u.type }}</td>
            <td><a :href="u.card_url" target="_blank">{{ u.username }}</a></td>
            <td>{{ u.email_verified ? 'Yes' : 'No' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'admin' })
const { api } = useApi()
const { data } = await useAsyncData('admin-users', () => api<any>('/admin/users'))
</script>

<style scoped>
.bar { display:flex; justify-content:space-between; align-items:center; }
.table-wrap { overflow:auto; }
table { width:100%; border-collapse:collapse; }
th, td { text-align:left; padding:.65rem; border-bottom:1px solid var(--vc-border); }
</style>

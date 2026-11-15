<template>
  <div>
    <h1>Plans</h1>
    <div v-for="p in data?.plans || []" :key="p.id" class="vc-panel plan">
      <strong>{{ p.name }} · {{ p.months }} mo ({{ p.profile_type }})</strong>
      <label>Price (paisa)
        <input v-model.number="p.price_paisa" class="vc-input" type="number" />
      </label>
      <button class="vc-btn" @click="save(p)">Save</button>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'admin' })
const { api } = useApi()
const { data, refresh } = await useAsyncData('admin-plans', () => api<any>('/admin/plans'))

async function save(p: any) {
  await api(`/admin/plans/${p.id}`, { method: 'PUT', body: { price_paisa: p.price_paisa, is_active: p.is_active } })
  await refresh()
}
</script>

<style scoped>
.plan { padding:1rem; margin:.75rem 0; max-width:480px; }
label { display:block; margin:.5rem 0; }
</style>

<template>
  <div>
    <div class="bar">
      <div>
        <h1 style="margin:0">Appearance</h1>
        <p class="vc-muted">Live now: {{ data?.appearance_mode === 'custom' ? 'Custom design' : 'Template ' + data?.template_id }}</p>
      </div>
      <button class="vc-btn vc-btn--ghost" type="button" @click="drawer = true">Preview</button>
    </div>

    <div class="tabs">
      <button :class="{ active: tab === 'template' }" @click="tab = 'template'">Choose a template</button>
      <button :class="{ active: tab === 'custom' }" @click="tab = 'custom'">Build custom page</button>
    </div>

    <div v-if="tab === 'template'" class="grid">
      <button
        v-for="n in 10"
        :key="n"
        class="vc-panel tpl"
        :class="{ selected: selectedTemplate === n }"
        @click="pickTemplate(n)"
      >
        Template {{ n }}
      </button>
    </div>

    <div v-else class="vc-panel" style="padding:1.25rem">
      <p>Custom designs are managed in the Page Builder. Activate one to go live in custom mode.</p>
      <ul>
        <li v-for="c in data?.customizations || []" :key="c.id">
          {{ c.name || 'Untitled' }} <span v-if="c.is_active" class="vc-muted">(active)</span>
        </li>
      </ul>
      <button class="vc-btn" @click="setMode('custom')">Use custom design mode</button>
      <p class="vc-muted" style="margin-top:1rem">Full drag-and-drop builder remains on the Laravel member URL until the Nuxt builder port is complete.</p>
    </div>

    <div v-if="drawer" class="drawer" @click.self="drawer = false">
      <div class="phone">
        <iframe :src="previewUrl" title="Preview" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'member' })
const { api } = useApi()
const { data, refresh } = await useAsyncData('appearance', () => api<any>('/member/appearance'))
const tab = ref<'template' | 'custom'>((data.value?.appearance_mode as any) || 'template')
const selectedTemplate = ref(data.value?.template_id || 1)
const drawer = ref(false)

const previewUrl = computed(() => {
  const base = data.value?.preview_base || '/'
  if (tab.value === 'template') return `${base}?preview_template=${selectedTemplate.value}`
  return base
})

async function pickTemplate(n: number) {
  selectedTemplate.value = n
  await api('/member/appearance/mode', {
    method: 'POST',
    body: { appearance_mode: 'template', template_id: n },
  })
  await refresh()
}

async function setMode(mode: string) {
  await api('/member/appearance/mode', { method: 'POST', body: { appearance_mode: mode } })
  await refresh()
}
</script>

<style scoped>
.bar { display:flex; justify-content:space-between; gap:1rem; align-items:flex-start; margin-bottom:1rem; }
.tabs { display:flex; gap:.5rem; margin-bottom:1rem; flex-wrap:wrap; }
.tabs button { border:1px solid var(--vc-border); background:transparent; color:var(--vc-text); padding:.5rem 1rem; border-radius:11px; cursor:pointer; }
.tabs button.active { background: var(--vc-gradient); color:#06101f; border:0; font-weight:600; }
.grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(140px,1fr)); gap:.75rem; }
.tpl { padding:1.5rem; cursor:pointer; text-align:center; }
.tpl.selected { outline:2px solid var(--vc-accent); }
.drawer { position:fixed; inset:0; background:rgba(0,0,0,.55); display:flex; justify-content:center; align-items:center; z-index:50; }
.phone { width:min(390px,92vw); height:min(780px,90vh); border-radius:28px; overflow:hidden; border:1px solid var(--vc-border); background:#000; }
.phone iframe { width:100%; height:100%; border:0; background:#fff; }
</style>

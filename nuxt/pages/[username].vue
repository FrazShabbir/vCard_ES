<template>
  <div>
    <div v-if="pending" class="vc-muted">Loading card…</div>
    <div v-else-if="error" class="vc-panel" style="padding:1.5rem;max-width:480px;margin:2rem auto">
      <h1>Card not found</h1>
      <p class="vc-muted">That username is free — create an account to claim it.</p>
      <NuxtLink class="vc-btn" to="/auth/register">Create account</NuxtLink>
    </div>
    <div v-else-if="card?.paused" class="paused vc-panel">
      <h1>{{ card.user.full_name }}</h1>
      <p v-if="card.user.phone">{{ card.user.phone }}</p>
      <p>This profile is paused. Create your own card to stay in touch.</p>
      <NuxtLink class="vc-btn" to="/auth/register">Create a card</NuxtLink>
    </div>
    <article v-else class="card" :style="cardStyle">
      <header class="hero">
        <img v-if="card?.profile?.avatar" :src="card.profile.avatar" alt="" class="avatar" />
        <h1>{{ card?.user?.full_name }}</h1>
        <p v-if="card?.profile?.designation" class="vc-muted">{{ card.profile.designation }}</p>
        <p v-if="card?.profile?.organization">{{ card.profile.organization }}</p>
      </header>
      <section v-if="card?.profile?.bio">
        <h2>About</h2>
        <p>{{ card.profile.bio }}</p>
      </section>
      <section v-if="card?.socials?.length">
        <h2>Links</h2>
        <div class="links">
          <a v-for="s in card.socials" :key="s.id" :href="s.url" target="_blank" rel="noopener" class="vc-btn vc-btn--ghost">{{ s.platform || 'Link' }}</a>
        </div>
      </section>
      <section v-if="card?.shop?.products?.length">
        <h2>{{ card.shop.name || 'Shop' }}</h2>
        <div class="products">
          <div v-for="p in card.shop.products" :key="p.id" class="vc-panel product">
            <img v-if="p.image" :src="p.image" alt="" />
            <strong>{{ p.name }}</strong>
            <span>{{ p.sale_price || p.original_price }}</span>
          </div>
        </div>
      </section>
      <section v-for="sec in card?.sections || []" :key="sec.id">
        <h2>{{ sec.title }}</h2>
        <p v-if="sec.subtitle" class="vc-muted">{{ sec.subtitle }}</p>
        <div v-html="sec.content" />
      </section>
      <button class="vc-btn save" type="button" @click="showSheet = true">Save contact</button>
      <footer v-if="card?.watermark" class="watermark">Made with {{ appName }}</footer>
    </article>

    <div v-if="showSheet" class="sheet" @click.self="showSheet = false">
      <div class="sheet-panel vc-panel">
        <h3>Save this contact</h3>
        <p class="vc-muted">Opens a contact file your phone can import.</p>
        <a class="vc-btn" :href="card?.download_vcard_url">Download contact</a>
        <button class="vc-btn vc-btn--ghost" type="button" @click="showSheet = false">Not now</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'public' })
const route = useRoute()
const config = useRuntimeConfig()
const appName = config.public.appName
const username = route.params.username as string
const showSheet = ref(false)

const { data: card, pending, error } = await useAsyncData(`card-${username}`, () =>
  $fetch(`/api/v1/cards/${username}`)
)

const cardStyle = computed(() => {
  const c = (card.value as any)?.customization?.design_settings?.colors || {}
  return {
    '--primary': c.primary || '#5b8cff',
    '--bg': c.background || 'transparent',
    '--text': c.text || 'inherit',
  }
})

onMounted(() => {
  setTimeout(() => {
    if (card.value && !(card.value as any).paused) showSheet.value = true
  }, 4000)
})

useSeoMeta({
  title: () => (card.value as any)?.user?.full_name || username,
  description: () => (card.value as any)?.profile?.bio || '',
})
</script>

<style scoped>
.card { max-width: 720px; margin: 0 auto; padding: 1rem 0 4rem; }
.hero { text-align:center; margin-bottom: 1.5rem; }
.avatar { width:120px; height:120px; border-radius:50%; object-fit:cover; }
.links, .products { display:flex; flex-wrap:wrap; gap:.5rem; }
.product { padding:.75rem; width:140px; }
.product img { width:100%; border-radius:11px; }
.save { position: sticky; bottom: 1rem; width: 100%; max-width: 720px; }
.watermark {
  margin-top: 2rem;
  padding: .85rem;
  text-align: center;
  border-top: 1px solid var(--vc-border);
  background: rgba(91,140,255,.12);
  border-radius: var(--vc-radius-sm);
}
.paused { max-width:480px; margin:3rem auto; padding:1.5rem; text-align:center; }
.sheet { position:fixed; inset:0; background:rgba(0,0,0,.45); display:flex; align-items:flex-end; justify-content:center; z-index:40; }
.sheet-panel { width:min(480px,100%); padding:1.25rem; border-radius:18px 18px 0 0; display:grid; gap:.65rem; }
</style>

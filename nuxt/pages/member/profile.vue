<template>
  <div>
    <h1>My profile</h1>
    <form class="vc-panel form" @submit.prevent="save">
      <label>First name<input v-model="form.first_name" class="vc-input" /></label>
      <label>Last name<input v-model="form.last_name" class="vc-input" /></label>
      <label>Phone<input v-model="form.phone" class="vc-input" /></label>
      <label>Organisation<input v-model="form.organization" class="vc-input" /></label>
      <label>Designation<input v-model="form.designation" class="vc-input" /></label>
      <label>Bio<textarea v-model="form.bio" class="vc-input" rows="4" /></label>
      <label>Address<input v-model="form.address" class="vc-input" /></label>
      <p v-if="msg" class="vc-muted">{{ msg }}</p>
      <p v-if="error" class="err">{{ error }}</p>
      <button class="vc-btn" type="submit">Save</button>
    </form>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'member' })
const { api } = useApi()
const { data } = await useAsyncData('member-profile', () => api<any>('/member/profile'))
const form = reactive({
  first_name: data.value?.user?.first_name || '',
  last_name: data.value?.user?.last_name || '',
  phone: data.value?.user?.phone || '',
  organization: data.value?.profile?.organization || '',
  designation: data.value?.profile?.designation || '',
  bio: data.value?.profile?.bio || '',
  address: data.value?.profile?.address || '',
})
const msg = ref('')
const error = ref('')

async function save() {
  msg.value = ''
  error.value = ''
  try {
    await api('/member/profile', { method: 'PUT', body: form })
    msg.value = 'Profile saved.'
  } catch (e: any) {
    error.value = e?.data?.message || 'Could not save.'
  }
}
</script>

<style scoped>
.form { padding: 1.25rem; max-width: 560px; }
label { display:block; margin: .7rem 0; font-weight:600; }
.err { color: var(--vc-danger); }
</style>

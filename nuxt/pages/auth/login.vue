<template>
  <div class="auth vc-panel">
    <h1>Sign in</h1>
    <p class="vc-muted">Use the email and password for your card account.</p>
    <form @submit.prevent="submit">
      <label>Email<input v-model="email" class="vc-input" type="email" required /></label>
      <label>Password<input v-model="password" class="vc-input" type="password" required /></label>
      <p v-if="error" class="err">{{ error }}</p>
      <button class="vc-btn" type="submit" :disabled="loading">{{ loading ? 'Signing in…' : 'Sign in' }}</button>
    </form>
    <p class="vc-muted">No account? <NuxtLink to="/auth/register">Create one</NuxtLink></p>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'default' })
const { api, setToken } = useApi()
const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

async function submit() {
  error.value = ''
  loading.value = true
  try {
    const res = await api<any>('/auth/login', {
      method: 'POST',
      body: { email: email.value, password: password.value },
    })
    setToken(res.token)
    if (res.needs_verification) {
      await navigateTo('/auth/verify')
    } else {
      await navigateTo('/member')
    }
  } catch (e: any) {
    error.value = e?.data?.message || 'Could not sign in.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.auth { max-width: 420px; margin: 3rem auto; padding: 1.5rem; }
label { display:block; margin: .85rem 0; font-weight: 600; }
.vc-input { margin-top: .35rem; }
.err { color: var(--vc-danger); }
.vc-btn { width: 100%; margin-top: 1rem; }
</style>

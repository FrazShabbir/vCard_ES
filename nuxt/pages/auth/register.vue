<template>
  <div class="auth vc-panel">
    <h1>Create your account</h1>
    <p class="vc-muted">One screen. No billing step — you get a free month after you verify email.</p>
    <form @submit.prevent="submit">
      <label>First name<input v-model="form.first_name" class="vc-input" required /></label>
      <label>Last name<input v-model="form.last_name" class="vc-input" required /></label>
      <label>Email<input v-model="form.email" class="vc-input" type="email" required /></label>
      <label>Phone (optional)<input v-model="form.phone" class="vc-input" /></label>
      <label>Account type
        <select v-model="form.account_type" class="vc-input">
          <option value="personal">Personal</option>
          <option value="business">Business</option>
        </select>
      </label>
      <label>Password<input v-model="form.password" class="vc-input" type="password" required /></label>
      <label>Confirm password<input v-model="form.password_confirmation" class="vc-input" type="password" required /></label>
      <label class="check"><input v-model="form.terms" type="checkbox" required /> I agree to the terms</label>
      <label class="check"><input v-model="form.marketing_opt_in" type="checkbox" /> Send me product updates (optional)</label>
      <p v-if="error" class="err">{{ error }}</p>
      <button class="vc-btn" type="submit" :disabled="loading">{{ loading ? 'Creating…' : 'Create account' }}</button>
    </form>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'default' })
const { api, setToken } = useApi()
const form = reactive({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  account_type: 'personal',
  password: '',
  password_confirmation: '',
  terms: false,
  marketing_opt_in: false,
})
const error = ref('')
const loading = ref(false)

async function submit() {
  error.value = ''
  loading.value = true
  try {
    const res = await api<any>('/auth/register', { method: 'POST', body: { ...form, terms: form.terms ? true : undefined } })
    setToken(res.token)
    await navigateTo('/auth/verify')
  } catch (e: any) {
    const msg = e?.data?.message
    const errors = e?.data?.errors
    error.value = msg || (errors ? Object.values(errors).flat().join(' ') : 'Could not create the account.')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.auth { max-width: 460px; margin: 2rem auto; padding: 1.5rem; }
label { display:block; margin: .75rem 0; font-weight: 600; }
.check { font-weight: 500; display:flex; gap:.5rem; align-items:center; }
.vc-input { margin-top: .35rem; }
.err { color: var(--vc-danger); }
.vc-btn { width: 100%; margin-top: 1rem; }
</style>

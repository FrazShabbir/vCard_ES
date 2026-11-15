<template>
  <div class="auth vc-panel">
    <h1>Verify your email</h1>
    <p class="vc-muted">Enter the 6-digit code. In local development it is printed in the Laravel log.</p>
    <form @submit.prevent="submit">
      <input
        v-model="code"
        class="vc-input code"
        maxlength="6"
        inputmode="numeric"
        autocomplete="one-time-code"
        @input="onInput"
      />
      <p v-if="error" class="err">{{ error }}</p>
      <button class="vc-btn" type="submit" :disabled="loading || code.length !== 6">
        {{ loading ? 'Checking…' : 'Verify' }}
      </button>
    </form>
    <button class="vc-btn vc-btn--ghost" :disabled="cooldown > 0" @click="resend">
      {{ cooldown > 0 ? `Resend in ${cooldown}s` : 'Resend code' }}
    </button>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'default' })
const { api } = useApi()
const code = ref('')
const error = ref('')
const loading = ref(false)
const cooldown = ref(0)
let timer: any

function onInput() {
  code.value = code.value.replace(/\D/g, '').slice(0, 6)
  if (code.value.length === 6) submit(code.value)
}

async function submit(passed?: string) {
  const value = passed || code.value
  if (value.length !== 6 || loading.value) return
  error.value = ''
  loading.value = true
  try {
    await api('/auth/verify-otp', { method: 'POST', body: { code: value } })
    await navigateTo('/member/onboarding')
  } catch (e: any) {
    error.value = e?.data?.message || 'That code is wrong or has expired.'
  } finally {
    loading.value = false
  }
}

async function resend() {
  try {
    await api('/auth/resend-otp', { method: 'POST' })
    cooldown.value = 60
    clearInterval(timer)
    timer = setInterval(() => {
      cooldown.value -= 1
      if (cooldown.value <= 0) clearInterval(timer)
    }, 1000)
  } catch (e: any) {
    error.value = e?.data?.message || 'Could not resend.'
  }
}
</script>

<style scoped>
.auth { max-width: 420px; margin: 3rem auto; padding: 1.5rem; text-align: center; }
.code { text-align: center; letter-spacing: .4em; font-size: 1.4rem; }
.err { color: var(--vc-danger); }
.vc-btn { width: 100%; margin-top: .75rem; }
</style>

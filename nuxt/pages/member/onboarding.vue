<template>
  <div>
    <h1>Get started</h1>
    <p class="vc-muted">A short path to launch your card.</p>
    <div v-for="step in steps" :key="step.id" class="vc-panel step">
      <strong>{{ step.n }}. {{ step.title }}</strong>
      <p class="vc-muted">{{ step.desc }}</p>
      <NuxtLink class="vc-btn" :to="step.path">{{ step.cta }}</NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'member' })
const steps = [
  { id: 'profile', n: 1, title: 'Complete your profile', desc: 'Add a bio and organisation or address.', path: '/member/profile', cta: 'Open profile' },
  { id: 'appearance', n: 2, title: 'Set appearance', desc: 'Pick a template or build a custom page.', path: '/member/appearance', cta: 'Open appearance' },
  { id: 'plan', n: 3, title: 'Choose a plan when ready', desc: 'Free month first; then Personal or Business in PKR.', path: '/member/subscription', cta: 'View plans' },
]
</script>

<style scoped>
.step { padding: 1.1rem; margin: .85rem 0; }
</style>

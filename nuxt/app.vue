<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<script setup lang="ts">
onMounted(() => {
  if (typeof document !== 'undefined') {
    document.documentElement.setAttribute('data-scheme', 'dark')
  }
})
</script>

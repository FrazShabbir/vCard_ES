<template>
  <div>
    <h1>My card</h1>
    <div class="vc-panel" style="padding:1.25rem">
      <h2 style="margin-top:0">Printed NFC card</h2>
      <ul>
        <li v-for="line in quote?.lines || []" :key="line.label">{{ line.label }} — {{ line.amount_label }}</li>
      </ul>
      <p><strong>Total: {{ quote?.total_label }}</strong></p>
      <form @submit.prevent="order" class="form">
        <label>Delivery address<textarea v-model="delivery_address" class="vc-input" required rows="3" /></label>
        <label>Phone<input v-model="phone" class="vc-input" required /></label>
        <button class="vc-btn" type="submit">{{ quote?.button_label || 'Order now' }}</button>
      </form>
      <p v-if="msg">{{ msg }}</p>
      <div v-if="orderData?.order" style="margin-top:1rem">
        <h3>Current order</h3>
        <p>Status: {{ orderData.order.status }} · Total: {{ orderData.order.total }}</p>
        <ol>
          <li v-for="s in orderData.status_history || []" :key="s.id">{{ s.status }} — {{ s.comment }}</li>
        </ol>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'member' })
const { api } = useApi()
const { data: orderData, refresh } = await useAsyncData('card-order', () => api<any>('/member/card-order'))
const quote = computed(() => orderData.value?.quote)
const delivery_address = ref('')
const phone = ref('')
const msg = ref('')

async function order() {
  msg.value = ''
  try {
    const res = await api<any>('/member/card-order', {
      method: 'POST',
      body: { delivery_address: delivery_address.value, phone: phone.value },
    })
    msg.value = res.message
    await refresh()
  } catch (e: any) {
    msg.value = e?.data?.message || 'Could not place the order.'
  }
}
</script>

<style scoped>
.form label { display:block; margin:.75rem 0; font-weight:600; }
</style>

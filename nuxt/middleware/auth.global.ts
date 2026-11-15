export default defineNuxtRouteMiddleware(async (to) => {
  const token = useCookie('vc_token')
  const needsAuth = to.path.startsWith('/member') || to.path.startsWith('/admin')
  const isAuthPage = to.path.startsWith('/auth')

  if (needsAuth && !token.value) {
    return navigateTo('/auth/login')
  }

  if (isAuthPage && token.value && to.path !== '/auth/verify') {
    return navigateTo('/member')
  }
})

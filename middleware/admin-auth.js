export default defineNuxtRouteMiddleware(() => {
  if (process.server) return
  const loggedIn = sessionStorage.getItem('import_logged_in') === '1'
  if (!loggedIn) {
    return navigateTo('/admin')
  }
})

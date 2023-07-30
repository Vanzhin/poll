import { useUserStore  } from '../stores/UserStore'


export default defineNuxtRouteMiddleware((to, from) => {
  // isAuthenticated() is an example method verifying if a user is authenticated
  const user = useUserStore()
  if (user.getIsAutchUser === false) {
    return navigateTo('/login')
  }
})
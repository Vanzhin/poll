import { useUserStore  } from '../stores/UserStore'


export default defineNuxtRouteMiddleware((to, from) => {
  // isAuthenticated() is an example method verifying if a user is authenticated
  const user = useUserStore()
  console.log('from -', from)
  console.log('to -', to)
  if (user.getIsAutchUser === false) {
  
    return navigateTo('/user/autch')
  }
})
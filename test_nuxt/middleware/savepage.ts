import { useUserStore  } from '../stores/UserStore'


export default defineNuxtRouteMiddleware((to, from) => {
  
  const user = useUserStore()
  user.savePage(from.path)
  console.log('from -', from)
  console.log('to -', to)
  // user.savePage()
  return
})
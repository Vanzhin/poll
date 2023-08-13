import { useUserStore } from '../stores/UserStore'
import { useLoaderStore } from '../stores/Loader'
import { useModalStore  } from '../stores/ModalStore'

export default async function setUseAsyncFetch ({url, params, token, loading = true}){
  const user = useUserStore() 
  const loader = useLoaderStore() 
  const modal = useModalStore()
  loader.setIsLoaderStatus(loading)
  if (user.getIsAutchUser && token) {
    params.headers.Authorization = `Bearer ${user.getToken}`
  }

  const { data: result, pending, error } = await useAsyncData(
    () => $fetch(url, params))
    
  if (error.value) {
    console.log(error.value.data)
    if (error.value.data){
      if (error.value.data.message === "Expired JWT Token"||
        error.value.data.message === "Invalid JWT Token") 
      {
        await user.getAuthRefresh() //обновление токена
        if (!user.getIsAutchUser) {
          navigateTo(`/user/autch`)
          loader.setIsLoaderStatus(false)
          return
        }
        setUseAsyncFetch({ url, params, token })
      }
    } else {
      modal.setMessageError(error.value)
    }
  } else{
    console.log('result.value -',result.value)
    loader.setIsLoaderStatus(false)
    return result.value
  }
  loader.setIsLoaderStatus(false)
}
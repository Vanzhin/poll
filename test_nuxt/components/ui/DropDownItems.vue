<template>
  <div class="d-dropdown">
    <button class="btn btn-secondary "  tooggle="false" 
      @click="toggleMeny"
    >
      <div class="dropdown-button">
        <div class="title"> Личный кабинет</div> 
        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M15.4828 10.3529C15.7617 10.0746 16.2133 10.0745 16.4924 10.3527C16.7725 10.632 16.7727 11.0856 16.4927 11.365L12.7064 15.1442C12.3161 15.5339 11.6839 15.5339 11.2936 15.1442L7.50726 11.3651C7.22731 11.0856 7.22745 10.632 7.50757 10.3527C7.78666 10.0745 8.23826 10.0746 8.51725 10.3529L12 13.8275L15.4828 10.3529Z" fill="#269EB7"/>
        </svg>
      </div>
      <div class="dropdown-button-mobale">
        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5 7.75C14.5 9.13071 13.3807 10.25 12 10.25C10.6193 10.25 9.5 9.13071 9.5 7.75C9.5 6.36929 10.6193 5.25 12 5.25C13.3807 5.25 14.5 6.36929 14.5 7.75ZM16 7.75C16 9.95914 14.2091 11.75 12 11.75C9.79086 11.75 8 9.95914 8 7.75C8 5.54086 9.79086 3.75 12 3.75C14.2091 3.75 16 5.54086 16 7.75ZM5.5 21.6912C5.5 21.7108 5.5001 21.7304 5.5003 21.75H4.00024L4 21.6912C4 17.3054 7.61421 13.75 12 13.75C16.3858 13.75 20 17.3054 20 21.6912L19.9998 21.75H18.4997C18.4999 21.7304 18.5 21.7108 18.5 21.6912C18.5 18.1482 15.5718 15.25 12 15.25C8.4282 15.25 5.5 18.1482 5.5 21.6912Z" fill="#269EB7"/>
        </svg>

      </div>
    </button>
    <ul 
      class="dropdown-menu menu" 
      :class="classObject"
    >
      <div v-if="user.getIsAutchUser">
        <li>
          <NuxtLink class="dropdown-item menu_li" to='/user/statistics'>Статистика</NuxtLink>
        </li>
        <li>
          <NuxtLink class="dropdown-item menu_li" to='/user/profile'>Профиль</NuxtLink>
        </li>
        <!-- <li><NuxtLink class="dropdown-item menu_li" to="statistics">Статистика</NuxtLink></li>
        <li><NuxtLink class="dropdown-item menu_li" to="userAutchProfile">Профиль</NuxtLink></li> -->
       <!-- admin -->
        <li
          v-if="user.getUserAdmin"
        ><NuxtLink class="dropdown-item menu_li" to='/admin'>Админка</NuxtLink>
        </li>
        <li><hr class="menu-hr"></li>
        <li class="dropdown-item menu-item menu_li" @click="logOut" >
          <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.46826 5.32654H15.4683V3.82654H8.46826C6.81141 3.82654 5.46826 5.16968 5.46826 6.82654V18.8265C5.46826 20.4834 6.81141 21.8265 8.46826 21.8265H15.4683V20.3265H8.46826C7.63983 20.3265 6.96826 19.655 6.96826 18.8265V6.82654C6.96826 5.99811 7.63983 5.32654 8.46826 5.32654ZM14.5808 16.0106L15.6398 17.0696L16.7005 16.009L19.1754 13.5341C19.5659 13.1436 19.5659 12.5104 19.1754 12.1199L16.7005 9.64501L15.6398 8.58435L14.5808 9.6434L17.0139 12.0765L9.46826 12.0765V13.5765L17.0148 13.5765L14.5808 16.0106Z" fill="white"/>
          </svg>

          Выйти</li>
      </div> 
      <div v-else>
        <li><div class="dropdown-item menu_li" @click="clickNavigate('/user/autch')">Авторизоваться</div></li>
        <li><div class="dropdown-item menu_li" @click="clickNavigate('/user/autch/link')">По ссылке</div></li>
        <li><div class="dropdown-item menu_li" @click="clickNavigate('/user/signup')">Зарегистрироваться</div></li>
      </div>
    </ul>
  </div>
</template>
<script setup>
  const route = useRoute()
  import { useUserStore  } from '../../stores/UserStore'
  
  const user = useUserStore()
  const toggle = ref({toggle: false, click: 0})
  
  function toggleMeny(){
    console.log('toggle')
    toggle.value.toggle = !toggle.value.toggle
    toggle.value.click =  0
    if (toggle.value.toggle){
      window.addEventListener('click', dropDownClickVisible)
    }
  }
  onMounted(() => {
    const user = useUserStore()
  })
  const classObject = computed(() => ({
    active: toggle.value.toggle,
  
  }))
  function dropDownClickVisible(){
    if (toggle.value.toggle && toggle.value.click > 0){
      toggle.value.toggle = false
      toggle.value.click =  0
      window.removeEventListener("click", dropDownClickVisible);
    } else {
      toggle.value.click += 1
      
    }
  }
  async function logOut(){
    await  user.getLogOutUser()
    navigateTo('/')
  }
  async function clickNavigate(link){
    console.log(route.path)
    user.savePage(route.path)
    navigateTo(link)
  }
</script>   

<style lang="scss" scoped>

.dropdown-button {
  display: flex;
  @media (max-width: 480px) {
    display: none;
  }
  &-mobale{
    display: none;
    @media (max-width: 480px) {
    display: flex;
  }
  }

}
  .btn{
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    padding: 10px 5px 10px 10px;
    gap: 10px;
    width: 175px;
    height: 36px;
    border: 1px solid var(--color-blue);
    border-radius: 6px;
    background-color: rgba(255, 255, 255, 0);
    font-family: 'Lato';
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: var(--color-blue);
    @media (max-width: 480px) {
      width: 22px;
      border: 1px solid var(--color-fon);
      padding:0;
      justify-content: center;
      margin: 0 auto 0;
    }
    &:hover{
      cursor: pointer;
      background: var(--color-blue);
      .title{
        color: var(--color-white);
      };
      path{
        fill:var(--color-white);
      }
    }
  }
  .title{
    margin-top: 0;
    font-family: 'Lato';
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
  }
  .menu{
    top: 30px;
    transform: translate(0px, 40px);
    width: 172px;
    background: var(--color-blue);
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    &-item{
      display: flex;
    }
   &-hr{
    margin:10px 15px ;
    border-top: 1px solid var(--color-white);
    color: var(--color-white);
    opacity: 1;
   }
    &_li{
      font-family: 'Lato';
      font-style: normal;
      font-weight: 400;
      font-size: 16px;
      line-height: 24px;
      color: var(--color-white);
    &:hover{
      & path {
        fill: var(--color-blue);
      }
      cursor: pointer;
      color: var(--color-blue);
    }}
  }
  .active{
    display: block;
  }
</style>

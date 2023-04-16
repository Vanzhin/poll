<template>
  <div class="dropdown">
    <button class="btn btn-secondary "  data-bs-toggle="dropdown" aria-expanded="false">
        <div class="title"> Личный кабинет</div> 
        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M15.4828 10.3529C15.7617 10.0746 16.2133 10.0745 16.4924 10.3527C16.7725 10.632 16.7727 11.0856 16.4927 11.365L12.7064 15.1442C12.3161 15.5339 11.6839 15.5339 11.2936 15.1442L7.50726 11.3651C7.22731 11.0856 7.22745 10.632 7.50757 10.3527C7.78666 10.0745 8.23826 10.0746 8.51725 10.3529L12 13.8275L15.4828 10.3529Z" fill="#269EB7"/>
        </svg>

    </button>
    <ul 
      class="dropdown-menu menu" 
      aria-labelledby="dropdownMenuButton1"
    >
      <div v-if="getIsAutchUser">
        <li><RouterLink class="dropdown-item menu_li" :to="{ name: 'statistics'}">Статистика</RouterLink></li>
        <li><RouterLink class="dropdown-item menu_li" :to="{ name: 'userAutchProfile'}">Профиль</RouterLink></li>
       
        <li
          v-if="getUserAdmin"
        ><RouterLink class="dropdown-item menu_li" :to="{ name: 'admin'}">Админка</RouterLink>
        </li>
        <li><hr class="menu-hr"></li>
        <li class="dropdown-item menu-item menu_li" @click="logOut" >
          <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.46826 5.32654H15.4683V3.82654H8.46826C6.81141 3.82654 5.46826 5.16968 5.46826 6.82654V18.8265C5.46826 20.4834 6.81141 21.8265 8.46826 21.8265H15.4683V20.3265H8.46826C7.63983 20.3265 6.96826 19.655 6.96826 18.8265V6.82654C6.96826 5.99811 7.63983 5.32654 8.46826 5.32654ZM14.5808 16.0106L15.6398 17.0696L16.7005 16.009L19.1754 13.5341C19.5659 13.1436 19.5659 12.5104 19.1754 12.1199L16.7005 9.64501L15.6398 8.58435L14.5808 9.6434L17.0139 12.0765L9.46826 12.0765V13.5765L17.0148 13.5765L14.5808 16.0106Z" fill="white"/>
          </svg>

          Выйти</li>
      </div> 
      <div v-else>
        <li><RouterLink class="dropdown-item menu_li" :to="{ name: 'logout'}">Авторизоваться</RouterLink></li>
        <li><RouterLink class="dropdown-item menu_li" :to="{ name: 'logoutlink'}">По ссылке</RouterLink></li>
        <li><RouterLink class="dropdown-item menu_li" :to="{ name: 'signup'}">Зарегистрироваться</RouterLink></li>
      </div>
    </ul>
  </div>
</template>
<script>
import { RouterLink } from 'vue-router'
import { mapGetters, mapActions } from "vuex"
export default {
  computed:{
    ...mapGetters(["getIsAutchUser", "getUserAdmin"]),
  },
  methods:{
    ...mapActions(["getLogOutUser"]),
    async logOut(){
      await  this.getLogOutUser()
      this.$router.push({ name: 'home'})
    },
  }
}
</script>   

<style lang="scss" scoped>
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
    &:hover{
      cursor: pointer;
      background: var(--color-blue);
      color: var(--color-white);
      path{
        fill:var(--color-white);
      }
    }
  }
  .title{
    font-family: 'Lato';
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
  }
  .menu{
    transform: translate(-40px, 40px);
    width: 172px;
    background: var(--color-blue);
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
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
</style>

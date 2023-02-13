<template>
    <div class="dropdown">
        <button class="btn btn-secondary "  data-bs-toggle="dropdown" aria-expanded="false">
           Личный кабинет
        </button>
        <ul 
          class="dropdown-menu menu" 
          aria-labelledby="dropdownMenuButton1"
         
        >
          <div v-if="getIsAutchUser">
            <li><RouterLink class="dropdown-item" :to="{ name: 'statistics'}">Статистика</RouterLink></li>
            <li><hr class="dropdown-divider"></li>
            <li><RouterLink class="dropdown-item" :to="{ name: 'home'}" @click="logOut">Выйти</RouterLink></li>
          </div> 
          <div v-else>
            <li><RouterLink class="dropdown-item" :to="{ name: 'logout'}">Авторизоваться</RouterLink></li>
            <li><RouterLink class="dropdown-item" :to="{ name: 'logoutlink'}">По ссылке</RouterLink></li>
            <li><RouterLink class="dropdown-item" :to="{ name: 'signup'}">Зарегистрироваться</RouterLink></li>
          </div>
        </ul>
    </div>

</template>
<script>
import { RouterLink } from 'vue-router'
import { mapGetters, mapActions } from "vuex"
export default {
  computed:{
    ...mapGetters(["getIsAutchUser"]),
  },
  methods:{
    ...mapActions(["getLogOutUser"]),
    async logOut(){
      await  this.getLogOutUser()
      // this.$router.push({ name: 'home'})
    },
  }
}
</script>   

<style lang="scss" scoped>
  .btn{
    padding: 0 40px;
  }
  .menu{
    transform: translate(-40px, 40px);
  }
</style>

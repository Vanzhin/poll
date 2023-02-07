<template>
    <div  @click="logOut">{{ getIsAutchUser }}</div>
    <div class="dropdown">
        <button class="btn btn-secondary "  data-bs-toggle="dropdown" aria-expanded="false">
           Личный кабинет
        </button>
        <ul 
          class="dropdown-menu menu" 
          aria-labelledby="dropdownMenuButton1"
         
        >
          <div v-if="getIsAutchUser">
            <li><RouterLink class="dropdown-item" :to="{ name: ''}">Статистика</RouterLink></li>
            <li><hr class="dropdown-divider"></li>
            <li><RouterLink class="dropdown-item" :to="{ name: ''}" @click="logOut">Выйти</RouterLink></li>
          </div> 
          <div v-else>
            <li><RouterLink class="dropdown-item" :to="{ name: 'logout'}">Авторизоваться</RouterLink></li>
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
    ...mapActions(["setIsAutchUser",]),
    async logOut(){
      await  this.setIsAutchUser(false)
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

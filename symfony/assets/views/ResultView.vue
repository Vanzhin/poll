<template>
  <HeadersPage
      title="Тестирование Базовый курс"
      :subTitle="getTestTitleActive"
  />
  <div class="fon">
    <div class="wrapper">
      <div class="ticket-title">
        Режим тотальной проверки
      </div>
      <div class="ticket-number">
        {{  getTicketTitle }}
      </div>
      <Loader
        v-if="loader"
      />
      <div
        v-else
      >
        <ResultAutchView
          v-if="getIsAutchUser"
        />
        <ResultNoAutchView
          v-else
        />
      </div> 
    </div>
  </div> 
</template>
<script>

import ResultAutchView from './ResultAutchView.vue'
import ResultNoAutchView from './ResultNoAutchView.vue'
import Loader from '../components/ui/LoaderView.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
import HeadersPage from '../components/HeadersPage.vue'
export default {
  components: {
    ResultAutchView,
    ResultNoAutchView,
    Loader,
    HeadersPage
  },
  data() {
    return {
      loader: false
    }
  },
  computed:{
    ...mapGetters([
      "getIsAutchUser", 
      "getAutchUserToken", 
      "getTicketTitle",
      "getTestTitleActive",
    ]),
    
  },
  methods: {
    ...mapActions(["getQuestionsDb", "setResultDb", "getAuthRefresh"]),
  },
  async mounted(){
    console.log('монтирую результат')
    
  },
  async created(){
    console.log('создаю результат')
    this.loader = true
    window.scroll(0, 0);
    await this.setResultDb( {token: this.getAutchUserToken, userAuth:this.getIsAutchUser})
    
    this.loader = false
  },
  unmounted(){
    console.log('размонтирую результат!')
  }

} 

</script>
<style lang="scss" scoped>
  .ticket{
    &-title{
      padding-top: 27px;
      font-weight: 700;
      font-size: 20px;
      line-height: 40px;
      color: var(--color-Black_blue);
    }
    &-number{
      width: 148px;
      height: 36px;
      color: var(--color-blue);
      border: 1px solid var(--color-blue);
      border-radius: 6px;
      display: flex;
      justify-content: center;
      align-items: center;
     
    }
  }
</style>

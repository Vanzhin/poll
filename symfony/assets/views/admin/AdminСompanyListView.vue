<template>
   <Loader
    v-if="isLoader"
  />
  <div class="fon"
    v-else
  >
    
    <div class="container">
      <div class="wrapper">
        <div class="row">
          <div class="title">
            <h2>Компании</h2>
            <div class="button-cont">
              <div class="button"
                title="Добавить новую команию"
                @click.stop="createCompany"
              >
                <svg width="36" height="37" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M30.4023 18.8252C30.4023 25.9054 24.6627 31.645 17.5825 31.645C10.5023 31.645 4.7627 25.9054 4.7627 18.8252C4.7627 11.745 10.5023 6.00537 17.5825 6.00537C24.6627 6.00537 30.4023 11.745 30.4023 18.8252ZM31.9023 18.8252C31.9023 26.7338 25.4911 33.145 17.5825 33.145C9.6739 33.145 3.2627 26.7338 3.2627 18.8252C3.2627 10.9166 9.6739 4.50537 17.5825 4.50537C25.4911 4.50537 31.9023 10.9166 31.9023 18.8252ZM24.7419 19.899H10.4221V17.751H24.7419V19.899Z" fill="#269EB7"/>
                  <path d="M16.5088 11.6646L16.5088 25.9844L18.6567 25.9844L18.6567 11.6646L16.5088 11.6646Z" fill="#269EB7"/>
                </svg>
              </div>
            </div>
          
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="tests__block"
          v-if="getCompanyList">
          <ItemCompany
            v-for="(item, index) in getCompanyList" 
            :key="item.id"
            :item="item"
            :index="index"
           @click.stop="companyRoute({item})"
          /> 
          
        </div>
        <div class="tests__block"
          v-else>
        <h4>Создайте компанию.</h4>
        </div>

        <Pagination
          type="getCompanyList"
          admin= true
        />
      </div>
    </div>

  </div>

  
</template>
 
<script>
  import ItemCompany from "../../components/Admin/ItemCompany.vue"
  import MessageView from "../../components/ui/MessageView.vue"
  import Loader from '../../components/ui/Loader.vue'
  import Pagination from "../../components/Pagination.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      MessageView,
      Pagination,
      ItemCompany
    },
    data() {
      return {
        isLoader: true,
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken", "getMessage", "getCompanyList"]),
    },
   
    methods: { 
      ...mapActions(["saveQuestionDb", "setMessage", "getCompanyListDB"]),
      ...mapMutations(['SET_COMPANY']),
      createCompany(){
        this.$router.push({name: 'adminCompanyCreate', params: {operation:"create", id: 0  } })
      },
      companyRoute({item}){
        this.SET_COMPANY(item)
        this.$router.push({name: 'adminCompanyStaff', params: { id:item.id } })
      }
    },
    async mounted(){
      setTimeout(()=>this.isLoader = false, 1000 )
      
    },
    async created(){
      await this.getCompanyListDB({})
      this.isLoader = false
    },
 } 
 
</script>
<style lang="scss" scoped>
  .title{
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-top: 10px;
    padding: 64px 0 8px 0;
    & h2 {
      font-family: 'Lato';
      font-style: normal;
      font-weight: 700;
      font-size: 30px;
      line-height: 40px;
      color: var(--color-blue);
    }
  }
  .create{
    display: flex;
    align-items: center;
    &-plus{
      transform: scaleY(1.3);
    }
  }
  .button{
    margin-right: 20px;
    &-cont{
      display: flex;
      justify-content: end;
    }
    
    transition: all 0.1s ease-out;
    &:hover{
      cursor: pointer;
      transform: scale(1.15);
    }
  }
 @media (min-width: 1024px) {
  
 }
</style>
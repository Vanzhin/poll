<template>
  <div class="block">
    <div class="title">
      <h2>Тест: {{ testName }}</h2>
    </div>
    <Button/>
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item" role="presentation"
        v-for="(nav, index ) in navsActive" 
          :key="nav.title"
      >
        <RouterLink class="nav-link tabs-fonts" :class="{active: nav.active}"  
          @click="activeTogge(index)"
          :to="{ path: nav.link}" >{{ nav.title }} {{ nav.count }}
        </RouterLink>
       
      </li>
      
    </ul>
  </div>
  <RouterView/>
</template>


<script>
import { RouterLink,  RouterView } from 'vue-router'
import { mapGetters, mapActions, mapMutations} from "vuex"
import Button from "../../components/ui/ButtonBack.vue"
export default {
  components: {
    Button
  },
  data() {
    return {
      
      testId: this.$route.params.id,
      test: null,
      classObject: {
        active: true,
      },
      url: this.$route.path,
      navs:[]
        
    }
  },
  computed:{
    ...mapGetters([
      "getTest",
      "getTestTicketCount",
      "getTestSectionCount",
      "getTestQuestionCount"
    ]),

    testName () {
      return this.getTest.title
    },
    countQuestion(){ 
      return this.getTestQuestionCount
    },
    countTicket(){ 
      return this.getTestTicketCount
    },
    countSection(){ 
      return this.getTestSectionCount
    },
    navsActive(){
      this.url= this.$route.path
     return this.navs=[
        {
          title: `Вопросы `,
          link: 'questions',
          active: (new RegExp("questions", 'i')).test(this.url),
          count: this.countQuestion
        },
        {
          title: `Билеты `,
          link: 'tickets',
          active: (new RegExp("tickets", 'i')).test(this.url),
          count: this.countTicket
        },
        {
          title: `Секции `,
          link: 'sections',
          active: (new RegExp("sections", 'i')).test(this.url),
          count: this.countSection
        }
      ]
    }
  },
   methods: {
    ...mapActions(["getQuestionsTestIdDb", "getTestIdDb"]),
    activeTogge(index) {
      this.navs =[ ...this.navs.map((nav, ind) => {
        index === ind ? nav.active = true: nav.active = false
        return nav
      })]
    },
  },
 mounted(){
  
 },

  async created(){
    
    
    
   
    
    
  },
 
} 

</script>
<style lang="scss" scoped>
  .tabs-fonts{
    font-family: 'Lato';
    font-style: normal;
    font-weight: 700;
    font-size: 16px;
    line-height: 24px;
    color: #0B1F33;
  }
  .block{
    
    padding: 10px ;
  }
  .title{
    display: flex;
    justify-content: space-between;
    margin: 10px;
    & h2{
      font-family: "Lato";
      font-style: normal;
      font-weight: 700;
      font-size: 30px;
      line-height: 40px;
      color: var(--color-blue);
    }
  }
  .test{
    display: flex;
  }
  [class*="col-"] {
  padding-top: 7px;
  padding-right: 7px;
  padding-left: 7px;
  margin-bottom: 10px;
  }
  
@media (min-width: 1024px) {
 
}
</style>

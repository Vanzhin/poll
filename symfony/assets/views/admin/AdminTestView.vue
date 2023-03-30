<template>
 
  <div class="block">
    <div class="title">
      <h2>Тест: {{ testName }}</h2>
    </div>
    <ul class="nav nav-tabs" role="tablist">
     
      <li class="nav-item" role="presentation"
      >
        <RouterLink class="nav-link" :class="{active: navs[0].active}"  
          @click="activeTogge(0)"
          :to="{ path: navs[0].link}" >{{ navs[0].title }} {{ countQuestion }}
        </RouterLink>
       
      </li>
      <li class="nav-item" role="presentation"
      >
        <RouterLink class="nav-link" :class="{active: navs[1].active}"  
          @click="activeTogge(1)"
          :to="{ path: navs[1].link}" >{{ navs[1].title }} {{ countTicket }}
        </RouterLink>
        
      </li>
      <li class="nav-item" role="presentation"
      >
        <RouterLink class="nav-link" :class="{active: navs[2].active}"  
          @click="activeTogge(2)"
          :to="{ path: navs[2].link}" >{{ navs[2].title }} {{ countSection }}
        </RouterLink>
      </li>
    </ul>
  </div>
  <RouterView/>
</template>


<script>
import { RouterLink,  RouterView } from 'vue-router'
import { mapGetters, mapActions, mapMutations} from "vuex"

export default {
  components: {
  
  },
  data() {
    return {
      
      testId: this.$route.params.id,
      test: null,
      classObject: {
        active: true,
      },
      url: this.$route.path,
      navs:[
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
      console.log("1-",this.getTestQuestionCount)
      return this.getTestQuestionCount
    },
    countTicket(){ 
      console.log("1-",this.getTestTicketCount)
      return this.getTestTicketCount
    },
    countSection(){ 
      console.log("1-",this.getTestSectionCount)
      return this.getTestSectionCount
    },
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
    console.log('this.getTest - ', this.$route.params.id)
    await this.getTestIdDb({id: +this.$route.params.id})
    this.navs= [
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
   
    
    
  },
 
} 

</script>
<style lang="scss" scoped>
  .block{
    background-color: rgb(207 207 199);
    padding: 10px ;
  }
  .title{
    display: flex;
    justify-content: space-between;
    margin: 10px;
    & h2{
      font-size: 1.4rem;
      color: #697a3f;
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

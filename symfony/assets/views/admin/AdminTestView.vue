<template>
 
  <div class="block">
    <div class="title">
      <h2>Тест: {{ testName }}</h2>
    </div>
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item" role="presentation"
        v-for="(nav, index ) in navs" 
          :key="nav.title"
      >
        <RouterLink class="nav-link" :class="{active: nav.active}"  
          @click="activeTogge(index)"
          :to="{ path: nav.link}" >{{ nav.title }} {{ nav.count }}</RouterLink>
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
      navs:[]
    }
  },
  computed:{
    ...mapGetters([
      "getTest"
    ]),

    testName () {
      return this.$store.getters.getTest.title
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

  async created(){
    console.log('this.getTest - ', this.$route.params.id)
    console.log(this.$route)
    
    await this.getTestIdDb({id: +this.$route.params.id})
    const url = this.$route.path
    this.navs = [
        {
          title: `Вопросы `,
          link: 'questions',
          active: (new RegExp("questions", 'i')).test(url),
          count: this.getTest.questionCount || ''
        },
        {
          title: `Билеты `,
          link: 'tickets',
          active: (new RegExp("tickets", 'i')).test(url),
          count: this.getTest.ticketCount || ''
        },
        {
          title: `Секции `,
          link: 'sections',
          active: (new RegExp("sections", 'i')).test(url),
          count: this.getTest.sectionCount || ''
        }
      ]
    this.test = this.getTest
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

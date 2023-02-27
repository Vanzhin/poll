<template>
  <div class="">
    <div class="tests__block">
      <h4>{{ areaTitle.title }}</h4>
      <h5>Выберите тест:</h5>
    </div>
    <div class="tests__block">
      <div
        v-for="(test, index) in tests" 
        :key="test.id"
        class="test__block"
      >
        <div class="test__card"
          v-if="getIsAutchUser || index < 3"
        >
          <RouterLink :to="{ name: 'test', params: { id: test.id } }">
            {{ test.id }} - {{ test.title }}
          </RouterLink>
        </div>
        <div class="test__card-limitation "
          v-else
          title="У Вас ограниченный доступ. Подпишитесь на группу."
        >
          {{ test.id }} - {{ test.title }}
        </div>
        
      </div>
    </div> 
         
     
  </div>
</template>
<script>
import { RouterLink } from 'vue-router'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  data() {
    return {
      count: 0
    }
  },
  computed:{
    ...mapGetters(["getIsAutchUser"]),
    areaTitle () {
        console.log(this.$route.params.id)
        console.log(this.$store.getters.getAreaTitle(this.$route.params.id))
        return this.$store.getters.getAreaTitle(this.$route.params.id)
      },
    tests () {
      return this.$store.getters.getTests
    },
  },
} 

</script>
<style lang="scss">
  .tests__block{
    width: 100%;
    padding: 10px;
  }
  .test{
    &__block{
    margin: 10px 10px;}
  }
  .test__card, .test__card-limitation{
    background-color: #e2e5fc;
    padding: 5px;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
  }
  .test__card-limitation{
    color: rgb(134, 134, 132);
  }
  .test__card:hover{
    background-color: aliceblue;
  }
    

</style>

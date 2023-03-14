<template>
  <i class="result"
      :class="{resultTrue: question.result.score }"
  ><b>{{ index+1 }})</b> {{ question.title }}</i>
  <div class=""> 
    <img :src="question.image" 
      v-if="question.image"
      class="img"
    />   
  </div>
  <hr>
</template>
<script>

import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  props: ['question', 'index' ],
  data() {
    return {}
  },
  computed:{
    ...mapGetters([
      "getTest",
      "getQuestion"
    ]),
  },
  methods: {
  },
  created(){
  } 
}
</script>
<style lang="scss" scoped>
  .img{
    height: 130px;
    margin: 3px 10px;
    max-width: 170px;
  }
  .result::before{
    content: "X  ";
    font-family: Geneva, Arial;
    color:rgb(235, 25, 25);
    font-weight: 900;
    font-style:  oblique ;
  }
  .resultTrue::before{
    content: "V  ";
    color:rgb(22, 204, 104);
    font-style:  normal ;
  }
</style>

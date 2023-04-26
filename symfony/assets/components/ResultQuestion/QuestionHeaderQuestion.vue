<template>
  <div class="title-block">
    <div class="title-icon">
      <svg width="24" height="25" viewBox="0 0 24 25" 
        fill="none" xmlns="http://www.w3.org/2000/svg"
        v-if="question.result.correct"
      >
        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23.1659C6.3756 23.1659 1.8 18.5903 1.8 12.9659C1.8 7.34154 6.3756 2.76594 12 2.76594C17.6244 2.76594 22.2 7.34154 22.2 12.9659C22.2 18.5903 17.6244 23.1659 12 23.1659ZM12 0.965942C5.373 0.965942 0 6.33894 0 12.9659C0 19.5935 5.373 24.9659 12 24.9659C18.6276 24.9659 24 19.5935 24 12.9659C24 6.33894 18.6276 0.965942 12 0.965942Z" fill="#56D062"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9818 9.28007C15.8552 9.15827 15.65 9.15827 15.5234 9.28007L11.066 13.5731L9.17003 11.7473C9.04403 11.6255 8.83823 11.6255 8.71162 11.7473L7.64183 12.7781C7.51523 12.8999 7.51523 13.0979 7.64183 13.2197L10.8368 16.2971C10.8998 16.3583 10.9832 16.3889 11.066 16.3889C11.1488 16.3889 11.2316 16.3583 11.2952 16.2971L17.0516 10.7525C17.1128 10.6937 17.1464 10.6145 17.1464 10.5317C17.1464 10.4489 17.1128 10.3691 17.0516 10.3109L15.9818 9.28007Z" fill="#56D062"/>
      </svg>
      <svg width="24" height="24" viewBox="0 0 24 24" 
        fill="none" xmlns="http://www.w3.org/2000/svg"
        v-else
      >
        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 22.2C6.3756 22.2 1.8 17.6244 1.8 12C1.8 6.3756 6.3756 1.8 12 1.8C17.6244 1.8 22.2 6.3756 22.2 12C22.2 17.6244 17.6244 22.2 12 22.2ZM12 0C5.373 0 0 5.373 0 12C0 18.6276 5.373 24 12 24C18.6276 24 24 18.6276 24 12C24 5.373 18.6276 0 12 0Z" fill="#EE3F58"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.44676 7.82643C9.01325 7.39119 8.31038 7.39119 7.87686 7.82643C7.44335 8.26168 7.44335 8.96735 7.87686 9.40259L10.4381 11.974L7.82514 14.5974C7.39162 15.0327 7.39162 15.7383 7.82514 16.1736C8.25865 16.6088 8.96152 16.6088 9.39503 16.1736L12.008 13.5502L14.605 16.1576C15.0385 16.5928 15.7414 16.5928 16.1749 16.1576C16.6084 15.7223 16.6084 15.0166 16.1749 14.5814L13.5779 11.974L16.1232 9.41862C16.5567 8.98337 16.5567 8.2777 16.1232 7.84246C15.6896 7.40721 14.9868 7.40721 14.5533 7.84246L12.008 10.3979L9.44676 7.82643Z" fill="#EE3F58"/>
      </svg>
    </div>
    <div class="title-cont">
      <div class="title">
        {{ index+1 }}. {{ question.title }}
      </div>
      <img :src="question.image" 
        v-if="question.image"
        class="title-img"
      />  
    </div>
  </div>
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
   .title{
    margin-top: 0;
    font-weight: 700;
    font-size: 18px;
    line-height: 24px;
    color: var(--color-Black_blue);
    
    
    &-img{
      width: 306px;
      margin-top: 15px;
    }
    &-icon{
      width: 25px;
    }
    &-block{
      display: flex;
    }
    &-cont{
      padding-left: 15px;
    }
  }
</style>

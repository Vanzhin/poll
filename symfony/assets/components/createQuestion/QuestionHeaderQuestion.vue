<template>
  <label  >Введите вопрос</label>
  <div class="img_block">
    <textarea rows="2"  name="question[title]" required
      v-model="questionTitle"
      class="textarea_input" 
    >
    </textarea>
    <i class="bi bi-eraser custom-close" title="Очистить поле вопроса"
      @click="questionTitle = ''"
      v-if="questionTitle !== ''"
    ></i>
  </div>
  <div class="mb-3 w-100">
    <div class="img_block">
      <img :src="questionImgUrl" width="200" 
        v-if="questionImgUrl !== ''"
      />  
      <i class="bi bi-x-lg custom-close" title="Удалить изображение"
        @click="questionImgDelete()"
        v-if="questionImgUrl !== ''"
      ></i>
    </div>
    <label class="label"
      v-if="questionImgUrl === ''"
    >Прикрепить изображение</label>
    <label class="label"
      v-else
    >Изменить изображение</label>
    <input  class="" type="file" accept="image/*"  
      @change="changeQuestionImg" 
      name="questionImage"
      :value="questionImgValue"
    >
  </div>
</template>
<script>
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  props: ['question', 'index' ],
  data() {
    return {
      questionTitle:"",
      questionImgFile:"",
      questionImgUrl:"",
      questionImgValue:"",
      operationEdit: this.$route.params.operation === "edit"
    }
  },
  computed:{
    ...mapGetters([
      "getTest",
      "getQuestion"
    ]),
  },
  methods: {
    changeQuestionImg(e){
      if (typeof e.target.files[0] === 'object'){
        this.questionImgFile = e.target.files[0]
        this.questionImgUrl = URL.createObjectURL(e.target.files[0])
        this.questionImgValue = e.target.value
      }
    },
    questionImgDelete(){
      this.questionImgFile = ''
      this.questionImgUrl = ''
      this.questionImgValue = ''
    }
  },
  created(){
    
    if (this.operationEdit){
      this.questionTitle = this.getQuestion.title
      this.questionImgUrl = this.getQuestion.image ? this.getQuestion.image :''
    }
  } 
}
</script>
<style lang="scss" scoped>

  .custom{
    &-close{
      cursor: pointer;
    }
  }
  .label{
    display:block;
    margin: 0 10px;
  }
  .textarea_input{
    max-width: 50%;
    padding: 0;
    padding-left: 10px;
    margin: 5px;
  }
  .img_block{
    display: flex;
    align-items: flex-start;
    & i {
      margin: 10px;
      transition: all 0.5s ease-out;
      &:hover{
        color: rgb(185, 48, 14);
        transform: scale(1.25);
      }
    }
  }
</style>

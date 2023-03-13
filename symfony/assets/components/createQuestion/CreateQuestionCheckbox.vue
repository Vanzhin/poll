<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <QuestionHeaderQuestion/>
      
      <input type="hidden" 
        id="answer_true"
        name="question[answer]"
        :value="answerSelect"
      >
      <hr>
      <i class="i">Введите варианты ответов.</i>
      <div class="block_number">
        <label for="number" class="label"> Количество ответов</label>
        <input id="number" type="number"
          v-model="numberAnswers"
          @change="changeNumberAnswers"
        >
        правильные ответы: {{ answerSelect }}
      </div>
      <div class="custom-control "
        v-for="(answer, ind ) in answers" 
        :key="answer"
      >
        <div class="custom-radio" >
          <input type="checkbox" 
            :value= "operationEdit ? answer.id : ind"
            v-model="answerSelect"
            class="custom-control-input"  
          >
          <div class="custom-radio img_block">
            <textarea rows="1" required
              :name="`variant[${answer.id}][title]`"
              v-model= "answer.title"
              class="textarea_input" 
            >
            </textarea> 
            <input type="hidden" 
              :name="`variant[${answer.id}][correct]`" 
              :value="answerSelect.includes( answer.id )"
            >
            <i class="bi bi-eraser custom-close" title="Очистить поле"
              @click="answer.title = ''"
              v-if="answer.title !== ''"
            ></i>
            <i class="bi bi-x-lg custom-close" title="Удалить ответ"
                @click="answerDelete(ind)"
                v-if="answers.length > 1"
            ></i>
          </div>
        </div>
        <div class="mb-3 w-100">
          <div class="img_block">
            <img :src="answer.image"  width="200"
              v-if="answer.image !== ''"
            /> 
            <i class="bi bi-x-lg custom-close" title="Удалить изображение"
              @click="answerImgDelete(ind)"
            v-if="answer.image !== ''"
          ></i>
          </div> 
          <label class="label"
            v-if="answer.image === ''"
          >Прикрепить изображение </label>
          <label class="label"
            v-else
          >Изменить изображение</label>
          <input  class="" type="file" accept="image/*"  
            @change="(e)=> changeAnswerImg(e, ind)"
            :name="(answer.value === '')&&(answer.image === '')? '' : `variantImage[${answer.id}]`"
            :value="answer.value"
          >
        </div>
      </div>
      <br>
    </div>       
  </div>
</template>
<script>
import  QuestionHeaderQuestion from './QuestionHeaderQuestion.vue';
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  props: ['question', 'index' ],
  components: {
    QuestionHeaderQuestion
  },
  data() {
    return {
      count: 0,
      answerSelect: [],
      answers: [{
        id:"a1",
        title:"",
        image:"",
        value:""
      }],
      numberAnswers: 1,
      uniqueNumber: 1,
      showPreviewQuestionImg: false,
      operation: this.$route.params.operation,
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
    changeNumberAnswers(){
      if (this.numberAnswers < 1) {
        this.numberAnswers = 1
        return
      }
      if (this.answers.length < this.numberAnswers) {
        ++this.uniqueNumber
        this.answers.push({id:'a' + (this.uniqueNumber), title:"", image:"", value:""})
      } else {
        const arr = this.answerSelect.filter(item =>{ 
         return item !== this.answers[this.answers.length - 1].id})
        this.answerSelect = arr
        this.answers.pop()
      }
    },
    changeAnswerImg(e, ind){
      if (typeof e.target.files[0] === 'object'){
        this.answers[ind].image = URL.createObjectURL(e.target.files[0])
        this.answers[ind].value = e.target.value
      }
    },
    answerDelete(ind){
      if (this.answers.length > 1){
        const arr = this.answerSelect.filter(item =>{ 
          return item !== this.answers[ind].id})
        this.answerSelect = arr
        this.answers.splice(ind, 1);
        this.numberAnswers = this.answers.length
      }
    },
    answerImgDelete(ind){
      this.answers[ind].image = ''
      this.answers[ind].value = ''
    },
  },
  created(){
    console.log(this.getQuestion)
    if (this.operationEdit) {
      this.answerSelect = this.getQuestion.answer
      this.uniqueNumber = this.numberAnswers = this.getQuestion.variant.length
      this.answers = this.getQuestion.variant.map(item => 
        {
          return {
            id: item.id,
            title: item.title,
            file: '',
            image: item.image ? item.image : '',
            value: ''
          }
        })
      console.log("this.answers -",this.answers )
    }
  } 
}
</script>
<style lang="scss" scoped>
  .block_number{
    display: flex;
  }
  .shadow{
    padding: 5px;
  }
  .custom{
    &-control {
      position: relative;
      display: flex;
      align-items:center;
      min-height: 1.5rem;
      padding-left: 1.5rem;
      flex-wrap:wrap;
      &-label {
        position: relative;
        margin-bottom: 0;
        margin-left: 10px;
      }
     
    };
    &-radio{
      display: flex;
      align-items:center;
      width: 100%;
    }
    &-close{
      cursor: pointer;
    }
}
  .f_sm {
      font-size: 0.9rem;
  }
  
  #number{
    width: 40px;
    margin-left: 20px;
    margin-right: 20px;
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

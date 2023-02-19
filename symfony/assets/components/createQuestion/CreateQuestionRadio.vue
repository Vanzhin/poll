<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <label  >Введите вопрос</label>
      <textarea rows="2"  name="question-title"
        v-model="questionTitle"
        class="textarea_input" 
      >
      </textarea>
      <input type="hidden" 
        id="trueanswer"
        name="answer_true" 
        :value="answerSelect"
      >
      <div class="mb-3 w-100">
        <img :src="questionImgUrl" width="200" 
          v-if="typeof questionImgFile === 'object'"
        />  
        <label class="label">Прикрепить изображение </label>
        
        <!-- <img src={`${avatarURL}${article.image}`} width="100%"/>} -->
        <input  class="" type="file" accept="image/*"  @change="changeQuestionImg" name="question_img">
        <i class="bi bi-x-lg custom-close" title="Удалить изображение"
              @click="questionImgDelete()"
            v-if="typeof questionImgFile === 'object'"
          ></i>
      </div>
      <hr>
      <i class="i">Введите варианты ответов.</i>
      <div class="block_number">
        <label for="number" class="label"> Количество ответов</label>
        <input id="number" type="number"
          v-model="numberAnswers"
          @change="changeNumberAnswers"
        >
        {{ answerSelect }}
      </div>
      <div class="custom-control "
        v-for="(answer, ind ) in answers" 
        :key="answer"
      >
        <div class="custom-radio" >
          <input type="radio" 
            :value= "ind "
            v-model="answerSelect"
            class="custom-control-input"  
          >
          <textarea rows="1" 
            :name="'answer[' + ind +']'"
            :id="'answer' +  (ind) " 
            v-model.lazy= "answers[ind].title"
            class="textarea_input" 
          >
          </textarea> 
        </div>
        <div class="mb-3 w-100">
          <img :src="answers[ind].url"  width="200"
            :name="'answer_img[' + ind +']'"
            v-if="typeof answers[ind].file === 'object'"
          />  
          <label class="label">Прикрепить изображение </label>
          
          <!-- <img src={`${avatarURL}${article.image}`} width="100%"/>} -->
          <input  class="" type="file" accept="image/*"  @change="(e)=> changeAnswerImg(e, ind)">
          <i class="bi bi-x-lg custom-close" title="Удалить изображение"
              @click="answerImgDelete(ind)"
            v-if="typeof answers[ind].file === 'object'"
          ></i>
        </div>
        
      </div>
      <br>
      
    </div>       
  </div>
</template>
<script>
// v-model="answer"
export default {
  props: ['qestion', 'index' ],
  data() {
    return {
      count: 0,
      answerSelect: null,
      questionTitle:"",
      questionImgFile:"",
      questionImgUrl:"",
      answers: [{
        title:"",
        file:"",
        url:""
      }],
      numberAnswers: 1,
      showPreviewQuestionImg: false
    }
  },
  computed:{
    
  },
  methods: {
   
    changeNumberAnswers(){
      if ( this.answers.length < this.numberAnswers) {
        this.answers.push({title:"",file:"",url:""})
      } else {
        this.answers.pop()
      }
    },
    changeQuestionImg(e){
      if (typeof e.target.files[0] === 'object'){
        this.questionImgFile = e.target.files[0]
        this.questionImgUrl = URL.createObjectURL(e.target.files[0])
      }
    },
    changeAnswerImg(e, ind){
      if (typeof e.target.files[0] === 'object'){
        this.answers[ind].file = e.target.files[0]
        this.answers[ind].url = URL.createObjectURL(e.target.files[0])
      }
    },
    answerImgDelete(ind){
      this.answers[ind].file = ''
      this.answers[ind].url = ''
    },
    questionImgDelete(){
      this.questionImgFile = ''
      this.questionImgUrl = ''
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
</style>

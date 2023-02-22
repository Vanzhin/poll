<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <label  >Введите вопрос</label>
      <div class="img_block">
        <textarea rows="2"  name="question[title]" required
          v-model="questionTitle"
          class="textarea_input" 
        >
        </textarea>
        <i class="bi bi-x-lg custom-close" title="Очистить поле вопроса."
            @click="questionTitle = ''"
            v-if="questionTitle !== ''"
        ></i>
      </div>
      <div class="mb-3 w-100">
        <div class="img_block">
          <img :src="questionImgUrl" width="200" 
            v-if="typeof questionImgFile === 'object'"
          />  
          <i class="bi bi-x-lg custom-close" title="Удалить изображение"
                @click="questionImgDelete"
              v-if="typeof questionImgFile === 'object'"
          ></i>
        </div>
        <label class="label">Прикрепить изображение </label>
        <!-- <img src={`${avatarURL}${article.image}`} width="100%"/>} -->
        <input  class="" type="file" accept="image/*"  
          @change="changeQuestionImg" 
          name="question[img]"
          :value="questionImgValue" 
        >
        
      </div>
      <hr>
      <i class="i">Введите правильный ответ.</i>
      
      <div class="custom-control ">
        <div class="custom-radio img_block">
          <input  
            name="question[answer][]"
            v-model="answers"
            class="custom-control-input" required  
          >
          <i class="bi bi-x-lg custom-close" title="Очистить поле ответа."
            @click="answers = ''"
            v-if="answers !== ''"
          ></i>
        </div>
      </div>
      <br>
      
    </div>       
  </div>
</template>
<script>

export default {
  props: ['qestion', 'index' ],
  data() {
    return {
      count: 0,
      questionTitle: "",
      questionImgFile: "",
      questionImgUrl: "",
      questionImgValue: "",
      answers: '',
    }
  },
  computed:{
  },
  methods: {
    changeQuestionImg(e){
      if (typeof e.target.files[0] === 'object'){
        this.questionImgFile = e.target.files[0]
        this.questionImgValue = e.target.value
        this.questionImgUrl = URL.createObjectURL(e.target.files[0])
      }
    },
    questionImgDelete(e){
      this.questionImgFile = ""
      this.questionImgUrl = ""
      this.questionImgValue = ""
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
    & input {
      margin-top: 10px;
      padding-left: 5px;
    }
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

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
        <i class="bi bi-x-lg custom-close" title="Очиститеть поле вопроса."
          @click="questionTitle = ''"
          v-if="questionTitle !== ''"
        ></i>
      </div>
      <input type="hidden" 
        id="trueanswer"
        name="question[answer]" 
        :value="answerSelect"
      >
      <div class="mb-3 w-100">
        <div class="img_block">
          <img :src="questionImgUrl" width="200" 
            v-if="typeof questionImgFile === 'object'"
          />  
          <i class="bi bi-x-lg custom-close" title="Удалить изображение"
                @click="questionImgDelete()"
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
      <i class="i">Введите варианты ответов в правильной последовательности.</i>
      <div class="block_number">
        <label for="number" class="label"> Количество ответов</label>
        <input id="number" type="number"
          v-model="numberAnswers"
          @change="changeNumberAnswers"
        >
        {{ answerSelect }}
      </div>
      <div  
        @drop="onDrop($event)"
        @dragover.prevent
        @dragenter.prevent
      >
        <div class="custom-control "
          v-for="(answer, ind ) in qestionVariantSort" 
          :key="answer"
          @dragstart="onDragStart($event, ind)"
          draggable="true"
          :dataname="answer.sort"
        >
          <div :class="{block_drop: drag}"           :dataname="answer.sort"></div>
            <div class="custom-radio" >
              <div class="custom-radio img_block">
                <textarea rows="1" required
                  :name="`question[variant][${ind}][title]`"
                  
                  v-model.lazy= "answer.title"
                  class="textarea_input" 
                >
                </textarea> 
                <i class="bi bi-x-lg custom-close" title="Удалить ответ"
                    @click="answerDelete(ind)"
                    v-if="answers.length > 1"
                ></i>
              </div>
            </div>
            <div class="mb-3 w-100">
              <div class="img_block">
                <img :src="answer.url"  width="200"
                  v-if="typeof answer.file === 'object'"
                /> 
                <i class="bi bi-x-lg custom-close" title="Удалить изображение"
                  @click="answerImgDelete(ind)"
                v-if="typeof answer.file === 'object'"
              ></i>
              </div> 
              <label class="label">Прикрепить изображение </label>
              
              <!-- <img src={`${avatarURL}${article.image}`} width="100%"/>} -->
              <input  class="" type="file" accept="image/*"  
                @change="(e)=> changeAnswerImg(e, ind)"
                :name="`variant[${ind}][img]`"
                :value="answer.value"
              >
            </div>
          
        </div>
        <br>
      </div>
    </div>       
  </div>
</template>
<script>

import { SlickList, SlickItem } from 'vue-slicksort';
export default {
  props: ['qestion', 'index' ],
  
  data() {
    return {
      count: 0,
      answerSelect: [1],
      questionTitle: "",
      questionImgFile: "",
      questionImgUrl: "",
      questionImgValue: "",
      answers: [{
        title: "",
        file: "",
        url: "",
        value: "",
        sort: 0,
      }],
      numberAnswers: 1,
      showPreviewQuestionImg: false,
      drag: false,
    }
  },
  computed:{
    qestionVariantSort(){
      this.answers.sort((a,b) => a.sort-b.sort)
      this.answers.forEach((item, index ) => {
        item.sort = index
      })
      return this.answers
    }
  },
  methods: {
    changeNumberAnswers(){
      if ( this.answers.length < this.numberAnswers) {
        this.answers.push({
          title: "",
          file: "",
          url: "",
          value: "",
          sort: this.answers.length 
        })
      } else {
        this.answers.pop()
      }
    },
    changeQuestionImg(e){
      if (typeof e.target.files[0] === 'object'){
        this.questionImgFile = e.target.files[0]
        this.questionImgUrl = URL.createObjectURL(e.target.files[0])
        this.questionImgValue = e.target.value
      }
    },
    changeAnswerImg(e, ind){
      if (typeof e.target.files[0] === 'object'){
        this.answers[ind].file = e.target.files[0]
        this.answers[ind].url = URL.createObjectURL(e.target.files[0])
        this.answers[ind].value = e.target.value
      }
    },
    answerDelete(ind){
      if (this.answers.length > 1){
        this.answers.splice(ind, 1);
        this.numberAnswers = this.answers.length
      }
    },
    answerImgDelete(ind){
      this.answers[ind].file = ''
      this.answers[ind].url = ''
      this.answers[ind].value = ''
    },
    questionImgDelete(){
      this.questionImgFile = ''
      this.questionImgUrl = ''
      this.questionImgValue = ''
    },
    onDragStart(e , item) {
      this.drag = true
      e.dataTransfer.dropEffect = 'copy'
      e.dataTransfer.effectAllowed = 'move'
      e.dataTransfer.setData('item', item.toString())
      e.dataTransfer.setData('pageY', e.target.offsetTop.toString())
      console.log('e - ', e)
      console.log('e.pageY - ', e.pageY, 'item - ', item)
      console.log('e.clientY - ', e.target.clientY, 'item - ', item)
      console.log(' высота -',  e.target.offsetTop)
    },
    onDrop(e){
      console.log('e onDrop- ', e)
      const item = parseInt(e.dataTransfer.getData('item'))
      const pageY = parseInt(e.dataTransfer.getData('pageY'))
      const yEl = e.target.offsetParent.offsetTop + e.target.offsetTop + e.target.clientHeight/2 + 170
      console.log('e.pageY - ', e.pageY, 'yEl - ', yEl, 'item - ', item)
      // console.log(e.toElement.attributes)
      console.log(e.toElement.attributes.dataname.value)
      if (e.pageY > yEl  ) {
        this.answers[item].sort = parseInt(e.toElement.attributes.dataname.value) + 0.5
      } else this.answers[item].sort = parseInt(e.toElement.attributes.dataname.value) - 0.5
      this.drag = false
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
      background-color: rgb(158, 155, 151);
      margin: 2px;
      border-radius: 10px;
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
  .block_drop{
    position: absolute;
    z-index: 10;
    height: 100%;
    width: 100%;
    
    left: 0;
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

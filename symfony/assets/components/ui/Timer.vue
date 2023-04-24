<template>
  <div class="timer-cont">
    <div class="timer-cont-fix">
      <div class="item-timer">
        <div class="timer"
          :class="classObject"
        >
        <div class="timer-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 12C20.5 16.6944 16.6944 20.5 12 20.5C7.30558 20.5 3.5 16.6944 3.5 12C3.5 7.30558 7.30558 3.5 12 3.5C16.6944 3.5 20.5 7.30558 20.5 12ZM22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM11.25 7.03058V11.6644C11.25 12.3996 11.5444 13.1042 12.0675 13.6208L13.9729 15.503L15.0271 14.4358L13.1216 12.5537C12.8838 12.3188 12.75 11.9985 12.75 11.6644V7.03058H11.25Z" fill="white"/>
          </svg>
        </div>
          {{ strTimer }}
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  props: ['time', 'timeEnd'],
  data() {
    return {
      timerId : '',
      setIntervalId : '',
      timeMinutStart: this.time * 60,
      timeMinut: this.time * 60 ,
      strTimer: ''
    }
  },
  computed: {
    classObject() {
      return {
        'timer-yellow': this.timeMinut < this.timeMinutStart * 0.5,
        'timer-red': this.timeMinut < this.timeMinutStart * 0.2,
        'timer-end': this.timeMinut <= 0,
      }
    }
  },
  methods: {
    setIntervalActive(){this.setIntervalId = setInterval(() => {
      
      let seconds = this.timeMinut%60 // Получаем секунды
      let minutes = this.timeMinut/60%60 // Получаем минуты
      
      this.strTimer = `${minutes < 10 ? 0 :''}${Math.trunc(minutes)}:${seconds<10? 0 :''}${seconds}`;
      // Условие если время закончилось то...
      if (this.timeMinut <= 0) {
        // Таймер удаляется
        clearInterval(this.setIntervalId);
        // Выводит сообщение что время закончилось
        this.$emit('timeEnd')
        // this.timeEnd = false
        //alert("Время закончилось");
      } 
      --this.timeMinut; // Уменьшаем таймер
    }, 1000)},
    setIntervalCler(){clearInterval(this.setIntervalId) }
  },
  mounted(){
    this.setIntervalActive()
  },
  unmounted(){
    this.setIntervalCler()
  }
}

</script>

<style lang="scss" scoped>
  
  .item-timer{
    
    @media (min-width: 1345px) {
   margin-right: -164px;
    }
  }
  .timer{
    background-color: var(--color-blue);
    width: 165px;
    height: 52px;
    text-align: center;
    font-family: 'Lato';
    font-style: normal;
    font-weight: 700;
    font-size: 24px;
    line-height: 24px;
    color: #FFFFFF;
    display: flex;
    align-items: center;
    padding-left: 16px;
    &-yellow{
      background-color: rgb(213 227 27);
    };
    &-red{
      background-color: var(--color-red);
    };
    &-end{
      background-color: rgb(226, 35, 10);
      color: rgb(245 230 226);
    };
    &-cont{
      display: flex;
      justify-content: end;
      width: 100%;
      &-fix{
        position: fixed;
        z-index: 20;
      }
    };
    &-icon{
      margin-right: 10px;
    }
  }
  
</style>

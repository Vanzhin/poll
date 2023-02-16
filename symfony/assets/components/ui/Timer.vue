<template>
  <div>
    <div class="cont">
      <div class="container">
        <div class="row justify-content-md-center">
          <div class="item-timer">
            <div class="timer"
              :class="classObject"
            >
              {{ strTimer }}
            </div>
          </div>
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
      strTimer: '20:00'
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
      console.log('timerMinut', this.timeMinut)
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
  .cont{
    display: flex;
    width: 84%;
    position: fixed;
    z-index: 20;
  }
  .item-timer{
    width: 100%;
    display: flex;
    justify-content: center;
  }
  .timer{
    background-color: rgb(152 153 146);
    width: 100px;
    text-align: center;
    &-yellow{
      color: rgb(185, 197, 20);
    };
    &-red{
      color: rgb(236, 37, 11);
    };
    &-end{
      background-color: rgb(226, 35, 10);
      color: rgb(245 230 226);
    }
  }
  
</style>

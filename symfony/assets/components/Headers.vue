<template>
  <div class="container"
    :class="classObject"
    :scroll="setIsActive()"
  >
  <div
  :class="classObject"> 
    <div class="wrapper">
      <div class="block">
        <div class="logo-block">
          <RouterLink :to="{ name: 'home'}" class="routerLink"> 
            <div class="logo">
              <div class="logo-title">лого</div>
            </div>
          </RouterLink>
        </div>
        <div class="search">
          <input type="text" value="" name="s" placeholder="Поиск..." class="search-input"/>
          <button class="search-button bi-search"></button>
         
          <!-- <div class="bi-search"></div> -->
        </div>
        <!-- <div class="links">
          <RouterLink :to="{ name: 'logout'}" class="routerLink"> 
            <h5>   Личный кабинет  </h5>
          </RouterLink>
        </div> -->
        <DropDownItems/>
      </div> 
    </div>
  </div>
  </div> 
</template>

<script>
import { RouterLink } from 'vue-router'
import DropDownItems from './ui/DropDownItems.vue';
export default {
  data() {
    return {
      
        isActive: false
    
      
    }
  },
  components:{
    DropDownItems
  },
  computed:{
    classObject() {
      console.log(this.isActive)
      return {
      fixeds: this.isActive
     
    }
  }
  },
  methods: {
    setIsActive(){
      let scrollY = window.pageYOffset;
      console.log(scrollY)
      // this.isActive = ind
    },
    
  }, 
  mounted() {
    // window.addEventListener('scroll', function (){
        
    //   let scrollY = window.pageYOffset;
    //   console.log(scrollY)
     
    //     if(scrollY > 68) {
    //       this.setIsActive(true);
    //     } else {
    //       this.setIsActive(false);
    //     }
    // })
  }
  
} 

</script>


<style lang="scss" scoped>
.container{
  position: relative;
 
}

.wrapper{
  background-color: #cad5d5;
  padding: 5px 0;
  
}
.block{
  display: flex;
  padding: 10px;
  margin: 0 10px;
  align-items: center;
  justify-content: space-between;
}
.logo-block{
  transform:  rotateY(0deg);
}
.logo{
  width: 50px;
  height: 50px;
  transform:  scaleY(0.75) rotate(45deg);
  border: 1px solid saddlebrown;
  background-color: rgb(148, 114, 62);
  text-align: center;
  &:hover{
    background-color: rgb(196, 163, 114);
  }

}
.logo-title{
  transform: rotate(20deg);
  text-align: center;
  transform:  scaleY(1) rotate(-45deg) translate(-7px,16%);
  font-size: 18px;
  color:#204242;
  padding-top: 3px;
  
}
.search{
  display: flex;
  height: 35px;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  max-width: 370px;
  width: 100%;
 
  border: 1px solid #664915;
  box-sizing: border-box;
  border-radius: 25px;
  padding-right: 4px;
  padding-left: 10px;
  margin: 0 20px;
}
.search-input{
  border: 0px solid #664915;
  background-color: #cad5d5;
  width: 80%;
}
.search-input:focus {
    outline-width: 0;
 
}
.search-button{
  width: 30px;
  height: 30px;
  border-radius: 25px;
  outline-width: 0;
  border: 0px;
 background-color: #664915;
}
.search-button:hover{
  background-color: #896624;
  &.bi-search::before{
      color: #cad5d5;
    }
}
.links{
  & h5 {
    color:#204242;
  }
  :hover{
  cursor: pointer;
  h5 {color: #82a5a5;}
}}
.fixeds {
    position: fixed;
    z-index: 100;
    top: 0;

    border-bottom: 1px solid rgb(218, 215, 215);
    width: 100%;
    }
</style>
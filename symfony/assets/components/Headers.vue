<template>
  <div class="container">
    <div class="wrapper">
      <div class="block">
        <div class="logo-block">
          <RouterLink :to="{ name: 'home'}"> 
            <div class="logo">
              <div class="logo-title">лого</div>
            </div>
          </RouterLink>
        </div>
        <div class="search">
          <input type="text" value="" name="s" placeholder="Поиск..." class="search-input"/>
          <input type="button" value="" class="search-button"/>
        </div>
        <div class="links">
          <h5>   Личный кабинет  </h5>
        </div>
        
      </div> 
    </div>
  </div> 
</template>

<script>
import { RouterLink } from 'vue-router'
export default {
  data() {
    return {
      count: 0
    }
  },
  
  
} 

</script>


<style scoped>
.container{
 
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
}
.logo-title{
  transform: rotate(20deg);
  text-align: center;
  transform:  scaleY(1) rotate(-45deg) translate(-7px,16%);
  font-size: 18px;
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
  background-color: #b99146;
}
.links:hover{
  cursor: pointer;
  color: #b99146;
}
</style>
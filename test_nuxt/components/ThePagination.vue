<template>
  <div>
    <div class="block-contener" 
      v-if="pagination.paginations.length > 1"
    >
      <div class="block-pagination">
        <div class="block-pagination-element"
          @click = "paginate('-1')"
          v-if="parseInt(pagination.paginations[0].label) > 1"
        >
          <span>
            &laquo; Назад
          </span>
        </div>
        <div 
          v-for="(item) in pagination.paginations"
          :key = "item.label" 
          class="block-pagination-element" 
          :class="{active:item.active}"
          @click ="paginate(parseInt(item.label))"
        >
          <span>
            {{item.label}}
          </span>
        </div>
        <div class="block-pagination-element"
          @click = "paginate('+1')"
          v-if="nextTotalPriznak"
        >
          <span>
            Вперед &raquo;
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
  import { usePaginationStore } from '../stores/PaginationStore'
  const pagination = usePaginationStore()
  const nextTotalPriznak = computed(()=>{
    return (pagination.totalPages > 10) ? 
      (parseInt( pagination.paginations[9].label) < parseInt(pagination.totalPages)) ? 
        true 
        : false
      : false
  })
  
 function paginate (pag) {
    console.log("pag -- ", pag)
    console.log("activePage --", pagination.activePage)
    switch (pag) {
      case '-1' : {
        if (pagination.activePage > 1) {
          if (pagination.activePage - 10 > 0){
            pag = pagination.activePage - 10
          } else pag = pagination.activePage - 1
        } else { return }
      break;
      }
      case '+1' : {
        if  (pagination.activePage < pagination.totalPages) {
          if (pagination.activePage + 10 < pagination.totalPages){
            pag = pagination.activePage + 10
          } else pag = pagination.activePage + 1
        } else { return }
      break;
      }
      default:{ }
    }
    navigateTo(`${pagination.url}${pag>1? '/page/'+ pag :''}`)
  }
</script>



<style lang="scss" scoped>
.block{
  &-contener{
    margin-top: 20px;
    padding-bottom: 30px;
  }
  &-pagination{
      display: flex;
      justify-content: center;
      align-items: center;
      &-element{
         padding: 5px 10px;
         
         margin: 0 5px;
         min-width: 10px;
         border-radius: 5px;
         color: var(--color-blue);
         &.active {
            background-color: var(--color-blue);
            color: var(--color-white)
         }
         &:hover{
            cursor: pointer;
            
            background-color: var(--color-hover-logo)
         }
      }
   }}
</style>
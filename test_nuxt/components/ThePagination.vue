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
  import { useCategoryStore } from '../stores/CategoryStore'
  const pagination = usePaginationStore()
  const categorys = useCategoryStore()
  
  console.log('store ',pagination)

  const paginations = pagination.paginations
  console.log('pagination ',paginations)

  const nextTotalPriznak = (pagination.totalPages > 10) ? 
          (parseInt( pagination.paginations[9].label) < pagination.totalPages) ? true : false
        : false
  console.log(nextTotalPriznak)

 function paginate (pag) {
    console.log("pag -- ", pag)
    
    switch (pag) {
        case '-1' : {
          if (pagination.activePage > 1) {
            pagination.activePageToChange( parseInt(pagination.paginations[0].label) - 1)
          } else { return }
        break;
        }
        case '+1' : {
         
          if  (pagination.activePage = parseInt(pagination.paginations[9].label) < pagination.totalPages) {
            pagination.activePageToChange (parseInt(pagination.paginations[9].label) + 1)
          } else { return }
        break;
        }
        default:{
          console.log("activePage -- ", pagination.activePage)
         
          preNavigation (pag)

          console.log("activePage -- ", pagination.activePage)
          // router.push({ path: `/page/${pag}` })

         
        }
      }
  }

async function preNavigation (pag) {
  pagination.activePageToChange(pag)
  await categorys.getApiCategorys({
    page: pag > 1 ? pag: '',
  })
  navigateTo(`${pagination.url}${pag>1? '/page/'+ pag :'/'}`)
}




// { "currentPage": 1, "totalPages": 3, "totalItem": 15, "totalItemsPerPage": 6, "limit": 6 }
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
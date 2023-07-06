<template>
  <div>
    пагинация
    <!-- {{ pagination }} -->


    <div class="block-contener" 
      v-if="pagination.length > 1"
    >
      <div class="block-pagination">
        <div class="block-pagination-element"
          @click = "paginate('-1')"
          v-if="parseInt(pagination[0].label) > 1"
        >
          <span>
            &laquo; Назад
          </span>
        </div>

        <div 
          v-for="(item) in pagination"
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
  const store = usePaginationStore()
  const router = useRouter()

  console.log('store ',store)

  const pagination = store.paginations
  console.log('pagination ',pagination)

  const nextTotalPriznak = (store.totalPages > 10) ? 
          (parseInt( store.pagination[9].label) < store.totalPages) ? true : false
        : false
  console.log(nextTotalPriznak)

  function paginate (pag) {
    console.log("pag -- ", pag)
    
    switch (pag) {
        case '-1' : {
          if (store.activePage > 1) {
            store.activePageToChange( parseInt(store.paginations[0].label) - 1)
          } else { return }
        break;
        }
        case '+1' : {
         
          if  (store.activePage = parseInt(store.paginations[9].label) < store.totalPages) {
            store.activePageToChange (parseInt(store.paginations[9].label) + 1)
          } else { return }
        break;
        }
        default:{
          console.log("activePage -- ", store.activePage)
          // activePage = useState('activePage', () => pag)
          store.activePageToChange(pag)
          console.log("activePage -- ", store.activePage)
          // router.push({ path: `/page/${pag}` })
           navigateTo(`${store.url}${pag>1? '/page/'+ pag :'/'}`)
        }
      }
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
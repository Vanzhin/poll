<template>
  <div>
    пагинация
    <!-- {{ pagination }} -->


    <div class="block-contener" 
      v-if="state.pagination.length > 1"
    >
      <div class="block-pagination">
        <div class="block-pagination-element"
          @click = "paginate('-1')"
          v-if="parseInt(state.pagination[0].label) > 1"
        >
          <span>
            &laquo; Назад
          </span>
        </div>

        <div 
          v-for="(item, key) in state.pagination"
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


const pagination = useState('paginatios').value
console.log('pagination ',pagination)

const state = {
  pagination:[],
  activePage: 1,
  totalItemsPage: 0,
  paginVisible: 10,
  paginLeft: 1,
  paginRight: 10,
  totalPages: 1,
  totalItem: null
}

let  pagin = []
      
      state.totalItemsPage = pagination.limit
      state.activePage = pagination.currentPage
      state.totalPages = pagination.totalPages
      state.totalItem = pagination.totalItem
      
      if (pagination.totalPages <= state.paginVisible){
        const len =  pagination.totalPages
        state.paginRight = pagination.totalPages
        pagin = Array.from({length: len}).map((inp, index) => { 
          return {label: index + 1 , active: index + 1  === state.activePage ? 
            true : false,
          }})
      } else {
        if (state.activePage === 1 ) {state.paginRight = state.paginVisible }
        if ( state.paginRight < state.activePage ) { 
          if ( (state.activePage + state.paginVisible - 1) < state.totalPages){
            state.paginRight = state.activePage + state.paginVisible - 1
          } else {
            state.paginRight = state.totalPages
          }
          state.paginLeft = state.paginRight - (state.paginVisible - 1)
        } else if (state.paginLeft > state.activePage ) {
          if ( (state.activePage - state.paginVisible + 1) > 0){
            state.paginLeft = state.activePage - (state.paginVisible - 1)
          } else {
            state.paginLeft = 1
          }
          state.paginRight = state.paginLeft + state.paginVisible - 1
        } 
        const len = state.paginVisible
        pagin = Array.from({length: len}).map((inp, index) => { 
          return {label: state.paginLeft + index , active: state.paginLeft + index === state.activePage ? 
            true : false,
        }})
      }
    
    
    state.pagination = pagin
    const nextTotalPriznak = (state.totalPages > 10) ? 
          (parseInt( state.pagination[9].label) < state.totalPages) ? true : false
        : false
  console.log(nextTotalPriznak)

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
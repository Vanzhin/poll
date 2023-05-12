<template>
  
</template>
 
<script>

import { RouterLink } from 'vue-router'
import { mapGetters, mapActions, mapMutations} from "vuex"

import crumbsTitle from '../utils/crumbs.js'
export default {
  components: {
   
  },
  data() {
    return {
      isLoader: true,
    }
  },
  computed:{
    ...mapGetters([
      "getTests",
      "getCategorysFooter",
      "getCrumbsLength",
      "getCategoryParent"
    ]),
    
  },
  
  methods: {
    ...mapActions([
      "getCategorysDB",
      "setTests", 
      "setPagination", 
      "setCategoryParent",
      "setCategorys",
      "getCategorysDBFooter",
      "setCrumbs",
      "setCrumbsHome",
      "setCrumbsLength"
    ]),
    async categoryUpdate({section}){
     
      this.setPagination(null)
      this.getCategorysDB({page:null, parentId: section.id})
      await this.setCrumbsLength({len:1})
      if (section.test.length > 0) {
        this.setTests(section.test)
        this.$router.push({name: 'area', params: {id: section.id } })
        this.setCrumbs({crumbs:[
          {
            name:'area',
            params: {id: section.id }, 
            title: `/${crumbsTitle(section)}`,
            iter: this.getCrumbsLength + 1 
          },
        ]
        })
        return
      } 
      this.setCategorys(section.children)
      this.$router.push({name: 'iter', params: { num: 1, id: section.id }})
      this.setCrumbs({crumbs:[{
        name:'iter',
        params: { num: 1, id: section.id }, 
        title: `/${crumbsTitle(section)}` ,
        iter: this.getCrumbsLength + 1 
        }]
      })
    },
   
  },

  mounted(){},

  async created(){
    this.categoryUpdate({section:this.getCategoryParent})
  }
  
} 
 
</script>
<style lang="scss" scoped>

</style>
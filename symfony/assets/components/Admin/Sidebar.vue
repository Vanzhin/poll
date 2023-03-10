<template>
  <ul class="sidenav app-sidenav" :class="{open: value}">
    <RouterLink
      tag="li"
      active-class="list-active"
      :to="link.url"
      :exact="link.exact"
      class="list-link"
      v-for="link in links"
      :key="link.url"
    >    
      <li class="list"> 
        <span v-html="link.icon"  class="list-icon" :title="link.title"></span>
        {{link.title}}
      </li>
    </RouterLink>
  </ul>
</template>

<script>
import { RouterLink, RouterView } from 'vue-router'
export default {
  props: ['value'],
  data: () => ({
    links: [
      {title: 'Главная', url: '/admins', exact: true,
        icon: `<svg width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-home align-text-bottom"
                                 aria-hidden="true">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>`
      },
      {title: 'Разделы', url: '/admins/chapters',
        icon:`<svg  width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-file align-text-bottom"
                                 aria-hidden="true">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>`
      },
      {title: 'Области аттестации', url: '/admins/sections',
        icon:`<i class="bi bi-box"></i>`},
      {title: 'Тесты', url: '/admins/tests',
        icon:`<i class="bi bi-archive"></i>`},
      {title: 'Вопросы', url: '/admins/questions',
        icon:`<i class="bi bi-question-square"></i>`},
      {title: 'Импорт из файла', url: '/admins/import',
        icon:`<i class="bi bi-cloud-arrow-down"></i>`},
      {title: 'Пользователи', url: '/admins/users',
        icon:` <i class="bi bi-people"></i>`},
       
    ]
  })
}
</script>

<style lang="scss" scoped>
.app-sidenav{
  padding-top:15px;
  position:fixed;
  top:80px;
  width:250px;
  transition:transform .3s;
  transition:transform .3s,-webkit-transform .3s;
  bottom:0!important;
  height:auto!important;
  z-index: 18;
}
.app-sidenav.open{
  -webkit-transform:translateX(0);
  transform:translateX(0)
}
.list{
  list-style-type:none;
  padding: 5px;
  @media (max-width: 768px) {
    width: 28px;
    overflow:hidden;
    height: 32px;
    }
  &-icon{
    margin-right: 15px;
  }
  &-link{
    color: rgb(94, 93, 92);
    text-decoration: none;
  }
  &-active{
    color: rgb(12, 137, 187);
  }
  &:hover{
    cursor: pointer;
    background-color: rgb(186, 217, 245);
    .list-icon{
      color: rgb(19, 19, 19);
    }
    .list-link{
      color: rgb(19, 19, 19);
    }
  }
}
</style>




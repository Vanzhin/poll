<template>
  <div class="fon">
  <ul class="sidenav app-sidenav" :class="{open: value}">
    <NuxtLink
      tag="li"
      active-class="list-active"
      :to="link.url"
      :exact="link.exact"
      class="list-link"
      v-for="link in links"
      :key="link.url"
    >    
      <li class="list"> 
       
        {{link.title}}
      </li>
    </NuxtLink>
  </ul>
</div>
</template>

<script>

export default {
  props: ['value'],
  data: () => ({
    links: [
      {title: 'Главная', url: '/admin', exact: true,
        icon: `<svg width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-home align-text-bottom"
                                 aria-hidden="true">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>`
      },
      {title: 'Разделы', url: '/admin/chapters',
        icon:`<svg  width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-file align-text-bottom"
                                 aria-hidden="true">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>`
      },
      {title: 'Тесты', url: '/admin/tests',
        icon:`<i class="bi bi-archive"></i>`},
      {title: 'Вопросы', url: '/admin/questions',
        icon:`<i class="bi bi-question-square"></i>`},
      {title: 'Импорт из файла', url: '/admin/import',
        icon:`<i class="bi bi-cloud-arrow-down"></i>`},
      {title: 'Пользователи', url: '/admin/users',
        icon:` <i class="bi bi-people"></i>`},
       
    ]
  })
}
</script>

<style lang="scss" scoped>
.app-sidenav{
  padding-top:147px;
  padding-right: 30px;
  position:fixed;
  top:89px;
  width:250px;
  transition:transform .3s;
  bottom:0!important;
  height:auto!important;
  z-index: 16;
  background-color: var(--color-fon);
  @media (max-width: 768px) {
    width: 60px;
    overflow:hidden;
  }
}
.app-sidenav.open{
  -webkit-transform:translateX(0);
  transform:translateX(0)
}
.list{
  list-style-type:none;
  padding: 5px;
  font-family: 'Lato';
  font-style: normal;
  font-weight: 700;
  font-size: 16px;
  line-height: 40px;
  border-bottom: 1px solid rgba(255, 255, 0, 0);
  @media (max-width: 768px) {
    
    }
  &-icon{
    margin-right: 15px;
  }
  &-link{
    color: var(--color-Black_blue);
    border-bottom: 1px solid rgba(255, 255, 0, 0);
    text-decoration: none;
  }
  &-active{
    color: var(--color-blue);
    border-bottom: 1px solid#269EB7 ;
  }
  &:hover{
    cursor: pointer;
    color: var(--color-blue);
    border-bottom: 1px solid#269EB7 ;
    .list-icon{
      color: rgb(19, 19, 19);
    }
    .list-link{
      color: rgb(19, 19, 19);
    }
  }
}
</style>
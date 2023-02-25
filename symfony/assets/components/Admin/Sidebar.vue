<template>
  <ul class="sidenav app-sidenav" :class="{open: value}">

    <RouterLink
      v-for="link in links"
      :key="link.url"
      tag="li"
      active-class="active"
      :to="link.url"
      :exact="link.exact"
    >
      <a href="#" class="waves-effect waves-orange pointer">{{link.title}}</a>
    </RouterLink>
  </ul>
</template>

<script>
import { RouterLink, RouterView } from 'vue-router'
export default {
  props: ['value'],
  data: () => ({
    links: [
      {title: 'Счет', url: '/', exact: true},
      {title: 'История', url: '/history'},
      {title: 'Планирование', url: '/planning'},
      {title: 'Новая запись', url: '/record'},
      {title: 'Категории', url: '/categories'}
    ]
  })
}
</script>




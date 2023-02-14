import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import TestsView from '../views/TestsView.vue'
import store from '../store/index';
const router = createRouter({
  history: createWebHistory(""),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/SectionsView.vue')
      // component: () => import('../views/TicketView.vue'),
      // component: () => import('../views/ResultView.vue'),
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/question/:id',
      name: 'question',
      component: () => import('../views/Question.vue')
    },
    {
      path: '/questions',
      name: 'questions',
      component: () => import('../views/QuestionsView.vue')
    },
    {
      path: '/area/:id',
      name: 'area',
      component: () => import('../views/TestsView.vue')
    },
    {
      path: '/test/:id',
      name: 'test',
      meta: {autch: false},
      component: () => import('../views/TicketsView.vue'),
      props: true,
    },
    {
      path: '/ticket/:id',
      name: 'ticket',
      component: () => import('../views/TicketView.vue'),
      props: true,
    },
    {
      path: '/logout',
      name: 'logout',
      component: () => import('../views/Logout.vue'),
      props: true,
    },
    {
      path: '/logoutlink',
      name: 'logoutlink',
      component: () => import('../views/LoginByLink.vue'),
      props: true,
    },
    {
      path: '/signup',
      name: 'signup',
      component: () => import('../views/Signup.vue'),
      props: true,
    },
    {
      path: '/chapter/:id',
      name: 'chapter',
      meta: {autch: false},
      component: () => import('../views/AreasView.vue')
    },
    {
      path: '/result',
      name: 'result',
      component: () => import('../views/ResultView.vue')
    },
    {
      path: '/result/autch',
      name: 'resultAutch',
      component: () => import('../views/ResultAutchView.vue')
    },
    {
      path: '/statistics',
      name: 'statistics',
      meta: {autch: true},
      component: () => import('../views/StatisticsAutchView.vue')
    },
    {
      path: '/redirection/:rtoken',
      name: 'redirection',
      
      component: () => import('../views/RedirectionView.vue')
    },
  ]
})
router.beforeEach((to, from, next) => {
  const requireAuth = to.meta.autch
  const userAutch = store.getters.getIsAutchUser
  // console.log('userAutch', userAutch)
  // console.log(store)
  // console.log(from.name)
  if ((to.name === 'logout' || to.name === 'logoutlink' || to.name ==='signup')
    &(from.name !== 'logout' & from.name !== 'logoutlink' & from.name !=='signup')
  
  ) {
    console.log("page true" )
    store.dispatch('setPage', from.path)
    
  } else{ console.log("page false" )}
  // store._actions.setPage(from.name)
  if (requireAuth && !userAutch) {
    next('/logout')
  } else {
    next()
  }
})

export default router

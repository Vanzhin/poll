import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import TestsView from '../views/TestsView.vue'
const router = createRouter({
  history: createWebHistory(""),
  routes: [
    {
      path: '/',
      name: 'home',
      // component: () => import('../views/SectionsView.vue')
      component: () => import('../views/TicketView.vue'),
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
      path: '/signup',
      name: 'signup',
      component: () => import('../views/Signup.vue'),
      props: true,
    },
    {
      path: '/chapter/:id',
      name: 'chapter',
      component: () => import('../views/AreasView.vue')
    },
    {
      path: '/result',
      name: 'result',
      component: () => import('../views/ResultView.vue')
    },
  ]
})

export default router
// {/* <RouterLink :to="{ name: 'question', params: { id: question.id } }" */}
// <RouterLink to="question"
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
      // meta: {loyout: 'page', autch: false},
      // component: () => import('../views/SectionsView.vue')
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminChapterView.vue')
      
      // component: () => import('../views/TicketView.vue'),
      // component: () => import('../views/ResultView.vue'),
    },
    {
      path: '/about',
      name: 'about',
      meta: {loyout: 'page', autch: false},
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/question/:id',
      name: 'question',
      meta: {loyout: 'page', autch: false},
      component: () => import('../views/Question.vue')
    },
    {
      path: '/questions',
      name: 'questions',
      meta: {loyout: 'page', autch: false},
      component: () => import('../views/QuestionsView.vue')
    },
    {
      path: '/area/:id',
      name: 'area',
      meta: {loyout: 'page', autch: false},
      component: () => import('../views/TestsView.vue')
    },
    {
      path: '/test/:id',
      name: 'test',
      meta: {loyout: 'page', autch: false},
      component: () => import('../views/TicketsView.vue'),
      props: true,
    },
    {
      path: '/ticket/:id',
      name: 'ticket',
      meta: {loyout: 'page', autch: false},
      component: () => import('../views/TicketView.vue'),
      props: true,
    },
    {
      path: '/logout',
      name: 'logout',
      meta: {loyout: 'empty', autch: false},
      component: () => import('../views/Logout.vue'),
      props: true,
    },
    {
      path: '/logoutlink',
      name: 'logoutlink',
      meta: {loyout: 'empty', autch: false},
      component: () => import('../views/LoginByLink.vue'),
      props: true,
    },
    {
      path: '/signup',
      name: 'signup',
      meta: {loyout: 'empty', autch: false},
      component: () => import('../views/Signup.vue'),
      props: true,
    },
    {
      path: '/iter/:num/group/:id',
      // path: '/?iter=1/group/:id',
      name: 'iter',
      meta: {loyout: 'page', autch: false},
      component: () => import('../views/AreasView.vue')
    },
    {
      path: '/result',
      name: 'result',
      meta: {loyout: 'page', autch: false},
      component: () => import('../views/ResultView.vue')
    },
    {
      path: '/result/autch',
      name: 'resultAutch',
      meta: {loyout: 'page', autch: false},
      component: () => import('../views/ResultAutchView.vue')
    },
    {
      path: '/statistics',
      name: 'statistics',
      meta: {loyout: 'page', autch: true},
      component: () => import('../views/StatisticsAutchView.vue')
    },
    {
      path: '/redirection/:rtoken',
      name: 'redirection',
      component: () => import('../views/RedirectionView.vue')
    },
    {
      path: '/create/question',
      name: 'createQuestion',
      meta: {loyout: 'page', autch: true},
      component: () => import('../views/admin/CreateQuestionView.vue')
    },
    {
      path: '/admins',
      name: 'admins',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminHomeView.vue')
    },
    {
      path: '/admins/сhapters',
      name: 'adminsChapters',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminChapterView.vue')
    },
    {
      path: '/admins/sections',
      name: 'adminsSections',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminSectionView.vue')
    },
    {
      path: '/admins/tests',
      name: 'adminsTests',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminTestView.vue')
    },
    {
      path: '/admins/questions',
      name: 'adminsQuestions',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/CreateQuestionView.vue')
    },
    {
      path: '/admins/import',
      name: 'adminsImport',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/ImportQuestionView.vue')
    },
    {
      path: '/admins/users',
      name: 'adminsUsers',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminUsersView.vue')
    },
    {
      path: '/admins/category/:id/:operation',
      name: 'adminsCategoryCreate',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/CreateCategoryView.vue')
    },
    {
      path: '/admins/iter/:num/group/:id',
      name: 'adminIter',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminIterView.vue')
    },
    {
      path: '/admins/test/:id/:operation',
      name: 'adminsTestCreate',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/CreateTestView.vue')
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

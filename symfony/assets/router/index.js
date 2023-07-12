import { createRouter, createWebHistory } from 'vue-router'
import store from '../store/index';
const router = createRouter({
  history: createWebHistory(""),
  routes: [
    {
      path: '/',
      name: 'home',
      meta: {loyout: 'page', autch: false },
      component: () => import('../views/SectionsView.vue')
      // meta: {loyout: 'admin', autch: true, admin: true},
      // component: () => import('../views/admin/AdminChapterView.vue')
      
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
      name: 'iter',
      meta: {loyout: 'page', autch: false},
      component: () => import('../views/AreasView.vue')
    },
    {
      path: '/sectionrout',
      name: 'sectionrout',
      meta: {loyout: 'page', autch: false},
      component: () => import('../views/SectionsRoutView.vue')
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
      component: () => import('../views/AutchStatisticsView.vue')
    },
    {
      path: '/user/profile',
      name: 'userAutchProfile',
      meta: {loyout: 'page', autch: true},
      component: () => import('../views/AutchProfileView.vue')
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
      path: '/search',
      name: 'search',
      meta: {loyout: 'page', autch: false},
      
      component: () => import('../views/TestsSearchView.vue')
    },
    {
      path: '/admin',
      name: 'admin',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminHomeView.vue')
    },
    {
      path: '/admin/chapters',
      name: 'adminChapters',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminChapterView.vue')
    },
    {
      path: '/admin/sections',
      name: 'adminSections',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminSectionView.vue')
    },
    {
      path: '/admin/tests',
      name: 'adminTestsList',
      meta: {loyout: 'admin', autch: true, admin: true, type: "test"},
      component: () => import('../views/admin/AdminTestsListView.vue')
    },
    {
      path: '/admin/category/:id/tests',
      name: 'adminTests',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminTestsView.vue')
    },
    {
      path: '/admin/questions',
      name: 'adminQuestions',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/CreateQuestionView.vue')
    },
    {
      path: '/admin/import',
      name: 'adminImport',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/ImportQuestionView.vue'),
      children: [{ 
        path: ':id', 
        name: 'adminImportId', 
        meta: {loyout: 'admin', autch: true, admin: true},
        component: () => import('../views/admin/ImportQuestionView.vue') 
      }],

    },
    {
      path: '/admin/users',
      name: 'adminUsers',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminUsersView.vue')
    },
    {
      path: '/admin/company',
      name: 'adminСompany',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminСompanyView.vue')
    },
    {
      path: '/admin/company/:id/:operation',
      name: 'adminCompanyCreate',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/CreateCompanyView.vue')
    },
    {
      path: '/admin/category/:id/:operation',
      name: 'adminCategoryCreate',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/CreateCategoryView.vue')
    },
    {
      path: '/admin/iter/:num/group/:id',
      name: 'adminIter',
      meta: {loyout: 'admin', autch: true, admin: true,  type: "category"},
      component: () => import('../views/admin/AdminIterView.vue')
    },
    {
      path: '/admin/test/:id',
      name: 'adminTest',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/AdminTestView.vue'),
      children: [
        {
          path: 'questions',
          name: 'adminTestQuestions',
          meta: {autch: true, admin: true},
          component: () => import('../views/admin/AdminTestQuestionsView.vue'),
        },
        {
          path: 'tickets',
          name: 'adminTestTickets',
          meta: {autch: true, admin: true},
          component: () => import('../views/admin/AdminTestTicketsView.vue'),
        },
        {
          path: 'sections',
          name: 'adminTestSections',
          meta: {autch: true, admin: true},
          component: () => import('../views/admin/AdminTestSectionsView.vue'),
        }
      ]
    },
    { 
      path: '/admin/test/:id/:operation',
      name: 'adminTestCreate',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/CreateTestView.vue')
    },
    {
      path: '/admin/test/:testId/question/:questionId/:operation',
      name: 'adminQuestionsCreate',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/CreateQuestionView.vue')
    },
    {
      path: '/admin/test/:testId/ticket/:ticketId/:operation',
      name: 'adminTicketCreate',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/CreateTicketView.vue')
    },
    {
      path: '/admin/test/:testId/section/:sectionId/:operation',
      name: 'adminSectionCreate',
      meta: {loyout: 'admin', autch: true, admin: true},
      component: () => import('../views/admin/CreateSectionView.vue')
    },
  ]
})
router.beforeEach((to, from, next) => {
  const requireAuth = to.meta.autch
  const requireRole = to.meta.admin
  const userAutch = store.getters.getIsAutchUser
  const role = store.getters.getUserRole
  
  if ((to.name === 'logout' || to.name === 'logoutlink' || to.name ==='signup')
    &(from.name !== 'logout' & from.name !== 'logoutlink' & from.name !=='signup')
  ) {
    store.dispatch('setPage', from.path)
  } else{ }
  
  if (requireAuth && !userAutch) {
    next('/logout')
  } else {
      if (requireRole ){
        if (role === "ROLE_SUPER_ADMIN") {
          next()
        } else {alert( "у вас нет прав доступа.")
        next('/')}
      } else {
        next()
      }
      
  }
})

export default router

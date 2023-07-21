import { 
  SET_IS_AUTCH_USER, 
  SET_AUTCH_USER_TOKEN, 
  SET_PAGE_NAME,
  SET_DELETE_USER_TOKEN,
  SET_LOGOUT_LINK_DATE,
  SET_MESSAGE_REQUEST,
  SET_AUTCH_USER_ROLE,
  SET_AUTCH_USER_PROFILE,
  SET_IS_AUTCH_USER_FIO,
  SET_USERS_LIST_COMPANY,
  SET_USER_COMPANY,
  SET_USER_LIST_EDUCATION_LEVEL
} from './mutation-types.js'

import axios from 'axios';

const state = () => ({
  token: localStorage.getItem('token') ?
    JSON.parse(localStorage.getItem('token')).token: "",
  refresh_token: localStorage.getItem('token') ?
    JSON.parse(localStorage.getItem('token')).refresh_token : "",
  isAutchUser: localStorage.getItem('token') ? true : false,
  page: localStorage.getItem('pageLink') ?
    localStorage.getItem('pageLink') : "",
  email: '',
  password: '',
  result: [],
  logoutLinkDate: {},
  message: null,
  profile: {},
  profileFIO: null,
  role: localStorage.getItem('token') ?
    JSON.parse(atob(JSON.parse(localStorage.getItem('token')).token.split('.')[1])).roles[0] : "",
  usersListCompany: null,
  userCompany: null,
  userListEducationLevel: null,
})

const actions = {
  // вход по ссылке
  async getLoginByLinkUser({ commit }, email) {
     try {
      const config = {
        method: 'get',
        url: `/api/login_link/${email}`,
        headers: { 
          'Accept': 'application/json',
          // 'Content-Type': 'application/json'
        },
      }
      await axios(config)
        .then((data)=>{
          
          commit("SET_LOGOUT_LINK_DATE",{
            message: data.data.message,
            url: data.data.link ? data.data.link.url : '',
            send: true 
          })
        })
    } catch (e) {
      
      commit("SET_LOGOUT_LINK_DATE",{data:e, send: false })
    }
  },
  //вход на сайт с помощью учетной записи
  async setLogInUser({ dispatch, commit }, user) {
    const data = JSON.stringify(user)
    try {
      const config = {
        method: 'post',
        url: '/api/login_check',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        data: data 
      }
      await axios(config)
        .then((data)=>{
          const base64Url = data.data.token.split('.')[1]
          // const base64 = decodeURIComponent(atob(base64Url).split('').map(function(c) {
          //   return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
          // }).join(''));
          const base64 = JSON.parse(atob(base64Url))
          
         
          commit("SET_AUTCH_USER_ROLE", base64.roles[0]);
          commit("SET_AUTCH_USER_TOKEN", data.data);
          commit("SET_IS_AUTCH_USER", true)
          // dispatch('setMessage',{
          //   message: "Вы успешно авторизовались",
          // });
        })
        return false
    } catch (e) {
      dispatch('setMessageError', e)
      return true
    }
  },
  //регистрация на сайте
  async setRegistrationUser({ dispatch, commit }, user) {
    const data = JSON.stringify(user)
    
    try {
      const config = {
        method: 'post',
        maxBodyLength: Infinity,
        url: '/api/register',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        data: data 
      }
      await axios(config)
        .then((data)=>{
          // console.log("getRegistrationUser - ", data.data )
          // dispatch('setMessage', data.data)
        })
        return false
    } catch (e) {
      dispatch('setMessageError', e)
      return true
    }
  },
  // выход с сайта
  async getLogOutUser({ commit, state }) {
    const data = JSON.stringify({"refresh_token":state.refresh_token} )
    
    try{
      const config = {
        method: 'post',
        url: '/api/token/invalidate',
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json'
        },
        data: data  
      }
      await axios(config)
        .then((data)=>{
          
          commit("SET_DELETE_USER_TOKEN", '');
          commit("SET_IS_AUTCH_USER", false)
        })
    } catch (e) {
      
      if (e.response.data.message === "No refresh_token found." ||
      e.response.data.message === "JWT Refresh Token Not Found"
      ) {
        commit("SET_DELETE_USER_TOKEN", '');
        commit("SET_IS_AUTCH_USER", false)
      }
    }
  },
  // повторное получение токена
  async getAuthRefresh({commit, state }, refresh_token) {
    let data = ''
    if (refresh_token) {
       data = JSON.stringify({"refresh_token": refresh_token})
    } else { data = JSON.stringify({"refresh_token": state.refresh_token})}
    try {
      const config = {
        method: 'post',
        url: '/api/token/refresh',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        data: data 
      }
      await axios(config)
        .then((data)=>{
          commit("SET_AUTCH_USER_TOKEN", data.data);
          commit("SET_IS_AUTCH_USER", true)
        })
    } catch (e) {
      console.log(e)
      if (e.response.data.message === "No refresh_token found." ||
      e.response.data.message === "JWT Refresh Token Not Found" ||
      e.response.data.message === "Invalid JWT Refresh Token"
      ) {
        commit("SET_DELETE_USER_TOKEN", '');
        commit("SET_IS_AUTCH_USER", false)
      }
    }
  },
  //получение данных пользователя из БД
  async getAutсhUserProfileDb({commit, dispatch ,state }, ) {
    let token = state.token
    try {
      const config = {
        method: 'post',
        url: '/api/auth/user',
        headers: { 
          'Accept': 'application/json',
          Authorization: `Bearer ${token}`
        },
      }
      await axios(config)
        .then((data)=>{
          commit("SET_AUTCH_USER_PROFILE", data.data);
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getAutсhUserProfileDb')
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  //получение списка пользователей для компании из БД
  async getUsersListCompanyDb({commit, dispatch ,state },{limit=10,page=1} ) {
    let token = state.token
    const adminCompany = await dispatch("getAdminCompanyAction")
    let data = JSON.stringify({
      "filter": {},
      "sort": {
        "firstName": "ASC"
      }
    });
    try {
      const config = {
        method: 'post',
        url: `/api/user/list?limit=${limit}`,
        headers: { 
          'Accept': 'application/json',
          Authorization: `Bearer ${token}`,
          'x-switch-user': `${adminCompany}`
        },
        data: data
      }
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log("SET_USERS_LIST_COMPANY", data)
          commit("SET_USERS_LIST_COMPANY", data.data.list)
          dispatch("setPagination", data.data.pagination)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getUsersListCompanyDb', {limit})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  //удаление пользователя компании из БД
  async deleteUserDb({commit, dispatch ,state },{id,} ) {
    let token = state.token
    const adminCompany = await dispatch("getAdminCompanyAction")
    
    try {
      const config = {
        method: 'delete',
        url: `/api/user/${id}?_switch_user=${adminCompany}`,
        headers: { 
          'Accept': 'application/json',
          Authorization: `Bearer ${token}`,
          // 'x-switch-user': `${adminCompany}`
        },
        
      }
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log("SET_USERS_LIST_COMPANY", data)
         
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getUsersListCompanyDb', {limit})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  
  //создание и редактирование пользователя для компании в БД
  async createUserInCompanyDb({commit, dispatch ,state },{user, userId=null} ) {
    let token = state.token
    const adminCompany = await dispatch("getAdminCompanyAction")
    let data = JSON.stringify(user)
    try {
      const config = {
        method: `${userId ? 'put' : 'post'}`,
        url: `/api/user${userId ? '/'+userId: ''}`,
        headers: { 
          'Accept': 'application/json',
          Authorization: `Bearer ${token}`,
          'x-switch-user': `${adminCompany}`
        },
        data : data
      }
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log("создание ред пользов", data)
          dispatch('setMessage', data)
        })
    } catch (e) {
      console.log("SET_USERS_LIST_COMPANY ошибка", e)
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('createUserInCompanyDb', {user, userId})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  //запрос из бд списка образований
  async getUserListEducationLevelDb({commit, dispatch ,state },{} ) {
     let token = state.token
    // const adminCompany = await dispatch("getAdminCompanyAction")
    try {
      const config = {
        method: `get`,
        url: `/api/profile/info`,
        headers: { 
          'Accept': 'application/json',
           Authorization: `Bearer ${token}`,
          // 'x-switch-user': `${adminCompany}`
        },
        
      }
      console.log(config)
      await axios(config)
        .then(({data})=>{

          console.log("SET_USER_LIST_EDUCATION_LEVEL", data.data.educationLevel)
          commit("SET_USER_LIST_EDUCATION_LEVEL", data.data.educationLevel)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getUserListEducationLevelDb', {})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  setPage ({ commit }, page) {
    commit("SET_PAGE_NAME", page);
  },
  setIsAutchUser ({ commit }, pr) {
    commit("SET_IS_AUTCH_USER", pr)
  },
  getAutchUserTokenAction({state}) {
    return state.token
  },
  setAutchUserProfileFIO({ commit }, fio){
    commit("SET_IS_AUTCH_USER_FIO", fio)
  }
  
};

const getters = {
  getIsAutchUser(state) {
    return state.isAutchUser
  },
  getAutchUserToken(state) {
    return state.token
  },
  getAutchUserRefreshToken(state) {
    return state.refresh_token
  },
  getPageName(state) {
    return state.page
  },
  getLogoutLinkDate(state) {
    return state.logoutLinkDate
  },
  getMessageLogin(state) {
    return state.message
  },
  getUserRole(state) {
    return state.role
  },
  getUserAdmin(state) {
    return state.role === "ROLE_SUPER_ADMIN"
  },
  getAutchUserProfile(state) {
    return state.profile
  },
  getAutchUserProfileFIO(state) {
    return state.profileFIO
  },
  getUsersListCompany(state) {
    return state.usersListCompany
  },
  getTotalUsersCompany(state) {
    return state.usersListCompany ? state.usersListCompany.length :''
  },
  getUserCompanyEdit(state) {
    return state.userCompany ? state.userCompany : ''
  },
  getUserListEducationLevel(state) {
    return state.userListEducationLevel
  },
}

const mutations = {
  [SET_IS_AUTCH_USER] (state, pr ) {
    state.isAutchUser = pr
  },

  [SET_AUTCH_USER_TOKEN] (state, data  ) {
    state.token = data.token
    state.refresh_token = data.refresh_token
    const parsed = JSON.stringify({
      token:data.token,
      refresh_token: data.refresh_token
    });
    localStorage.setItem('token', parsed);
  },

  [SET_DELETE_USER_TOKEN] (state  ) {
    state.token = ''
    state.refresh_token = ''
    localStorage.removeItem('token');
  },

  [SET_PAGE_NAME] (state, page) {
    state.page = page
    localStorage.setItem('pageLink', page);
  },
  [SET_LOGOUT_LINK_DATE] (state, result) {
    state.logoutLinkDate = result
  },
  [SET_MESSAGE_REQUEST] (state, message) {
    state.message = message
  },
  [SET_AUTCH_USER_ROLE] (state, role) {
    console.log("role -", role)
    state.role = role
  },
  [SET_AUTCH_USER_PROFILE](state, profile) {
    state.profile = profile
  },
  [SET_IS_AUTCH_USER_FIO](state, profile) {
    state.profileFIO = profile
  },
  [SET_USERS_LIST_COMPANY](state, usersList){
    state.usersListCompany = usersList
  },
  [SET_USER_COMPANY](state, {user}){
    state.userCompany = user
  },
  [SET_USER_LIST_EDUCATION_LEVEL](state, list){
    state.userListEducationLevel = list
  },
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}
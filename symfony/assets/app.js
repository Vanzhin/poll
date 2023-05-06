import './styles/app.css';
require('bootstrap')
import './bootstrap';
import './app.scss';
import Appt from './Appt.vue'
import { createApp } from 'vue'
import router from './router'
import store from './store/index';
import { initYandexMetrika } from 'yandex-metrika-vue3';

const app = createApp(Appt)
app.use(router)
app.use(store)
// app.use(initYandexMetrika, {
//   id: 93328607,
//   env: process.env.NODE_ENV,
//   // other options
// });
app.mount('#app')
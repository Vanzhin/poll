import './styles/app.css';
require('bootstrap')
import './bootstrap';
import './app.scss';
import Appt from './Appt.vue'
import { createApp } from 'vue'
import router from './router'
import store from './store/index';
import VueMeta from 'vue-3-meta'

const app = createApp(Appt)
// app.use(VueMeta)
app.use(router)
app.use(store)
app.mount('#app')
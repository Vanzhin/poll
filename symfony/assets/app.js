import './styles/app.css';
require('bootstrap')
import './bootstrap';

import Appt from './Appt.vue'
import { createApp } from 'vue'
import router from './router'
import store from './store/index';


const app = createApp(Appt)
app.use(router)
app.use(store)
app.mount('#app')
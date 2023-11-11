import {createApp} from 'vue'
import router from './router'
import App from '@/Main/App.vue'

import './bootstrap'


createApp(App).use(router).mount("#app")

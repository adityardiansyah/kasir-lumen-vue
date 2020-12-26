import { createApp } from 'vue'
import { router } from './router'
import App from './App.vue'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'font-awesome/css/font-awesome.min.css'
import 'bootstrap/dist/js/bootstrap.min'

const app = createApp(App)
app.use(router)
app.mount('#app')
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'index',
        component: () => import("../views/Index.vue"),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        name: 'login',
        component: () => import("../views/Login.vue")
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem('token') == null) {
            next({
                path: '/login',
                params: { nextUrl: to.fullPath }
            })
        } else {
            let user = JSON.parse(localStorage.getItem('user'))
            if(to.matched.some(record => record.meta.is_admin)) {
                if(user.is_admin == 1){
                    next()
                }
                else{
                    next({ name: 'index'})
                }
            }else {
                next()
            }
        }
    } else if(to.matched.some(record => record.meta.guest)) {
        if(localStorage.getItem('token') == null){
            next()
        }
        else{
            next({ name: 'index'})
        }
    }else {
        next()
    }
})

export {router}
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'index',
        component: () => import("../views/Index.vue"),
        meta: {
            requiresAuth: true,
            checkStore: true
        }
    },
    {
        path: '/cashier',
        name: 'cashier',
        component: () => import("../views/Cashier.vue"),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/add-product',
        name: 'add-product',
        component: () => import("../views/Index.vue"),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/create-store',
        name: 'create-store',
        component: () => import("../views/store/Add.vue"),
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
    let user = JSON.parse(localStorage.getItem('user'))
    if(to.matched.some(record => record.meta.checkStore)) {
        console.log(user.haveStore);
        if(user.haveStore == 'FALSE'){
            next({ name: 'create-store'})
        }
        else{
            next()
        }
    }

    if(to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem('user') == null) {
            next({
                path: '/login',
                params: { nextUrl: to.fullPath }
            })
        } else {
            next()
        }
        
    } else {
        next()
    }
})

export {router}
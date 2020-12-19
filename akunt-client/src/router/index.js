import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'transaction.index',
        component: () => import("../views/transaction/Index.vue")
    },{
        path: '/create',
        name: 'transaction.create',
        component: () => import("../views/transaction/Create.vue")
    },{
        path: '/edit/:id',
        name: 'transaction.edit',
        component: () => import("../views/transaction/Update.vue")
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export {router}
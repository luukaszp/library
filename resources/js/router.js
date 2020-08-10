import Vue from 'vue'
import Router from 'vue-router'
import Login from './components/Login.vue'
import RegisterReader from './components/AdminPanel/RegisterReader.vue'
import RegisterWorker from './components/AdminPanel/RegisterWorker.vue'
import AdminPanel from './components/AdminPanel/AdminPanel.vue'
import Readers from './components/AdminPanel/Readers.vue'
import Workers from './components/AdminPanel/Workers.vue'
import Roles from './components/AdminPanel/Roles.vue'
import Books from './components/AdminPanel/Books.vue'
import Authors from './components/AdminPanel/Authors.vue'
import Categories from './components/AdminPanel/Categories.vue'
import Publishers from './components/AdminPanel/Publishers.vue'
import Titles from './components/AdminPanel/Titles.vue'
import BorrowsReturns from './components/AdminPanel/BorrowsReturns.vue'
import Borrows from './components/AdminPanel/Borrows.vue'
import DelaysPenalties from './components/AdminPanel/DelaysPenalties.vue'
import Suggestions from './components/AdminPanel/Suggestions.vue'
import Opinions from './components/AdminPanel/Opinions.vue'
import UserForms from './components/AdminPanel/UserForms.vue'

Vue.use(Router);

const routes = [
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register-reader',
            name: 'register-reader',
            component: RegisterReader
        },
        {
            path: '/register-worker',
            name: 'register-worker',
            component: RegisterWorker
        },
        {
            path: '/admin-panel',
            name: 'admin-panel',
            component: AdminPanel,
            children: [
                {
                    path: '/admin-panel/readers',
                    name: 'readers',
                    component: Readers
                },
                {
                    path: '/admin-panel/workers',
                    name: 'workers',
                    component: Workers
                },
                {
                    path: '/admin-panel/roles',
                    name: 'roles',
                    component: Roles
                },
                {
                    path: '/admin-panel/books',
                    name: 'books',
                    component: Books
                },
                {
                    path: '/admin-panel/authors',
                    name: 'authors',
                    component: Authors
                },
                {
                    path: '/admin-panel/categories',
                    name: 'categories',
                    component: Categories
                },
                {
                    path: '/admin-panel/publishers',
                    name: 'publishers',
                    component: Publishers
                },
                {
                    path: '/admin-panel/titles',
                    name: 'titles',
                    component: Titles
                },
                {
                    path: '/admin-panel/borrows-returns',
                    name: 'borrows-returns',
                    component: BorrowsReturns
                },
                {
                    path: '/admin-panel/borrows',
                    name: 'borrows',
                    component: Borrows
                },
                {
                    path: '/admin-panel/delays-penalties',
                    name: 'delays-penalties',
                    component: DelaysPenalties
                },
                {
                    path: '/admin-panel/suggestions',
                    name: 'suggestions',
                    component: Suggestions
                },
                {
                    path: '/admin-panel/opinions',
                    name: 'opinions',
                    component: Opinions
                },
                {
                    path: '/admin-panel/userforms',
                    name: 'userforms',
                    component: UserForms
                }
            ]
        },
    ];


const router = new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
});

export default router;
import Vue from 'vue'
import Router from 'vue-router'
import Login from './components/Login.vue'
import Register from './components/Register.vue'
import AdminPanel from './components/AdminPanel.vue'
import Readers from './components/Readers.vue'
import Workers from './components/Workers.vue'
import Books from './components/Books.vue'
import Authors from './components/Authors.vue'
import Categories from './components/Categories.vue'
import Publishers from './components/Publishers.vue'
import Titles from './components/Titles.vue'
import BorrowsReturns from './components/BorrowsReturns.vue'
import Borrows from './components/Borrows.vue'
import DelaysPenalties from './components/DelaysPenalties.vue'
import Suggestions from './components/Suggestions.vue'
import Opinions from './components/Opinions.vue'
import UserForms from './components/UserForms.vue'

Vue.use(Router);

const routes = [
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register
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
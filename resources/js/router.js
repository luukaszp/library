/* eslint-disable */
import Vue from 'vue';
import VueRouter from 'vue-router';
import Login from './components/Login.vue';
import RegisterReader from './components/AdminPanel/RegisterReader.vue';
import RegisterWorker from './components/AdminPanel/RegisterWorker.vue';
import AddBook from './components/AdminPanel/AddBook.vue';
import AddBorrow from './components/AdminPanel/AddBorrow.vue';
import AdminPanel from './components/AdminPanel/AdminPanel.vue';
import Readers from './components/AdminPanel/Readers.vue';
import Workers from './components/AdminPanel/Workers.vue';
import Roles from './components/AdminPanel/Roles.vue';
import Books from './components/AdminPanel/Books.vue';
import Authors from './components/AdminPanel/Authors.vue';
import Categories from './components/AdminPanel/Categories.vue';
import Publishers from './components/AdminPanel/Publishers.vue';
import BorrowsReturns from './components/AdminPanel/BorrowsReturns.vue';
import Borrows from './components/AdminPanel/Borrows.vue';
import DelaysPenalties from './components/AdminPanel/DelaysPenalties.vue';
import Suggestions from './components/AdminPanel/Suggestions.vue';
import Opinions from './components/AdminPanel/Opinions.vue';
import UserForms from './components/AdminPanel/UserForms.vue';
import store from './store/store.js';

Vue.use(VueRouter);

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/register-reader',
    name: 'register-reader',
    component: RegisterReader,
    meta: {
      requiresAuth: true,
      is_worker: true
    }
  },
  {
    path: '/register-worker',
    name: 'register-worker',
    component: RegisterWorker,
    meta: {
      requiresAuth: true,
      is_admin: true
    }
  },
  {
    path: '/add-book',
    name: 'add-book',
    component: AddBook,
    meta: {
      requiresAuth: true,
      is_worker: true
    }
  },
  {
    path: '/add-borrow',
    name: 'add-borrow',
    component: AddBorrow,
    meta: {
      requiresAuth: true,
      is_worker: true
    }
  },
  {
    path: '/admin-panel',
    name: 'admin-panel',
    component: AdminPanel,
    meta: {
      requiresAuth: true,
      is_worker: true
    },
    children: [
      {
        path: '/admin-panel/readers',
        name: 'readers',
        component: Readers
      },
      {
        path: '/admin-panel/workers',
        name: 'workers',
        component: Workers,
        beforeEnter: (to, from, next) => {
          if (store.getters.loggedUser.is_admin === true) {
            next();
          } else {
            alert('Unauthorized');
          }
        }
      },
      {
        path: '/admin-panel/roles',
        name: 'roles',
        component: Roles,
        beforeEnter: (to, from, next) => {
          if (store.getters.loggedUser.is_admin === true) {
            next();
          } else {
            alert('Unauthorized');
          }
        }
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
  }
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!store.getters.isLoggedIn) {
      next('/login');
    } else if (to.matched.some((record) => record.meta.is_worker)) {
      if (store.getters.loggedUser.is_worker === true) {
        next();
      } else {
        alert('Unauthorized');
      }
    } else if (to.matched.some((record) => record.meta.is_admin)) {
      if (store.getters.loggedUser.is_admin === true) {
        next();
      } else {
        alert('Unauthorized');
      }
    }
  } else {
    next();
  }
});

export default router;
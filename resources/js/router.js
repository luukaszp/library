/* eslint-disable */
import Vue from 'vue';
import VueRouter from 'vue-router';
import Login from './components/Login.vue';
import Main from './components/Main.vue';
import RegisterReader from './components/AdminPanel/RegisterReader.vue';
import RegisterWorker from './components/AdminPanel/RegisterWorker.vue';
import AddBook from './components/AdminPanel/AddBook.vue';
import AddBorrow from './components/AdminPanel/AddBorrow.vue';
import AdminPanel from './components/AdminPanel/AdminPanel.vue';
import Readers from './components/AdminPanel/Readers.vue';
import Workers from './components/AdminPanel/Workers.vue';
import Roles from './components/AdminPanel/Roles.vue';
import Books from './components/AdminPanel/Books.vue';
import BooksISBN from './components/AdminPanel/BooksISBN.vue';
import Authors from './components/AdminPanel/Authors.vue';
import Categories from './components/AdminPanel/Categories.vue';
import Publishers from './components/AdminPanel/Publishers.vue';
import BorrowsReturns from './components/AdminPanel/BorrowsReturns.vue';
import Borrows from './components/AdminPanel/Borrows.vue';
import DelaysPenalties from './components/AdminPanel/DelaysPenalties.vue';
import Events from './components/AdminPanel/Events.vue';
import Types from './components/AdminPanel/Types.vue';
import Suggestions from './components/AdminPanel/Suggestions.vue';
import Opinions from './components/AdminPanel/Opinions.vue';
import UserForms from './components/AdminPanel/UserForms.vue';
import Calendar from './components/Calendar.vue';
import Search from './components/Search.vue';
import NewPositions from './components/NewPositions.vue';
import Catalog from './components/Catalog.vue';
import AuthorView from './components/AuthorView.vue';
import BookView from './components/BookView.vue';
import Profile from './components/Profile/Profile.vue';
import UserBorrows from './components/Profile/UserBorrows.vue';
import UserDelays from './components/Profile/UserDelays.vue';
import Statistics from './components/Profile/Statistics.vue';
import BorrowsAmount from './components/Profile/Charts/BorrowsAmount.vue';
import FavoriteAuthor from './components/Profile/Charts/FavoriteAuthor.vue';
import FavoriteCategory from './components/Profile/Charts/FavoriteCategory.vue';
import RatingsAmount from './components/Profile/Charts/RatingsAmount.vue';
import Follows from './components/Profile/Follows.vue';
import UserSuggestions from './components/Profile/UserSuggestions.vue';
import Questionnaires from './components/Profile/Questionnaires.vue';
import FirstLogin from './components/FirstLogin.vue';
import PasswordReset from './components/PasswordReset.vue';
import NewPassword from './components/NewPassword.vue';
import Ranking from './components/Ranking.vue';
import NotFound from './components/NotFound.vue';
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
    path: '/',
    name: 'main',
    component: Main
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
            Vue.swal('Nieautoryzowany', 'Odmowa dostępu!', 'error');
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
            Vue.swal('Nieautoryzowany', 'Odmowa dostępu!', 'error');
          }
        }
      },
      {
        path: '/admin-panel/books',
        name: 'books',
        component: Books
      },
      {
        path: '/admin-panel/books-isbn',
        name: 'books-isbn',
        component: BooksISBN
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
        path: '/admin-panel/calendar/events',
        name: 'events',
        component: Events
      },
      {
        path: '/admin-panel/calendar/types',
        name: 'types',
        component: Types
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
  {
    path: '/calendar',
    name: 'calendar',
    component: Calendar
  },
  {
    path: '/search',
    name: 'search',
    component: Search
  },
  {
    path: '/new',
    name: 'new',
    component: NewPositions
  },
  {
    path: '/catalog',
    name: 'catalog',
    component: Catalog
  },
  {
    path: '/book/:book_id',
    name: 'bookview',
    component: BookView,
    props: true
  },
  {
    path: '/author/:author_id',
    name: 'authorview',
    component: AuthorView,
    props: true
  },
  {
    path: '/profile/:user_id',
    name: 'profile',
    component: Profile,
    props: true,
    meta: {
        requiresAuth: true
    },
    children: [
      {
        path: '/profile/:user_id/borrows',
        name: 'userborrows',
        component: UserBorrows,
        props: true
      },
      {
        path: '/profile/:user_id/delays',
        name: 'userdelays',
        component: UserDelays,
        props: true
      },
      {
        path: '/profile/:user_id/statistics',
        name: 'statistics',
        component: Statistics,
        props: true,
        children: [
          {
            path: '/profile/:user_id/statistics/borrows',
            name: 'borrowsamount',
            component: BorrowsAmount,
            props: true
          },
          {
            path: '/profile/:user_id/statistics/authors',
            name: 'favoriteauthors',
            component: FavoriteAuthor,
            props: true
          },
          {
            path: '/profile/:user_id/statistics/categories',
            name: 'favoritecategories',
            component: FavoriteCategory,
            props: true
          },
          {
            path: '/profile/:user_id/statistics/ratings',
            name: 'ratingsamount',
            component: RatingsAmount,
            props: true
          }
        ]
      },
      {
        path: '/profile/:user_id/follows',
        name: 'follows',
        component: Follows,
        props: true
      },
      {
        path: '/profile/:user_id/suggestions',
        name: 'usersuggestions',
        component: UserSuggestions,
        props: true
      },
      {
        path: '/profile/:user_id/questionnaires',
        name: 'questionnaires',
        component: Questionnaires,
        props: true
      }
    ]
  },
  {
    path: '/first-login',
    name: 'first-login',
    component: FirstLogin
  },
  {
    path: '/reset-password',
    name: 'reset-password',
    component: PasswordReset
  },
  {
    path: '/new-password/:user_id',
    name: 'new-password',
    component: NewPassword
  },
  {
    path: '/ranking',
    name: 'ranking',
    component: Ranking
  },
  {
    path: '*',
    name: 'notfound',
    component: NotFound
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
        Vue.swal('Nieautoryzowany', 'Odmowa dostępu!', 'error');
      }
    } else if (to.matched.some((record) => record.meta.is_admin)) {
      if (store.getters.loggedUser.is_admin === true) {
        next();
      } else {
        Vue.swal('Nieautoryzowany', 'Odmowa dostępu!', 'error');
      }
    }
    else {
        next();
    }
  } else {
    next();
  }
});

export default router;
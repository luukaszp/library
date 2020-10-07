/* eslint-disable */
import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

import auth from './modules/auth';
import readers from './modules/readers';
import workers from './modules/workers';
import roles from './modules/roles';
import publishers from './modules/publishers';
import categories from './modules/categories';
import borrows from './modules/borrows';
import books from './modules/books';
import authors from './modules/authors';
import types from './modules/types';
import events from './modules/events';
import ratings from './modules/ratings';
import opinions from './modules/opinions';
import averages from './modules/averages';
import favourites from './modules/favourites';
import suggestions from './modules/suggestions';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    auth,
    readers,
    workers,
    roles,
    publishers,
    categories,
    borrows,
    books,
    authors,
    types,
    events,
    ratings,
    opinions,
    averages,
    favourites,
    suggestions
  },
  plugins: [createPersistedState()]
});
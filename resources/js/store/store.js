import Vue from 'vue'
import Vuex from 'vuex'

import auth from "./modules/auth"
import readers from "./modules/readers"
import workers from "./modules/workers"

Vue.use(Vuex)

export default new Vuex.Store({
    modules : {
        auth, readers, workers
    }
})
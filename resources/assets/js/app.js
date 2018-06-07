
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
import Vuex from 'vuex'
import VueRouter from 'vue-router'

Vue.use(Vuex)
Vue.use(VueRouter)

import user from './vuex/user'

const store = new Vuex.Store({
    modules: {
        user
    }
})

const router = new VueRouter({
    routes: [
        {
            path: '/',
            component: require('./components/auth/login.vue'),
            beforeEnter: (to, from, next) => {
                if(store.getters.getUser === null) {
                    next()
                }
                next('/home')
            }
        },
        {
            path: '/home',
            component: require('./components/home.vue'),
            beforeEnter: (to, from, next) => {
                if(store.getters.getUser === null) {
                    next('/')
                }
                next()
            }
        }
    ]
})

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

import clusters from 'clusters'

Vue.prototype.$k = clusters

Vue.prototype.$axios = window.axios

import VueChartkick from 'vue-chartkick'
Vue.use(VueChartkick)

new Vue({
    store,
    router
}).$mount('#app');

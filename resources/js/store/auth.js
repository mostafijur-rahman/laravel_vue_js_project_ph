import axios from 'axios';
import router from '@/router';

// import helper
// import { getCurrentLang } from '../helpers/common.js';
// const selectedLang = getCurrentLang();

export default {
    namespaced: true,
    state: {
        authenticated: false,
        user: {},
        loading: false,
    },
    getters: {
        authenticated(state) {
            return state.authenticated
        },
        user(state) {
            return state.user
        },
        // currentLang(state) {
        //     return state.currentLang;
        // },
    },
    mutations: {
        SET_AUTHENTICATED(state, value) {
            state.authenticated = value
        },
        SET_USER(state, value) {
            state.user = value
        },
    },
    actions: {
        login({ commit }) {
            return axios.get('/api/auth-user').then(({ data }) => {
                commit('SET_USER', data);
                commit('SET_AUTHENTICATED', true);

                // this.loadCompanySetting();
                router.push({ name: 'dashboard' });

            }).catch(() => {
                commit('SET_USER', {});
                commit('SET_AUTHENTICATED', false);
            })
        },
        logout({ commit }) {
            commit('SET_USER', {});
            commit('SET_AUTHENTICATED', false);
            router.push({ name: 'login' });
        },
        setAuthUserData({ commit }) {

            return axios.get('/api/auth-user').then(({ data }) => {

                commit('SET_USER', data);
                commit('SET_AUTHENTICATED', true);

            }).catch(() => {

                commit('SET_USER', {});
                commit('SET_AUTHENTICATED', false);
                router.push({ name: 'login' });

            })
        }
    }
}
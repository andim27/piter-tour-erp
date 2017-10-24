import axios from 'axios'
import Cookies from 'js-cookie'
import * as types from '../mutation-types'

// state
export const state = {
  user: null,
  token: Cookies.get('token')
}

// mutations
export const mutations = {
  [types.SAVE_TOKEN] (state, { token, remember }) {
    state.token = token
    Cookies.set('token', token, { expires: remember ? 730 : null })
  },

  [types.FETCH_USER_SUCCESS] (state, { user }) {
    state.user = user
  },
   // -- my --
  [types.FETCH_USERS] (state, { users }) {
    state.users = users
  },
  [types.FETCH_DEPARTMENTS] (state, { items }) {
    state.departments = items
  },
  [types.FETCH_DEPARTMENTS_FAILURE] (state, { items }) {
    state.departments = null
  },
  [types.FETCH_USER_FAILURE] (state) {
    state.token = null
    Cookies.remove('token')
  },

  [types.LOGOUT] (state) {
    state.user = null
    state.token = null

    Cookies.remove('token')
  },

  [types.UPDATE_USER] (state, { user }) {
    state.user = user
  }
}

// actions
export const actions = {
  saveToken ({ commit, dispatch }, payload) {
    commit(types.SAVE_TOKEN, payload)
  },

  async fetchUser ({ commit }) {
    try {
      const { data } = await axios.get('/api/user')

      commit(types.FETCH_USER_SUCCESS, { user: data })
    } catch (e) {
      commit(types.FETCH_USER_FAILURE)
    }
  },

  async fetchUsers ({ commit }) {
    try {
      const { data } = await axios.get('/api/users')

      commit(types.FETCH_USERS, { users: data.data })
    } catch (e) {
      commit(types.FETCH_USERS_FAILURE)
    }
  },
  async fetchDepartments ({ commit }) {
    try {
      const { data } = await axios.post('/api/departments')

      commit(types.FETCH_DEPARTMENTS, { departments: data.data })
    } catch (e) {
      commit(types.FETCH_DEPARTMENTS_FAILURE)
    }
  },
  updateUser ({ commit }, payload) {
    commit(types.UPDATE_USER, payload)
  },

  async logout ({ commit }) {
    try {
      await axios.post('/api/logout')
    } catch (e) { }

    commit(types.LOGOUT)
  }
}

// getters
export const getters = {
  authUser: state => state.user,
  authToken: state => state.token,
  authCheck: state => state.user !== null,
  users: state => state.users
}

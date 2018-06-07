const state = {
  user: {
  	access_token: localStorage.getItem('access_token'),
  	expires_in: localStorage.getItem('expires_in'),
    user: JSON.parse(localStorage.getItem('user'))
  }
}

const mutations = {
  SET_USER (state, user) {
  	state.user = user
  }
}

const actions = {

  saveUser({commit}, data) {
    localStorage.setItem('access_token', data.token.access_token)
    localStorage.setItem('expires_in', data.token.expires_in + Date.now())
    localStorage.setItem('user', JSON.stringify(data.user))
    commit('SET_USER', {
    	access_token: localStorage.getItem('access_token'),
  		expires_in: localStorage.getItem('expires_in'),
      user: data.user
    })
  },

  removeUser({commit}) {
    localStorage.removeItem('access_token')
    localStorage.removeItem('expires_in')
    commit('SET_USER', null)
  }

}

const getters = {
  getUser: state => {
  	if(
  	  (!localStorage.getItem('access_token') || !localStorage.getItem('expires_in'))
      || Date.now() > parseInt(localStorage.getItem('expires_in'))
    ) {
        return null
  	}
  	return state.user
  }
}

export default {
  state,
  mutations,
  actions,
  getters
}

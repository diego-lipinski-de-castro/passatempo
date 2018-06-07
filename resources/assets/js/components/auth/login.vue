<template>
  <div class="container mt-5">
	  <div class="row justify-content-center">
	    <div class="col-md-8">
	      <div class="card">
	        <div class="card-header"> Entrar </div>

	        <div class="card-body">

	            <div class="form-group row">
	              <label for="email" class="col-sm-4 col-form-label text-md-right"> E-mail </label>

	              <div class="col-md-6">
	                <input id="email" v-model='email' type="email" class="form-control" name="email" required>
	              </div>
	            </div>

	            <div class="form-group row">
	              <label for="password" class="col-md-4 col-form-label text-md-right"> Senha </label>

	              <div class="col-md-6">
	                <input id="password" @keyup.enter="login" v-model='password' type="password" class="form-control" name="password" required>
	              </div>
	            </div>

	            <div class="form-group row mb-0">
	              <div class="col-md-8 offset-md-4">
	                <button :disabled="loading" @click='login' type="submit" class="btn btn-primary">
	                  {{loginBtnText}}
	                </button>
	              </div>
	            </div>

	        </div>
	      </div>
	    </div>
	  </div>
	</div>
</template>

<script>

    import { mapActions } from 'vuex'

	export default {
		name: 'login',
		data: () => ({
			email: 'kuphal.sydni@example.net',
			password: '',
            loading: false
		}),
        computed: {
            loginBtnText() {
                return (this.loading) ? 'Carregando...' : 'Entrar'
            }
        },
		methods: {
      ...mapActions([
          'saveUser'
      ]),
			login() {

        const data = {
            client_id: 2,
            client_secret: 'iwmE1CsNzwQujX837ITukz9nNb2p16uFSzuWH1jr',
            grant_type: 'password',
            username: this.email,
            password: this.password
        }

        this.loading = true

        this.$axios.post('/oauth/token', data)
            .then(token => {
                this.$axios.get('/api/user', {
                  headers: {
                    'Authorization': `Bearer ${token.data.access_token}`
                  }
                }).then(user => {
                  this.loading = false
                  this.saveUser({ token: token.data, user: user.data })
                  this.$router.push('/home')
                })
                .catch(error => {
                  console.error(error)
                  this.loading = false
                })
            })
            .catch(error => {
                console.error(error)
                this.loading = false
            })

			}
		}
	}

</script>

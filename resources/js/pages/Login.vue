<template>
	<div class="hold-transition new-login-page">
		<div class="">
			<div class="text-center" style="color: #fff">
				<h1 style="text-shadow: 2px 2px #94c74b "><b>{{ $t('paribahan_hishab') }}</b></h1>
			</div>
			<div class="card">
				<div class="card-body">
					<form action="javascript:void(0)">
						<div class="col-12" v-if="Object.keys(validationErrors).length > 0">
							<div class="alert alert-danger">
								<ul class="mb-0">
									<li v-for="(value, key) in validationErrors" :key="key">{{ value[0] }}</li>
								</ul>
							</div>
						</div>
						<div class="input-group mb-3">
							<input type="text" class="form-control" v-model="form.phone" :placeholder="$t('write_phone_number_here')">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-phone"></span>
								</div>
							</div>
						</div>
						<div class="input-group mb-3">
							<input type="password" class="form-control" v-model="form.password" :placeholder="$t('write_password_here')">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-lock"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-8">
								<div class="icheck-primary">
									<input type="checkbox" id="remember" class="mr-1">
									<label for="remember">{{ $t('remember_me') }}</label>
								</div>
							</div>
							<div class="col-4">
								<button type="submit" :disabled="loading" @click="submitForm" class="btn btn-success btn-block">
									<i v-if="loading" class="fas fa-circle-notch fa-spin"></i>
									{{ $t('sign_in') }}
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="text-center" style="margin-top: 1rem; font-size: 14px; color: #fff;">
				<b>
					<p>
						{{ $t('powered_by') }} - <a style="color: #fff;" href="https://paribahanhishab.com" target="_blank"> {{ $t('rowshan_ara_technologies') }} </a> - {{ $t('version') }} - ({{ $t('1') }}{{ $t('5') }} . {{ $t('0') }})<br>
						{{ $t('image_credit') }} - <a style="color: #fff;" href="https://www.facebook.com/groups/1690276221251524" target="_blank">BD TRUCK LOVERS</a><br>
						<router-link style="color: #fff;" to="#" @click="cacheClear">
							{{ $t('cache_clear') }}
						</router-link>
					</p>
				</b>
			</div>
		</div>
	</div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
	data() {
		return {
		form: {
			phone: '',
			password: ''
		},
		loading: false,
		validationErrors: {}
		}
	},

	// Methods are functions that mutate state and trigger updates.
	// They can be bound as event listeners in templates.
	methods: {
		...mapActions({
			signIn: 'auth/login'
		}),
		async submitForm(){

			this.loading = true;
			await axios.get('/sanctum/csrf-cookie');
			await axios.post('/login', this.form)

				.then(({data})=>{

					this.signIn();
					this.$router.go(0);

				}).catch(({response})=>{

					if(response.status===422){
						this.validationErrors = response.data.errors
					}else{
						this.validationErrors = {}
						alert(response.data.message)
					}
				}).finally(()=>{
					this.loading = false
				})
		},
		cacheClear(){
            axios
                .get('/api/cacheOptimize')
				.then((response) => {
                    this.$router.go(0);
				}).catch((error) => {
					this.toast.error(error);
				});
        },
	},
}
</script>

<style>
	.new-login-page {
		align-items: center;
		display: -ms-flexbox;
		display: flex;
		height: 100vh;
		-ms-flex-pack: center;
		justify-content: center;
	}
</style>
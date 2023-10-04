<template>
	<div class="content-wrapper">
		<section class="content-header"></section>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-xs-12">
						<div class="card">
							<div class="card-header">
                                <h3 class="card-title">{{ $t('company_setting') }}</h3>
                            </div>
							<div class="card-body">
                                <div class="form-group">
                                    <label for="">{{ $t('your_company_name') }}</label>
                                    <input type="text" v-model="form.name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $t('company_slogan') }}</label>
                                    <input type="text" v-model="form.slogan" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $t('company_address') }}</label>
                                    <input type="text" v-model="form.address" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $t('phone_numbers') }}</label>
                                    <input type="text" v-model="form.phone" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $t('email_addresses') }}</label>
                                    <input type="text" v-model="form.email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $t('website') }}</label>
                                    <input type="text" v-model="form.website" class="form-control">
                                </div>
                            </div>
                        </div>
					</div>
                    <div class="col-md-6 col-xs-12">
                        <div class="card">
							<div class="card-header">
                                <h3 class="card-title">{{ $t('system_setting') }}</h3>
                            </div>
							<div class="card-body">
                                <div class="form-group">
                                    <label for="">{{ $t('default_oil_rate') }}</label>
                                    <input type="number" min="0" class="form-control" v-model="form.oil_rate">
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $t('payment_feature') }}</label>
                                    <select class="form-control" v-model="form.payment_feature">
                                        <option value="disable">{{ $t('inactive') }}</option>
                                        <option value="enable">{{ $t('active') }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $t('loan_feature') }}</label>
                                    <select class="form-control" v-model="form.loan_feature">
                                        <option value="disable">{{ $t('inactive') }}</option>
                                        <option value="enable">{{ $t('active') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card">
							<div class="card-header">
                                <h3 class="card-title">{{ $t('how_many_days_before_you_want_to_receive_notification') }}</h3>
                            </div>
							<div class="card-body">
                                <div class="form-group">
                                    <label for="">{{ $t('for_document') }}</label>
                                    <input type="number" class="form-control" v-model="form.notify_days_for_document" >
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $t('for_tyre') }}</label>
                                    <input type="number" class="form-control" v-model="form.notify_days_for_tyre" >
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $t('for_mobil') }}</label>
                                    <input type="number" class="form-control" v-model="form.notify_days_for_mobil" >
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" :disabled="loading" @click="submitForm" class="btn btn-success">
									<i v-if="!loading" class="fas fa-save"></i>
									<i v-if="loading" class="fas fa-circle-notch fa-spin"></i> {{ $t('update') }}
								</button>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
import { mapActions } from 'vuex';
import { useToast } from "vue-toastification";

export default {
    data(){
        return {
            form: {
                // company setting
                name : '',
                slogan : '',
                address : '',
                phone : '',
                email : '',
                website : '',

                // oil rate
                oil_rate : '',
                payment_feature : 'disable',
                loan_feature : 'disable',
                
                // for documents
                notify_days_for_document : '',
                notify_days_for_tyre : '',
                notify_days_for_mobil : '',

            },
            loading: false,
            toast: useToast()
        }
    },
    created (){
        this.getCompany();
    },
    methods: {
        ...mapActions({
            setAuthUserData:"auth/setAuthUserData"
        }),
		submitForm(){
			this.loading = true;
            axios.post('/api/settings/save-company', this.form)
                .then((response) =>{
                    this.toast.success(this.$t(response.data.message));
                    this.setAuthUserData();
                }).catch((error)=>{
                    this.toast.warning(error.message);
                }).finally(()=>{
                    this.loading = false
                })
		},
        getCompany(){
            axios.get('/api/settings/get-company')
                .then((response) =>{
                    this.form = { ...this.form, ...response.data.company };
                }).catch((error)=>{
                    this.toast.warning(error.message);
                })
        }
	},
}
</script>
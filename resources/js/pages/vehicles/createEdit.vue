<template>
	<div class="content-wrapper">
		<section class="content-header"></section>
		<section class="content">
			<div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <form @submit.prevent="createEditData()">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title"> {{ $t('vehicle_info') }} <span v-if="pageType == 'edit'">({{ $t('edit_page') }})</span></h3>
                                    <router-link to="/vehicles" class="btn btn-sm btn-secondary float-right"><i class="fa fa-arrow-left"></i> {{ $t('vehicle_list') }}</router-link>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('number_plate') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                                <input type="text" class="form-control" v-model="form.number_plate" placeholder="DHK-MA-11-2228" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('ownership') }}</label>
                                                <select v-model="form.ownership" class="form-control">
                                                    <option value="own">{{ $t('own') }}</option>
                                                    <option value="rental">{{ $t('rental') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('vehilce_no') }} </label>
                                                <input type="text" class="form-control" v-model="form.serial" :placeholder="$t('vehicle_serial_number_of_your_company')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('registration_number') }}</label>
                                                <input type="text" class="form-control" v-model="form.registration_number" :placeholder="$t('registration_number')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('engine_number') }}</label>
                                                <input type="text" class="form-control" v-model="form.engine_number" :placeholder="$t('engine_number')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('chassis_number') }}</label>
                                                <input type="text" class="form-control" v-model="form.chassis_number" :placeholder="$t('chassis_number')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('model') }}</label>
                                                <input type="text" class="form-control" v-model="form.model" :placeholder="$t('model')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('manufacturer') }}</label>
                                                <input type="text" class="form-control" v-model="form.manufacturer" :placeholder="$t('manufacturer')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('body_type') }}</label>
                                                <select v-model="form.body_type" class="form-control ">
                                                    <option value="">{{ $t('please_select') }}</option>
                                                    <option value="rental">{{ $t('local') }}</option>
                                                    <option value="own">{{ $t('imported') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('engine_down') }}</label>
                                                <select v-model="form.engine_down" class="form-control ">
                                                    <option value="">{{ $t('please_select') }}</option>
                                                    <option value="rental">{{ $t('half') }}</option>
                                                    <option value="own">{{ $t('full') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('fuel_tank_capacity') }}</label>
                                                <input type="text" class="form-control" v-model="form.fuel_tank_capacity" :placeholder="$t('fuel_tank_capacity')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('gps_id') }}</label>
                                                <input type="text" class="form-control" v-model="form.gps_id" :placeholder="$t('gps_id')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('driver') }}</label>
                                                <select v-model="form.driver_id" class="form-control">
                                                    <option value="">{{ $t('please_select') }}</option>
                                                    <option v-if="(drivers.length > 0)" v-for="driver in drivers" :value="driver.id">{{ $t(driver.name) }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('helper') }}</label>
                                                <select v-model="form.helper_id" class="form-control">
                                                    <option value="">{{ $t('please_select') }}</option>
                                                    <option v-if="(helpers.length > 0)" v-for="helper in helpers" :value="helper.id">{{ $t(helper.name) }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('note') }}</label>
                                                <textarea rows="1" class="form-control" v-model="form.note" :placeholder="$t('note')"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title"> {{ $t('other_info') }} <span v-if="pageType == 'edit'">({{ $t('edit_page') }})</span></h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('registration_year') }}</label>
                                                <input type="date" class="form-control" v-model="form.registration_year">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('tax_token_expire_date') }}</label>
                                                <input type="date" class="form-control" v-model="form.tax_token_expire_date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('fitness_expire_date') }}</label>
                                                <input type="date" class="form-control" v-model="form.fitness_expire_date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('insurance_expire_date') }}</label>
                                                <input type="date" class="form-control" v-model="form.insurance_expire_date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('last_tyre_change_date') }}</label>
                                                <input type="date" class="form-control" v-model="form.last_tyre_change_date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('last_mobil_change_date') }}</label>
                                                <input type="date" class="form-control" v-model="form.last_mobil_change_date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('last_servicing_date') }}</label>
                                                <input type="date" class="form-control" v-model="form.last_servicing_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" :disabled="submitLoading" class="btn btn-success">
                                        <i v-bind:class="submitBtnIcon"></i> {{ $t(submitBtnName) }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</section>
	</div>
</template>

<script>
import { useToast } from "vue-toastification";

export default {

	data() {
		return {
			toast: useToast(),
            submitLoading: false,
            pageType: 'create',
            submitBtnName: 'post',

			// form
			form: {
				id: '',
                
				ownership: 'own',
				serial: '',
				number_plate: '',
				registration_number: '',
				engine_number: '',
				chassis_number: '',
				model: '',
				manufacturer: '',

				body_type: '',
				engine_down: '',
				fuel_tank_capacity: '',
				gps_id: '',
				driver_id: '',
				helper_id: '',
                note: '',
                
				registration_year: '',
				tax_token_expire_date: '',
				fitness_expire_date: '',
				insurance_expire_date: '',
				last_tyre_change_date: '',
				last_mobil_change_date: '',
				last_servicing_date: '',

			},
            drivers: {},
            helpers: {},
		}
	},
	created() {
        this.getDrivers();
        this.getHelpers();

        if(this.$route.params && this.$route.params.id){
            this.getEditData(this.$route.params.id);
            this.submitBtnName = 'update_post';
        }

	},
	methods: {
		createEditData() {
			// loading
			this.submitLoading = true;
			// insertion
            let test;

            if(this.pageType == 'create'){

                test = axios.post('/api/vehicles', this.form)
                
            } else {

                test = axios.put('/api/vehicles/'+ this.form.id, this.form)

            }

            test.then((response) => {
                this.toast.success(this.$t(response.data.message));
            }).catch((error) => {
                this.toast.warning(error.message);
            }).finally(() => {
                this.submitLoading = false
            })
		},
        resetForm(){
            // reset form
            this.form.ownership = 'own'; 
            this.form.serial = '';
            this.form.number_plate = '';
            this.form.registration_number = '';
            this.form.engine_number = '';
            this.form.chassis_number = '';
            this.form.model = '';
            this.form.manufacturer = '';
            
            this.form.body_type = '';
            this.form.engine_down = '';
            this.form.fuel_tank_capacity = '';
            this.form.gps_id = '';
            this.form.driver_id = '';
            this.form.helper_id = '';
            this.form.note = '';

            this.form.registration_year = '';
            this.form.tax_token_expire_date = '';
            this.form.fitness_expire_date = '';
            this.form.insurance_expire_date = '';
            this.form.last_tyre_change_date = '';
            this.form.last_mobil_change_date = '';
            this.form.last_servicing_date = '';
        },
        getDrivers() {
            let params = { designation_id: 1 };
            axios.get('/api/common/staffs', { params })
				.then((response) => {
					this.drivers = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
        getHelpers() {
            let params = { designation_id: 2 };
            axios.get('/api/common/staffs', { params })
				.then((response) => {
					this.helpers = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
        getEditData(id) {
			axios.get('/api/vehicles/' + id)
				.then((response) => {
                    this.form = { ...this.form, ...response.data.data };
                    this.pageType = 'edit';

				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
	},
    computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
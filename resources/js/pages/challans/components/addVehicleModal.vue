<template>
    <div>
        <div class="modal fade" id="showVehicleModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form @submit.prevent="addVehicle()">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('vehicle_information') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('vehicle_select') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                        <select v-model="form.vehicle_id" class="form-control" @change="getStaffBasedOnVehicle" required>
                                            <option value="">{{ $t('please_select') }}</option>
                                            <option v-if="(vehicles.length > 0)" v-for="vehicle in vehicles" :value="vehicle.id">{{ vehicle.number_plate }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('driver_select') }}</label>
                                        <select v-model="form.driver_id" class="form-control">
                                            <option value="">{{ $t('please_select') }}</option>
                                            <option v-if="(drivers.length > 0)" v-for="driver in drivers" :value="driver.id">{{ driver.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('helper_select') }}</label>
                                        <select v-model="form.helper_id" class="form-control">
                                            <option value="">{{ $t('please_select') }}</option>
                                            <option v-if="(helpers.length > 0)" v-for="helper in helpers" :value="helper.id">{{ helper.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" ref="closeVehicleModal" data-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
                            <button type="submit" :disabled="submitLoading" class="btn btn-success">
                                <i v-bind:class="submitBtnIcon"></i> {{ $t(submitBtnName) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'addVehicleModal',
    props: ['challan_id'],
	data() {
		return {
            submitLoading: false,
            submitBtnName: 'post',

			// form
			form: {
				id: '',
				challan_id: this.challan_id,
				vehicle_id: '',
				driver_id: '',
				helper_id: '',
			},

			// for vehicle
			vehicles: {},
            drivers: {},
            helpers: {},
		}
	},
	created() {
        this.getVehicles();
        this.getDrivers();
        this.getHelpers();
	},
	methods: {
        getVehicles() {
			axios.get('/api/common/vehicles')
				.then((response) => {
					this.vehicles = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
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
        getStaffBasedOnVehicle() {
            if(this.form.vehicle_id){
                let form = { 'vehicle_id' : this.form.vehicle_id };
                    axios.post('/api/common/get-staff-based-on-vehicle', form)
                    .then((response) => {

                        if(response.data.data.driver && response.data.data.driver.id){
                            this.form.driver_id = response.data.data.driver.id;
                        } else {
                            this.form.driver_id = '';
                        }

                        if(response.data.data.helper && response.data.data.helper.id){
                            this.form.helper_id = response.data.data.helper.id;
                        } else {
                            this.form.helper_id = '';
                        }

                    }).catch((error) => {
                        this.toast.warning(error.message);
                    })
            } else {
                this.form.driver_id = '';
                this.form.helper_id = '';
            }
		},

        addVehicle(){
            // loading
			this.submitLoading = true;
			// insertion
            let request;
            if(this.pageType == 'create'){
                request = axios.post('/api/own-vehicle-challans', this.form);
            } else {
                request = axios.put('/api/own-vehicle-challans/'+ this.form.id, this.form);
            }
            request.then((response) => {
                this.toast.success(this.$t(response.data.message));
            }).catch((error) => {
                this.toast.warning(error.message);
            }).finally(() => {
                this.submitLoading = false
            })
        },
        resetForm(){
            this.form.id = '';
            this.form.challan_id = '';
            this.form.vehicle_id = '';
            this.form.driver_id = '';
            this.form.helper_id = '';
        },
	},
    computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
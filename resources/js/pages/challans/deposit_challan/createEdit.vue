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
                                    <h3 class="card-title"> {{ $t('challan_information') }}</h3>
                                    <router-link to="/deposit-challans" class="btn btn-sm btn-primary float-right"><i class="fa fa-list"></i> {{ $t('challan_list') }}</router-link>
                                </div>
                                <div class="card-body" v-if="(pageLoading == false)">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('challan_number') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                                <!-- required -->
                                                <div class="input-group">
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        list="suggestedChallanNumbers"
                                                        :class="{ 'is-invalid': (validationErrors && validationErrors['challan_number'])?true:false }"
                                                        v-model="form.challan_number" 
                                                        :placeholder="$t('write_challan_no_here')">
                                                    <span class="input-group-append">
                                                        <button type="button" :disabled="!form.challan_number.length>0" @click="verifyUniqueChallan" class="btn btn-primary">{{ $t('verify') }}</button>
                                                    </span>
                                                    <span
                                                        class="error invalid-feedback"
                                                        v-if="(validationErrors && validationErrors['challan_number'])?true:false"
                                                        v-for="(error) in validationErrors['challan_number']"
                                                        >{{ error }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('starting_date') }}</label>
                                                <input type="datetime-local" class="form-control" v-model="form.start_date_time">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('load_point') }}</label>
                                                <VueMultiselect
                                                    v-model="form.load_ids"
                                                    :options="loadOptions"
                                                    :multiple="true"
                                                    :loading="pointLoading"

                                                    :selectLabel="$t('press_enter_to_select')"
                                                    :deselectLabel="$t('press_enter_to_remove')"
                                                    :selectedLabel="$t('selected')"
                                                    :placeholder="$t('search_or_select_please')"

                                                    :taggable="true"
                                                    @tag="addLoadPoint"
                                                    :tag-placeholder="$t('add_this_point_as_new')"

                                                    track-by="id"
                                                    label="name"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('unload_point') }}</label>
                                                    <VueMultiselect
                                                        v-model="form.unload_ids"
                                                        :options="loadOptions"
                                                        :multiple="true"
                                                        :loading="pointLoading"

                                                        :selectLabel="$t('press_enter_to_select')"
                                                        :deselectLabel="$t('press_enter_to_remove')"
                                                        :selectedLabel="$t('selected')"
                                                        :placeholder="$t('search_or_select_please')"

                                                        :taggable="true"
                                                        @tag="addUnloadPoint"
                                                        :tag-placeholder="$t('add_this_point_as_new')"

                                                        track-by="id"
                                                        label="name"
                                                    />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('buyer_name') }}</label>
                                                <input type="text" class="form-control" v-model="form.buyer_name" :placeholder="$t('buyer_name')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('buyer_code') }}</label>
                                                <input type="text" class="form-control" v-model="form.buyer_code" :placeholder="$t('buyer_code')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('order_no') }}</label>
                                                <input type="text" class="form-control" v-model="form.order_no" :placeholder="$t('order_no')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('depu_change_bill') }}</label>
                                                <input type="text" class="form-control" v-model="form.depu_change_bill">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('gate_pass_no') }}</label>
                                                <input type="text" class="form-control" v-model="form.gate_pass_no" :placeholder="$t('gate_pass_no')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('lock_no') }}</label>
                                                <input type="text" class="form-control" v-model="form.lock_no" :placeholder="$t('lock_no')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('load_point_reach_time') }}</label>
                                                <input type="datetime-local" class="form-control" v-model="form.load_point_reach_time">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('box_qty') }}</label>
                                                <input type="number" class="form-control" v-model="form.box" :placeholder="$t('box_qty')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('weight') }}</label>
                                                <input type="number" class="form-control" v-model="form.weight" :placeholder="$t('weight')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('unit') }}</label>
                                                <select v-model="form.unit_id" class="form-control">
                                                    <option value="">{{ $t('please_select') }}</option>
                                                    <option value="1">{{ $t('ton') }}</option>
                                                    <option value="2">{{ $t('mon') }}</option>
                                                    <option value="3">{{ $t('kg') }}</option>
                                                    <option value="4">{{ $t('safety') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('about_goods') }}</label>
                                                <input type="text" class="form-control" v-model="form.goods" :placeholder="$t('about_goods')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('note') }}</label>
                                                <textarea rows="1" class="form-control" v-model="form.note" :placeholder="$t('you_can_write_any_note_here')"></textarea>
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>

                                <div class="card-body" v-else>
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-border"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title"> {{ $t('vehicle_information') }}</h3>
                                </div>
                                <div class="card-body" v-if="(pageLoading == false)">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('vehicle_select') }}</label>
                                                    <VueMultiselect
                                                        v-model="form.vehicle_id"
                                                        :options="vehicles"
                                                        :loading="vehicleLoading"
                                                        :multiple="false"

                                                        :selectLabel="$t('press_enter_to_select')"
                                                        :deselectLabel="$t('press_enter_to_remove')"
                                                        :selectedLabel="$t('selected')"
                                                        :placeholder="$t('search_or_select_please')"
                                                        @Select="getStaffBasedOnVehicle"

                                                        :taggable="true"
                                                        @tag="addVehicle"
                                                        :tag-placeholder="$t('add_this_as_new')"

                                                        track-by="id"
                                                        label="number_plate"
                                                    />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('driver_select') }}</label>
                                                    <VueMultiselect
                                                        v-model="form.driver_id"
                                                        :options="drivers"
                                                        :loading="driverLoading"

                                                        :selectLabel="$t('press_enter_to_select')"
                                                        :deselectLabel="$t('press_enter_to_remove')"
                                                        :selectedLabel="$t('selected')"
                                                        :placeholder="$t('search_or_select_please')"
                                                        
                                                        :taggable="true"
                                                        @tag="addDriver"
                                                        :tag-placeholder="$t('add_this_as_new')"

                                                        track-by="id"
                                                        label="name"
                                                    />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('helper_select') }}</label>
                                                    <VueMultiselect
                                                        v-model="form.helper_id"
                                                        :options="helpers"
                                                        :loading="helperLoading"

                                                        :selectLabel="$t('press_enter_to_select')"
                                                        :deselectLabel="$t('press_enter_to_remove')"
                                                        :selectedLabel="$t('selected')"
                                                        :placeholder="$t('search_or_select_please')"
                                                        
                                                        :taggable="true"
                                                        @tag="addHelper"
                                                        :tag-placeholder="$t('add_this_as_new')"

                                                        track-by="id"
                                                        label="name"
                                                    />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" v-else>
                                    <div class="d-flex justify-content-center">
                                        <p>
                                            <div class="spinner-border"></div> 
                                        </p>
                                    </div>
                                </div>

                                <div class="card-footer" v-if="pageType == 'create' && (pageLoading == false)">
                                    <button type="submit" :disabled="submitLoading" @click="(addAnother=true)" class="btn btn-sm btn-success mr-1">
                                        <i v-bind:class="submitBtnIcon"></i> {{ $t('post') }}
                                    </button>
                                    <button type="submit" :disabled="submitLoading" @click="(addAnother=false)" class="btn btn-sm btn-success mr-1">
                                        <i v-bind:class="submitBtnIcon"></i> {{ $t('post_and_go_to_list') }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary mr-1" @click="resetForm">
                                        <i class="fas fa-times"></i> {{ $t('reset') }}
                                    </button>
                                </div>
                                <div class="card-footer" v-if="pageType != 'create' && (pageLoading == false)">
                                    <button type="submit" :disabled="submitLoading" @click="(addAnother=false)" class="btn btn-sm btn-success mr-1">
                                        <i v-bind:class="submitBtnIcon"></i> {{ $t('update_post') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
			</div>
		</section>
	</div>
    <datalist id="suggestedChallanNumbers">
        <option v-for="suggestedChallanNumber in suggestedChallanNumbers" :value="suggestedChallanNumber.challan_number"></option>
    </datalist>
</template>

<script>
import { useToast } from "vue-toastification";
import VueMultiselect from 'vue-multiselect';

export default {
    components: {
        VueMultiselect
    },
	data() {
		return {
			toast: useToast(),
            addAnother: true,
            submitLoading: false,
            pageLoading: false,
            pageType: 'create',

			// form
			form: {

				id: '',
                // encrypt: '',
                // type: '',
				// group_id: '',
				voucher_number: '',
				challan_number: '',
				start_date_time: '',
				account_take_date_time: '',
				box: '',
				weight: '',
				unit_id: '',
				goods: '',
				buyer_name: '',
				buyer_code: '',
				order_no: '',
				depu_change_bill: 0,
				gate_pass_no: '',
				lock_no: '',
				load_point_reach_time: '',
				note: '',

                // vehicle info
				vehicle_id: '',
				driver_id: '',
				helper_id: '',
                load_ids: [],
                unload_ids: [],

			},
            settingAreas: {},
            validationErrors: {},

            loadOptions: [],
            // unloadOptions: [],

            // for commondate
			vehicles: [],
            drivers: [],
            helpers: [],

            pointLoading: false,
            vehicleLoading: false,
            driverLoading: false,
            helperLoading: false,

            // for common data suggestion
            suggestedChallanNumbers: [],
        }
	},
	created() {
        this.getUniqueChallanNumber();
        this.getSettingAreas();
        this.getVehicles();
        this.getDrivers();
        this.getHelpers();

        if(this.$route.params && this.$route.params.id){
            this.getEditData(this.$route.params.id);
            this.pageType = 'edit';
        }
	},
	methods: {
		createEditData() {
			// loading
			this.submitLoading = true;
            this.validationErrors = {};
			// insertion
            let test;
            if(this.pageType == 'create'){
                test = axios.post('/api/deposit-challans', this.form);
            } else {
                test = axios.put('/api/deposit-challans/'+ this.form.id, this.form);
            }
            test.then((response) => {
                this.toast.success(this.$t(response.data.message));

                if(this.addAnother == false){
                    this.$router.push('/deposit-challans');
                }

            }).catch((error) => {

                if (error.response.status == 422){
                    this.validationErrors = error.response.data.errors;
                }

            }).finally(() => {
                this.submitLoading = false
            })
		},
        resetForm(){
            // reset form
            // this.form.encrypt = '';
            // this.form.type = '';
            // this.form.group_id = '';
            this.form.voucher_number = '';
            this.form.challan_number = '';
            this.form.start_date_time = '';
            this.form.account_take_date_time = '';

            this.form.box = '';
            this.form.weight = '';
            this.form.unit_id = '';
            this.form.goods = '';
            this.form.buyer_name = '';
            this.form.buyer_code = '';
            this.form.order_no = '';
            this.form.depu_change_bill = 0;
            this.form.gate_pass_no = '';
            this.form.lock_no = '';
            this.form.load_point_reach_time = '';
            this.form.note = '';

            // vehicle info
            this.form.vehicle_id = '';
            this.form.driver_id = '';
            this.form.helper_id = '';
            this.form.load_ids = '';
            this.form.unload_ids = '';
        },
        getSettingAreas() {
			axios.get('/api/common/setting-areas')
				.then((response) => {
					this.loadOptions = response.data.lists;
					// this.unloadOptions = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
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
        getEditData(id) {
            this.pageLoading = true;
			axios.get('/api/deposit-challans/' + id)
				.then((response) => {
                    this.form = { ...this.form, ...response.data.data };

                    // params set
                    var params = { 
                        _this : this, 
                    }

                    // vehicle section
                    if(response.data.data && response.data.data.own_vehicle){
                    
                        params.vehicle_id = response.data.data.own_vehicle.vehicle_id;
                        params.driver_id = response.data.data.own_vehicle.driver_id;
                        params.helper_id = response.data.data.own_vehicle.helper_id;

                        // vehicle selection
                        if(this.vehicles.length > 0 && params.vehicle_id){
                            this.vehicles.filter(function(value){

                                if(value.id == params.vehicle_id){

                                    var data = { id : value.id, number_plate : value.number_plate };
                                    params._this.form.vehicle_id = data;
                                }
                            }, params);
                        }

                        // driver selection
                        if(this.drivers.length > 0 && params.driver_id){
                            this.drivers.filter(function(value){

                                if(value.id == params.driver_id){

                                    var data = { id : value.id, name : value.name };
                                    params._this.form.driver_id = data;
                                }
                            }, params);
                        }

                        // helper selection
                        if(this.helpers.length > 0 && params.helper_id){
                            this.helpers.filter(function(value){

                                if(value.id == params.helper_id){

                                    var data = { id : value.id, name : value.name };
                                    params._this.form.helper_id = data;
                                }
                            }, params);
                        }

                    }

                    // var params = { 
                    //     _this : this, 
                    //     vehicle_id : response.data.data.own_vehicle.vehicle_id,
                    //     driver_id : response.data.data.own_vehicle.driver_id, 
                    //     helper_id : response.data.data.own_vehicle.helper_id,
                    //     points : response.data.data.points,
                    // }

                    if(response.data.data && response.data.data.points){

                        params.points = response.data.data.points;

                        // load and unload point selection
                        if(params.points && params.points.length > 0 ){
                            params.points.forEach(function(value){

                                var data = { id : value.id, name : value.name };

                                switch (value.pivot.point) {
                                    case 'load':
                                        params._this.form.load_ids.push(data);
                                        break;
                                
                                    case 'unload':
                                        params._this.form.unload_ids.push(data);
                                        break;
                                }
                            }, params);
                        }
                    }

				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() => {
                    this.pageLoading = false;
                })
		},
        verifyUniqueChallan() {
			axios.get('/api/common/verify-unique-challan/' + this.form.challan_number)
				.then((response) => {
                    this.toast.success(this.$t('you_can_use_it'));
				}).catch((error) => {
					this.toast.warning(this.$t('challan_number_need_to_be_unique'));
				})
		},
        addLoadPoint(name){

            // insertion
			this.pointLoading = true;
            let form = { name : name, status : 'active'}

			axios
                .post('/api/settings/areas', form)
				.then((response) => {

                    // get response
                    var point = {
                        name: response.data.data.name,
                        id: response.data.data.id,
                    }

                    // set new value
                    this.loadOptions.push(point)
                    this.form.load_ids.push(point)

					this.toast.success(this.$t(response.data.message));

				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() => {
					this.pointLoading = false
				})
            
        },
        addUnloadPoint(name){

            // insertion
			this.pointLoading = true;
            let form = { name : name, status : 'active'}

            axios
                .post('/api/settings/areas', form)
                .then((response) => {

                    // get response
                    var point = {
                        name: response.data.data.name,
                        id: response.data.data.id,
                    }

                    // set new value
                    this.loadOptions.push(point)
                    this.form.unload_ids.push(point)

                    this.toast.success(this.$t(response.data.message));

                }).catch((error) => {
                    this.toast.warning(error.message);
                }).finally(() => {
                    this.pointLoading = false
                })
        },
        addVehicle(number_plate){

            // insertion
			this.vehicleLoading = true;
            let form = { number_plate : number_plate, ownership : 'own'}

            axios
                .post('/api/vehicles', form)
                .then((response) => {

                    // get response
                    var data = {
                        number_plate: response.data.data.number_plate,
                        id: response.data.data.id,
                    }

                    // set new value
                    this.vehicles.push(data);
                    this.form.vehicle_id = '';
                    this.form.vehicle_id = data;

                    this.toast.success(this.$t(response.data.message));

                }).catch((error) => {
                    this.toast.warning(error.message);
                }).finally(() => {
                    this.vehicleLoading = false
                })
        },
        addDriver(name){
            
            // insertion
            this.driverLoading = true;
            let form = { gender: 'male', name: name, designation_id : 1,  status: 'active'}

            axios
                .post('/api/staffs', form)
                .then((response) => {

                    // get response
                    var data = {
                        name: response.data.data.name,
                        id: response.data.data.id,
                    }

                    // set new value
                    this.drivers.push(data);
                    this.form.driver_id = '';
                    this.form.driver_id = data;

                    this.toast.success(this.$t(response.data.message));

                }).catch((error) => {
                    this.toast.warning(error.message);
                }).finally(() => {
                    this.driverLoading = false
                })
        },
        addHelper(name){

            // loading
            this.helperLoading = true;
            let form = { gender: 'male', name: name, designation_id : 2,  status: 'active'}

            axios
                .post('/api/staffs', form)
                .then((response) => {

                    // get response
                    var data = {
                        name: response.data.data.name,
                        id: response.data.data.id,
                    }

                    // set new value
                    this.helpers.push(data);
                    this.form.helper_id = '';
                    this.form.helper_id = data;

                    this.toast.success(this.$t(response.data.message));

                }).catch((error) => {
                    this.toast.warning(error.message);
                }).finally(() => {
                    this.helperLoading = false
                })
        },
        getStaffBasedOnVehicle() {
            if(this.form.vehicle_id){
                let form = { 'vehicle_id' : this.form.vehicle_id };
                    axios.post('/api/common/get-staff-based-on-vehicle', form)
                    .then((response) => {

                        if(response.data.data.driver && response.data.data.driver.id){

                            var data = {
                                name: response.data.data.driver.name,
                                id: response.data.data.driver.id,
                            }

                            this.form.driver_id = '';
                            this.form.driver_id = data;

                        } else {
                            this.form.driver_id = '';
                        }

                        if(response.data.data.helper && response.data.data.helper.id){

                            var data = {
                                name: response.data.data.helper.name,
                                id: response.data.data.helper.id,
                            }

                            this.form.helper_id = '';
                            this.form.helper_id = data;
                            
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
        getUniqueChallanNumber(){
			axios.get('/api/common/get-unique-challan-number')
				.then((response) => {
					this.suggestedChallanNumbers = response.data.data;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        }

	},
    computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<template>
    <div>
        <div class="modal fade" id="showPassengerChallanModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('new_challan') }} ({{ $t('going') }})</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="addUpdate()">
                        <div class="modal-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">{{ $t('challan_number') }} <small class="text-danger">({{ $t('required') }})</small></label>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('date') }}</label>
                                        <VueDatePicker 
                                            v-model="form.start_date_time"
                                            :placeholder="$t('select_date')"
                                            :enable-time-picker="false"
                                            format="dd/MM/yyyy"
                                            text-input
                                            >
                                        </VueDatePicker>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('coach_no') }}</label>
                                        <input 
                                            type="text" 
                                            class="form-control"
                                            list="suggestedCoachNumbers"
                                            v-model="form.coach_number" 
                                            :placeholder="$t('coach_number')">
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                                <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">{{ $t('guide_select') }}</label>
                                        <VueMultiselect
                                            v-model="form.guide_id"
                                            :options="guides"
                                            :loading="guideLoading"

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
                                <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('note') }}</label>
                                        <textarea rows="1" class="form-control" v-model="form.note" :placeholder="$t('you_can_write_any_note_here')"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
                            <div v-if="!form.id">
                                <button type="submit" :disabled="submitLoading" @click="addAnother=false" class="btn btn-success mr-1">
                                    <i v-bind:class="submitBtnIcon"></i> {{ $t('post') }}
                                </button>
                                <button type="submit" :disabled="submitLoading" @click="addAnother=true" class="btn btn-success">
                                    <i v-bind:class="submitBtnIcon"></i> {{ $t('post_and_add_another') }}
                                </button>
                            </div>
                            <div v-else>
                                <button type="submit" :disabled="submitLoading" @click="addAnother=false" class="btn btn-success mr-1">
                                    <i v-bind:class="submitBtnIcon"></i> {{ $t('update_post') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <datalist id="suggestedChallanNumbers">
        <option v-for="suggestedChallanNumber in suggestedChallanNumbers" :value="suggestedChallanNumber.challan_number"></option>
    </datalist>
    <datalist id="suggestedCoachNumbers">
        <option v-for="suggestedChallanNumber in suggestedChallanNumbers" :value="suggestedChallanNumber.challan_number"></option>
    </datalist>
</template>

<script>
import { useToast } from "vue-toastification";
import Swal from 'sweetalert2';
import moment from 'moment';
import VueMultiselect from 'vue-multiselect';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'


export default {
    name: 'GeneralExpenseModal',
    props: {
        vehicles: { type: [Array], default: []},
        drivers: { type: [Array], default: []},
        guides: { type: [Array], default: []},
        helpers: { type: [Array], default: []},

    },
    components: {
        VueMultiselect,
        VueDatePicker
    },
	data() {
		return {
            toast: useToast(),
            submitLoading: false,
            addAnother: false,
            listLoading: false,
            
            form: {
                id: '',
				challan_type: 'passenger_going',
                challan_number: '',
                coach_number: '',
				start_date_time: moment().format('YYYY-MM-DD'),
				vehicle_id: '',
				driver_id: '',
				guide_id: '',
				helper_id: '',
                note: '',
			},

            filter: {
				challan_id: '',
                paginate: false
			},

            validationErrors: {},
            vehicleLoading: false,
            driverLoading: false,
            guideLoading: false,
            helperLoading: false,
            

            // for common data suggestion
            suggestedChallanNumbers: [],
		}
	},
    created() {},
    mounted(){},
	methods: {
        addUpdate(){
			this.submitLoading = true;
			this.validationErrors = {};
            var request;

			// update
			if(this.form.id){
				request = axios.put('/api/passenger-challans/' + this.form.id, this.form)
			// insert
			} else {
				request = axios.post('/api/passenger-challans', this.form)
			}

            request
                .then((response) => {

                    if(this.form.challan_id){
                        this.$emit('afterAdd', this.filter.challan_id);
                    } else {
                        this.$emit('afterAdd');
                    }

                    if(!this.addAnother){
                        this.resetForm();
						$("#showPassengerChallanModal").modal("hide");
					}

                    this.toast.success(this.$t(response.data.message));

                }).catch((error) => {

                    if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                    }
                    this.submitLoading = false
                }).finally(() => {
                    this.submitLoading = false
                })
		},
        resetForm(){
            this.form.id = '';
            this.form.challan_type = 'passenger_going';
            this.form.challan_number = '';
            this.form.coach_number = '';
            this.form.start_date_time = moment().format('YYYY-MM-DD');
            this.form.vehicle_id = '';
            this.form.driver_id = '';
            this.form.guide_id = '';
            this.form.helper_id = '';
            this.form.note = '';
        },
        verifyUniqueChallan() {
			axios.get('/api/common/verify-unique-challan/' + this.form.challan_number)
				.then((response) => {
                    this.toast.success(this.$t('you_can_use_it'));
				}).catch((error) => {
					this.toast.warning(this.$t('challan_number_need_to_be_unique'));
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

                        if(response.data.data.guide && response.data.data.guide.id){

                            var data = {
                                name: response.data.data.guide.name,
                                id: response.data.data.guide.id,
                            }

                            this.form.guide_id = '';
                            this.form.guide_id = data;

                        } else {
                            this.form.guide_id = '';
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
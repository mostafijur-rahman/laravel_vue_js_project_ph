<template>
    <div>
        <div class="modal fade" id="showBillAddModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h4 class="modal-title" v-if="dealWith == 'client'">
                            {{ $t('list_of_billing_with_company') }}
                        </h4>
                        <h4 class="modal-title" v-if="dealWith == 'vendor'">
                            {{ $t('list_of_billing_with_vendor') }}
                        </h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped text-center table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">{{ $t('sl') }}</th>
                                            <th>{{ $t('date') }}</th>
                                            <th>{{ $t('bill_type') }}</th>
                                            <th>{{ $t('voucher_number') }}</th>
                                            <th class="text-right">{{ $t('amount') }}</th>
                                            <th>{{ $t('action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="(lists.length > 0)" v-for="(list, listIndex) in lists">
                                            <td>{{ listIndex + 1 }}</td>
                                            <td>{{ $getHumanDate(list.date_time) }}</td>
                                            <td>
                                                {{ $t(list.bill_type) }} <br>
                                            </td>
                                            <td>
                                                {{ list.voucher_number??'---' }} <br>
                                                <small>{{ list.note }}</small>
                                            </td>
                                            <td class="text-right">{{ $numFormat(list.amount) }}</td>
                                            <td>
                                                <button 
                                                    type="button" 
                                                        @click="deleteModal(list.id, listIndex, list.purposeable_id)"
                                                        class="btn btn-xs bg-gradient-danger" 
                                                        :title="$t('delete')"
                                                        >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-else>
                                            <td colspan="6">
                                                <h6 class="text-danger">{{ $t('no_data') }}</h6>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right">{{ $t('total') }} =</td>
                                            <td class="text-right">
                                                <b v-if="(lists.length > 0)">{{ $numFormat($getSumFromLists(lists, 'amount')) }}</b>
                                            </td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <form @submit.prevent="add()">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4" v-if="dealWith == 'vendor'">
                                    <div class="form-group">
                                        <label for="">{{ $t('vendor') }} / {{ $t('supplier') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                        <VueMultiselect
                                            v-model="form.vendor_id"
                                            :options="vendors"
                                            :multiple="false"
                                            :loading="vendorLoading"
                                            :disabled="disabledVendor"

                                            :selectLabel="$t('press_enter_to_select')"
                                            :deselectLabel="$t('press_enter_to_remove')"
                                            :selectedLabel="$t('selected')"
                                            :placeholder="$t('search_or_select_please')"

                                            :taggable="true"
                                            @tag="addVendor"
                                            :tag-placeholder="$t('add_this_as_new')"

                                            track-by="id"
                                            label="name"
                                        />
                                        <span
                                            class="error invalid-feedback"
                                            v-if="(validationErrors && validationErrors['vendor_id'])?true:false"
                                            v-for="(error) in validationErrors['vendor_id']"
                                            >{{ error }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4" v-if="dealWith == 'client'">
                                    <div class="form-group">
                                        <label for="">{{ $t('client') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                        <VueMultiselect
                                            v-model="form.client_id"
                                            :options="clients"
                                            :multiple="false"
                                            :loading="clientLoading"
                                            :disabled="disabledClient"

                                            :selectLabel="$t('press_enter_to_select')"
                                            :deselectLabel="$t('press_enter_to_remove')"
                                            :selectedLabel="$t('selected')"
                                            :placeholder="$t('search_or_select_please')"

                                            :taggable="true"
                                            @tag="addClient"
                                            :tag-placeholder="$t('add_this_as_new')"

                                            track-by="id"
                                            label="name"
                                        />
                                        <span
                                            class="error invalid-feedback"
                                            v-if="(validationErrors && validationErrors['client_id'])?true:false"
                                            v-for="(error) in validationErrors['client_id']"
                                            >{{ error }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('date') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                        <VueDatePicker 
                                            v-model="form.date_time"
                                            :placeholder="$t('select_date')"
                                            :enable-time-picker="false"
                                            format="dd/MM/yyyy"
                                            text-input
                                            >
                                        </VueDatePicker>
                                        <span
                                            class="error invalid-feedback"
                                            v-if="(validationErrors && validationErrors['date_time'])?true:false"
                                            v-for="(error) in validationErrors['date_time']"
                                            >{{ error }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">{{ $t('bill_type') }}</label>
                                        <select 
                                            v-model="form.bill_type" 
                                            class="form-control"
                                            :class="{ 'is-invalid': (validationErrors && validationErrors['bill_type'])?true:false }"
                                            >
                                            <option v-if="(billTypes.length > 0)" v-for="billType in billTypes" :value="billType.name">{{ $t(billType.value) }}</option>
                                        </select>
                                        <span
                                            class="error invalid-feedback"
                                            v-if="(validationErrors && validationErrors['bill_type'])?true:false"
                                            v-for="(error) in validationErrors['bill_type']"
                                            >{{ error }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('amount') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': (validationErrors && validationErrors['amount'])?true:false }"
                                            v-model="form.amount" 
                                            :placeholder="$t('write_amount_here')" required>
                                        <span
                                            class="error invalid-feedback"
                                            v-if="(validationErrors && validationErrors['amount'])?true:false"
                                            v-for="(error) in validationErrors['amount']"
                                            >{{ error }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('voucher_number') }}</label>
                                        <input type="text" class="form-control" v-model="form.voucher_number" :placeholder="$t('write_voucher_number_here')">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('note') }}</label>
                                        <textarea rows="1" class="form-control" v-model="form.note" :placeholder="$t('write_here')"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
                            <div>
                                <button type="submit" :disabled="submitLoading" @click="addAnother=false" class="btn btn-sm btn-success mr-1">
                                    <i v-bind:class="submitBtnIcon"></i> {{ $t('post') }}
                                </button>
                                <button type="submit" :disabled="submitLoading" @click="addAnother=true" class="btn btn-sm btn-success">
                                    <i v-bind:class="submitBtnIcon"></i> {{ $t('post_and_add_another') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueMultiselect from 'vue-multiselect';
import { useToast } from "vue-toastification";
import Swal from 'sweetalert2';
import moment from 'moment';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    name: 'BillAddModal',
    components: { 
        VueDatePicker,
        VueMultiselect
    },
    props: {
        clients: { type: [Array], default: []},
        vendors: { type: [Array], default: []},
    },
	data() {
		return {
            toast: useToast(),
            submitLoading: false,
            addAnother: false,
            billTypes: [
                { name: 'contract_rent', value: 'contract_rent' },
                // { name: 'extend_rent', value: 'extend_rent' },
                { name: 'demurrage_rent', value: 'demurrage_rent' },
            ],
            dealWith: '',
            disabledClient: false,
            disabledVendor: false,
            form: {
				id: '',
                purposeable_id: '',
                purposeable_type: '',
                vendor_id: '',
				client_id: '',

				bill_type: 'contract_rent',
                voucher_number: '',
				date_time: moment().format('YYYY-MM-DD'),
				amount: 0,
				note: '',
			},
            apiLink: '',
            lists: {},
            validationErrors: {},

            vendorLoading: false,
            clientLoading: false
		}
	},
    created() {
    },
    mounted(){
    },
	methods: {
        add(){
			this.submitLoading = true;
			this.validationErrors = {};

            axios
                .post(this.apiLink, this.form)
                .then((response) => {

                    this.lists.push(response.data.data);
                    
                    if(!this.addAnother){
                        this.resetForm();
						$("#showBillAddModal").modal("hide");
					}
                    this.$emit('afterAdd', response.data.data);

                    // disable every time
                    this.disabledClient = true;
                    this.disabledVendor = true;
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
        getLists() {
            this.lists = {};

            let params = {
                purposeable_id: this.form.purposeable_id,
                purposeable_type: this.form.purposeable_type,
				vendor_id: this.form.vendor_id,
				client_id: this.form.client_id,
                paginate: false
            };

            axios.get(this.apiLink, { params })
				.then((response) => {
					this.lists = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
        deleteModal(id, index, purposeableId) {
			Swal.fire({
				title: this.$t('are_you_sure'),
				text: this.$t('you_wont_be_able_to_revert_this'),
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: this.$t('yes_delete_it'),
				cancelButtonText: this.$t('cancel'),
			}).then((result) => {
				if (result.isConfirmed) {
					this.destroy(id, index, purposeableId);
				}
			})
		},
		async destroy(id, index, purposeableId) {
			try {
				// show loading
				Swal.fire({
					title: this.$t('operation_running'),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					didOpen: () => {
						Swal.showLoading();
					}
				})
				// fire request
				await axios.delete(this.apiLink + '/' + id);
                this.lists.splice(index, 1);
                this.$emit('afterDelete', purposeableId);

				Swal.close();
				Swal.fire({
					position: 'center',
					icon: 'success',
					title: this.$t('successfully_deleted'),
					showConfirmButton: false,
					timer: 1000
				})

			} catch (error) {
				Swal.close();
				Swal.fire({
					position: 'center',
					icon: 'error',
					title: this.$t('something_went_wrong'),
					showConfirmButton: false,
					timer: 1000
				})
			}
		},
        resetForm(){
            this.form.id = '';

            this.form.purposeable_id = '';
            this.form.purposeable_type = '';
            this.form.vendor_id = '';
            this.form.client_id = '';

            this.form.bill_type = 'contract_rent';
            this.form.voucher_number = '';
            this.form.date_time = moment().format('YYYY-MM-DD');
            this.form.amount = 0;
            this.form.note = '';

            // this.dealWith = ''; 
            this.disabledClient = false;
            this.disabledVendor = false;
        },
        setDefaultVendor(){
            // params set
            var params = { 
                _this : this,
                vendor_id : this.form.vendor_id
            }
            this.vendors.filter(function(value){
                if(value.id == params.vendor_id){
                    var data = { id : value.id, name : value.name };
                    params._this.form.vendor_id = data;
                }
            }, params);
        },
        setDefaultClient(){
            // params set
            var params = { 
                _this : this,
                client_id : this.form.client_id
            }
            this.clients.filter(function(value){
                if(value.id == params.client_id){
                    var data = { id : value.id, name : value.name };
                    params._this.form.client_id = data;
                }
            }, params); 
        },
        addVendor(){
            console.log('add Vendor');
        },
        addClient(name){
            // insertion
            this.clientLoading = true;
            let newForm = { 
                name : name,
                previous_loan : 0,
                previous_advance : 0,
                status : 'active',
            }
            axios
                .post('/api/clients', newForm)
                .then((response) => {

                    var value = {
                        name: response.data.data.name,
                        id: response.data.data.id,
                    }

                    this.clients.push(value);
                    this.form.client_id = '';
                    this.form.client_id = value;
                    this.toast.success(this.$t(response.data.message));

                }).catch((error) => {
                    this.toast.warning(error.message);
                }).finally(() => {
                    this.clientLoading = false
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
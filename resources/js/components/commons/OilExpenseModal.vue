<template>
    <div>
        <div class="modal fade" id="showOilExpenseModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('oil_expense') }}</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" v-if="form.challan_id">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped text-center table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ $t('sl') }}</th>
                                                <th>{{ $t('date') }}</th>
                                                <th class="text-left" v-if="$store.state.auth.user.company.payment_feature == 'enable'">{{ $t('payment_method') }}</th>
                                                <th>{{ $t('vendor') }}</th>
                                                <th>{{ $t('liter') }}</th>
                                                <th>{{ $t('rate') }}</th>
                                                <th>{{ $t('amount') }}</th>
                                                <th>{{ $t('action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="(listLoading == true)">
                                                <td colspan="8">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="spinner-border"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-if="(oilExpenses.length > 0)" v-for="(expense, expenseIndex) in oilExpenses">
                                                <td>{{ expenseIndex + 1 }}</td>
                                                <td>{{ $getHumanDate(expense.date_time) }}</td>

                                                <td class="text-left" v-if="$store.state.auth.user.company.payment_feature == 'enable'">
                                                    <small v-if="expense.transaction && expense.transaction.account">
                                                        <b>{{ (expense.transaction && expense.transaction.account && expense.transaction.account.bank_info )? expense.transaction.account.bank_info.name : $t('cash_account') }}</b><br>
                                                        <span v-if="expense.transaction && expense.transaction.account && expense.transaction.account.account_number">
                                                            {{ $t('account_number') }} : <b>{{ expense.transaction.account.account_number }}</b><br>
                                                        </span>
                                                        <span v-if="expense.transaction && expense.transaction.account && expense.transaction.account.holder_name">
                                                            {{ $t('holder_name') }} : <b>{{ expense.transaction.account.holder_name }}</b><br>
                                                        </span>
                                                        {{ $t('user_name') }} : <b>{{ (expense.transaction  && expense.transaction.account.user_name )? expense.transaction.account.user_name :'---' }}</b><br> 
                                                        {{ $t('transaction_id') }} : <b>{{ expense.transaction? expense.transaction.encrypt :'---' }}</b><br> 
                                                    </small>
                                                    <small v-if="expense.loan">
                                                        {{ $t('vendor') }} : <b>{{ expense.vendor_id?expense.vendor_info.name:'---' }}</b><br>  
                                                        <span class="text-danger">{{ $t('loan_id') }} : <b>{{ expense.loan? expense.loan.encrypt :'---' }}</b></span>
                                                    </small>
                                                    <small v-if="!expense.loan && !expense.transaction">
                                                        <b>---</b>
                                                    </small>
                                                </td>

                                                <td>
                                                    {{ expense.vendor_info?expense.vendor_info.name:'---' }} <br>
                                                    <small>{{ $t('voucher_id') }} : <b>{{ expense.voucher_number??'---' }}</b></small>
                                                </td>

                                                <td>{{ $numFormat(expense.liter) }}</td>
                                                <td>{{ $numFormat(expense.rate) }}</td>
                                                <td>{{ $numFormat(expense.amount) }}</td>
                                                <td>
                                                    <button 
                                                        type="button" 
                                                            @click="deleteModal(expense.id, expenseIndex)"
                                                            class="btn btn-xs bg-gradient-danger" 
                                                            :title="$t('delete')"
                                                            >
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr v-if="(oilExpenses.length == 0)">
                                                <td colspan="8"><h5 class="text-danger">{{ $t('no_data') }}</h5></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right">{{ $t('total') }} =</td>
                                                <td class="text-center">
                                                    <b v-if="(oilExpenses.length > 0)">{{ $numFormat($getSumFromLists(oilExpenses, 'liter')) }}</b>
                                                </td>
                                                <td class="text-center">
                                                    <b v-if="(oilExpenses.length > 0)">{{ $numFormat($getSumFromLists(oilExpenses, 'rate')) }}</b>
                                                </td>
                                                <td class="text-center">
                                                    <b v-if="(oilExpenses.length > 0)">{{ $numFormat($getSumFromLists(oilExpenses, 'amount')) }}</b>
                                                </td>
                                                <td class="text-center"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="addUpdate()">

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ $t('date') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                            <input 
                                                type="date" 
                                                class="form-control" 
                                                :class="{ 'is-invalid': (validationErrors && validationErrors['date_time'])?true:false }"
                                                v-model="form.date_time" required>
                                            <span
                                                class="error invalid-feedback"
                                                v-if="(validationErrors && validationErrors['date_time'])?true:false"
                                                v-for="(error) in validationErrors['date_time']"
                                                >{{ error }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4"
                                        v-if="$store.state.auth.user.company.payment_feature == 'enable' || $store.state.auth.user.company.loan_feature == 'enable'"
                                        >
                                        <div class="form-group">
                                            <label for="">
                                                {{ $t('payment_method') }}
                                                <small v-if="$store.state.auth.user.company.payment_feature == 'enable'" class="text-danger">({{ $t('required') }})</small>
                                            </label>
                                            <select 
                                                v-model="form.account_id" 
                                                class="form-control"
                                                :class="{ 'is-invalid': (validationErrors && validationErrors['account_id'])?true:false }"
                                                :required="$store.state.auth.user.company.payment_feature == 'enable'"
                                                >
                                                <option value="">{{ $t('please_select') }}</option>
                                                <option v-if="(accounts.length > 0) && $store.state.auth.user.company.payment_feature == 'enable'" v-for="account in accounts" :value="account.id">
                                                <span v-if="account.bank_id">
                                                    {{ account.account_number }} - ({{ account.bank_info.name }}) = ({{ $numFormat(account.balance) }})
                                                </span>
                                                <span v-else>
                                                    {{ account.user_name }} = ({{ $numFormat(account.balance) }})
                                                </span>
                                            </option>
                                                <option value="loan" v-if="$store.state.auth.user.company.loan_feature == 'enable'">{{ $t('loan') }}</option>
                                            </select>
                                            <span
                                                class="error invalid-feedback"
                                                v-if="(validationErrors && validationErrors['account_id'])?true:false"
                                                v-for="(error) in validationErrors['account_id']"
                                                >{{ error }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">{{ $t('pump') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                            <VueMultiselect
                                                :class="{ 'is-invalid': (validationErrors && validationErrors['vendor_id'])?true:false }"
                                                :required="$store.state.auth.user.company.loan_feature == 'enable' && form.account_id == 'loan'"
                                                v-model="form.vendor_id"
                                                :options="vendors"
                                                :multiple="false"
                                                :loading="vendorLoading"

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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ $t('voucher_number') }}</label>
                                            <input type="text" class="form-control" v-model="form.voucher_number" :placeholder="$t('write_voucher_number_here')">
                                        </div>
                                    </div>
                                    <div class="col-md-4" v-if="!form.challan_id">
                                        <div class="form-group">
                                            <label for="">{{ $t('vehicle') }}</label>
                                            <VueMultiselect
                                                v-model="form.vehicle_id"
                                                :options="vehicles"
                                                :loading="vehicleLoading"
                                                :multiple="false"

                                                :selectLabel="$t('press_enter_to_select')"
                                                :deselectLabel="$t('press_enter_to_remove')"
                                                :selectedLabel="$t('selected')"
                                                :placeholder="$t('search_or_select_please')"
                                            
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
                                            <label>{{ $t('liter') }}</label>
                                            <input 
                                                type="number" 
                                                class="form-control" 
                                                :class="{ 'is-invalid': (validationErrors && validationErrors['liter'])?true:false }"
                                                v-model="form.liter" 
                                                :placeholder="$t('write_liter_here')" required>
                                            <span
                                                class="error invalid-feedback"
                                                v-if="(validationErrors && validationErrors['liter'])?true:false"
                                                v-for="(error) in validationErrors['liter']"
                                                >{{ error }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ $t('rate') }}</label>
                                            <input 
                                                type="number" 
                                                class="form-control"
                                                :class="{ 'is-invalid': (validationErrors && validationErrors['rate'])?true:false }"
                                                v-model="form.rate" 
                                                :placeholder="$t('write_rate_here')" required>
                                            <span
                                                class="error invalid-feedback"
                                                v-if="(validationErrors && validationErrors['rate'])?true:false"
                                                v-for="(error) in validationErrors['rate']"
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
                                            <label>{{ $t('note') }}</label>
                                            <textarea rows="1" class="form-control" v-model="form.note" :placeholder="$t('write_here')"></textarea>
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
</template>

<script>
import { useToast } from "vue-toastification";
import Swal from 'sweetalert2';
import moment from 'moment';
import VueMultiselect from 'vue-multiselect';

export default {
    name: 'OilExpenseModal',
    props: {
        accounts: { type: [Object], default: {}},
        vendors: { type: [Array], default: []},
        vendorType: { type: [String], default: 'string'},
        vehicles: { type: [Array], default:[]},
    },
    components: {
        VueMultiselect
    },
	data() {
		return {
            toast: useToast(),
            submitLoading: false,
            addAnother: false,
            listLoading: false,

            oilExpenses: {},
            form: {
				id: '',
				account_id: '',
                
				vendor_id: '',
				vehicle_id: '',
				challan_id: '',
				voucher_number: '',

				date_time: moment().format('YYYY-MM-DD'),
                liter: 0,
                rate: 0,
				amount: 0,

				note: '',
			},
            filter: {
				challan_id: '',
                paginate: false
			},
            validationErrors: {},
            vendorLoading: false,
            vehicleLoading: false,
		}
	},
    created() {},
    mounted(){},
	methods: {
        addUpdate(){
			this.submitLoading = true;
            var request;

            // update
			if(this.form.id){
				request = axios.put('/api/expense-oils/' + this.form.id, this.form)
			// insert
			} else {
				request = axios.post('/api/expense-oils', this.form)
			}

            request
                .then((response) => {

                    if(this.form.challan_id){
                        this.$emit('refreshOilExpense', this.filter.challan_id);
                        this.oilExpenses.push(response.data.data);
                    } else {
                        this.$emit('refreshOilExpense');
                    }

                    if(!this.addAnother){
                        this.resetForm();
						$("#showOilExpenseModal").modal("hide");
					}

                    this.toast.success(this.$t(response.data.message));

                }).catch((error) => {

                    console.log(error);

                    if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                    }
                    this.submitLoading = false

                }).finally(() => {
                    this.submitLoading = false;
                })
		},
        getOilExpenses(parameters) {
            this.listLoading = true;
            this.oilExpenses = {};

            this.form.challan_id = parameters.challanId;
            this.filter.challan_id = parameters.challanId;
            let params = this.filter;
            
            axios.get('/api/expense-oils', { params })
				.then((response) => {
					this.oilExpenses = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() =>{
					this.listLoading = false;
				});
		},
        deleteModal(id, index) {
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
					this.destroy(id, index);
				}
			})
		},
		async destroy(id, index) {
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
				await axios.delete('/api/expense-oils/' + id);
                this.oilExpenses.splice(index, 1);
                this.$emit('refreshOilExpense', this.filter.challan_id);

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
            this.form.account_id = '';

            this.form.vendor_id = '';
            this.form.vehicle_id = '';
            // this.form.challan_id = '';
            this.form.voucher_number = '';

            this.form.date_time = moment().format('YYYY-MM-DD');
            this.form.liter = 0;
            this.form.rate = 0;
            this.form.amount = 0;

            this.form.note = '';
        },
        addVendor(name){
            // insertion
            this.vendorLoading = true;
            let newForm = { 
                name : name, 
                status : 'active', 
                type: this.vendorType
            }
            axios
                .post('/api/vendors', newForm)
                .then((response) => {

                    var value = {
                        name: response.data.data.name,
                        id: response.data.data.id,
                    }
                    this.$emit("addVendor", value);
                    this.form.vendor_id = '';
                    this.form.vendor_id = value;

                    this.toast.success(this.$t(response.data.message));

                }).catch((error) => {
                    this.toast.warning(error.message);
                }).finally(() => {
                    this.vendorLoading = false
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
                    var value = {
                        number_plate: response.data.data.number_plate,
                        id: response.data.data.id,
                    }

                    // set new value
                    this.$emit("addVehicle", value);
                    this.form.vehicle_id = '';
                    this.form.vehicle_id = value;

                    this.toast.success(this.$t(response.data.message));

                }).catch((error) => {
                    this.toast.warning(error.message);
                }).finally(() => {
                    this.vehicleLoading = false
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
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
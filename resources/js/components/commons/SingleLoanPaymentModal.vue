<template>
    <div>
        <div class="modal fade" id="showSingleLoanPaymentModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('payment_list') }}</h4>
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
                                            <th class="text-left" v-if="$store.state.auth.user.company.payment_feature == 'enable'">{{ $t('payment_method') }}</th>
                                            <th class="text-right">{{ $t('amount') }}</th>
                                            <th>{{ $t('action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="(listLoading == true)">
											<td colspan="5">
												<div class="d-flex justify-content-center">
													<div class="spinner-border"></div>
												</div>
											</td>
										</tr>
                                        <tr v-if="(lists.length > 0)" v-for="(list, listIndex) in lists">
                                            <td>{{ listIndex + 1 }}</td>
                                            <td>{{ $getHumanDate(list.date_time) }}</td>
                                            <td class="text-left" v-if="$store.state.auth.user.company.payment_feature == 'enable'">
												<small v-if="list.account">
                                                    <b>{{ (list.account && list.account.bank_info )? list.account.bank_info.name : $t('cash_account') }}</b><br>
                                                    <span v-if="list.account && list.account.account_number">
														{{ $t('account_number') }} : <b>{{ list.account.account_number }}</b><br>
													</span>
													<span v-if="list.account && list.account.holder_name">
														{{ $t('holder_name') }} : <b>{{ list.account.holder_name }}</b><br>
													</span>
                                                    {{ $t('user_name') }} : <b>{{ (list.account.user_name )? list.account.user_name :'---' }}</b><br> 
                                                    {{ $t('transaction_id') }} : <b>{{ list.account? list.encrypt :'---' }}</b><br> 
                                                    {{ list.note }}
                                                </small>
                                                <small v-else>---</small>
											</td>
                                            <td class="text-right">{{ $numFormat(list.amount) }}</td>
                                            <td>
                                                <button 
                                                    type="button" 
                                                        @click="deleteModal(list.id, listIndex, list.amount)"
                                                        class="btn btn-xs bg-gradient-danger" 
                                                        :title="$t('delete')"
                                                        >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="(lists.length == 0)">
											<td colspan="5"><h5 class="text-danger">{{ $t('no_data') }}</h5></td>
										</tr>
                                        <tr>
                                            <td colspan="3" class="text-right">{{ $t('total') }} =</td>
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

                    <form v-if="showForm" @submit.prevent="add()">
                        <div class="modal-body">
                            <div class="row">
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
                                <div class="col-md-4"
                                    v-if="$store.state.auth.user.company.payment_feature == 'enable'"
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
import { useToast } from "vue-toastification";
import Swal from 'sweetalert2';
import moment from 'moment';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    name: 'SingleLoanPaymentModal',
    components: { 
        VueDatePicker 
    },
    props: {
        accounts: { type: [Object], default: {}},
        items: { type: [Object], default: {}},
    },
	data() {
		return {
            toast: useToast(),
            submitLoading: false,
            addAnother: false,
            listLoading: false,

            form: {
				id: '',
				date_time: moment().format('YYYY-MM-DD'),
				account_id: '',
				amount: 0,
				note: '',
                items: this.items,

                loan_id: '',
                index_id: '',
			},

            apiLink: '/api/single-loan-payment-store',
            validationErrors: {},
            lists: {},
            showForm: false
		}
	},
    created() {},
    mounted(){},
	methods: {
        getLists() {
            this.listLoading = true;
            this.lists = {};
            let params = {
                transactionable_id: this.form.loan_id,
                transactionable_type: 'loan_trans',
                orderType: 'asc',
                paginate: false
            };
            axios
                .get('/api/account-transactions', { params })
				.then((response) => {
					this.lists = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() =>{
					this.listLoading = false;
				});
		},
        add(){
			this.submitLoading = true;
			this.validationErrors = {};
            axios
                .post(this.apiLink, this.form)
                .then((response) => {

                    this.lists.push(response.data.data);

                    if(!this.addAnother){
                        this.resetForm();
						$("#showSingleLoanPaymentModal").modal("hide");
					}

                    this.$emit('afterAdd', { 'data' : response.data.data, 'indexId' : this.form.index_id });
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
            this.form.date_time = moment().format('YYYY-MM-DD');
            this.form.account_id = '';
            this.form.amount = 0;
            this.form.note = '';
        },
        deleteModal(id, index, amount) {
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
					this.destroy(id, index, amount);
				}
			})
		},
		async destroy(id, index, amount) {
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
				await axios.delete('/api/single-loan-payment-delete/' + id);
                this.lists.splice(index, 1);
                this.$emit('afterDelete', { 'amount' : amount, 'indexId' : this.form.index_id });

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
	},
    computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
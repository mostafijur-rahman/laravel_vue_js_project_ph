<template>
	<div class="content-wrapper">
		<section class="content-header"></section>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">

						<div class="card">
							<div class="card-header">
								<SearchFilterModal
									:searchOptions="searchOptions"
									:settingBanks="settingBanks"

									@sendParams="setSearchFilterParams"
									/>
								<div class="card-tools">
									<button 
										type="button" 
										class="btn btn-sm btn-primary mr-1"
										@click="showSearchFilterModal"
										>
										<i class="fa fa-search"></i> {{ $t('search') }}
									</button>
									<button 
										v-if="$store.state.auth.user.company.payment_feature == 'enable'"
										type="button" 
										class="btn btn-sm btn-success mr-1"
										data-toggle="modal" 
										data-target="#add"
										@click="cashIncashOutModal(null, null)"
										>
										<i class="fa fa-plus"></i> {{ $t('cash_in') }} / {{ $t('cash_out') }}
									</button>
									<button 
										v-if="$store.state.auth.user.company.payment_feature == 'enable'"
										type="button" 
										class="btn btn-sm btn-success mr-1"
										data-toggle="modal" 
										data-target="#add"
										@click="balanceTransferModal(null, null)"
										>
										<i class="fa fa-plus"></i> {{ $t('balance_transfer') }}
									</button>
									<!-- <button 
										type="button" 
										class="btn btn-sm btn-primary mr-1"
										@click="showCurrentBalanceModal()"
										>
										<i class="fas fa-money-check"></i> {{ $t('current_balance') }}
									</button> -->
									<button 
										type="button" 
										class="btn btn-sm btn-info"
										@click="getData()"
										>
										<i class="fas fa-sync-alt"></i>
									</button>
								</div>
							</div>

							<div class="modal fade" id="cashIncashOutModal">
								<div class="modal-dialog modal-xl">
									<div class="modal-content">
										<form @submit.prevent="createUpdateCashInCashOut()">
											<div class="modal-header">
												<h4 class="modal-title">{{ $t('cash_in') }} / {{ $t('cash_out') }} {{ $t('form') }}</h4>
												<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="row">
                                                    <div class="col-md-4">
														<div class="form-group">
															<label>{{ $t('date') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<VueDatePicker 
																v-model="cashInCashOutForm.date_time"
																:placeholder="$t('select_date')"
																:enable-time-picker="false"
																format="dd/MM/yyyy"
																text-input
																required
																>
															</VueDatePicker>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for="">{{ $t('transection_type') }}</label>
															<select v-model="cashInCashOutForm.type" class="form-control" required>
																<option :value="'in'">{{ $t('cash_in') }}</option>
																<option :value="'out'">{{ $t('cash_out') }}</option>
															</select>
														</div>
													</div>
                                                    <div class="col-md-4">
														<div class="form-group">
															<label for="">{{ $t('account_selection') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<select v-model="cashInCashOutForm.account_id" class="form-control" required>
                                                                <option value="">{{ $t('please_select') }}</option>
                                                                <option v-if="(accounts.length > 0)" v-for="account in accounts" :value="account.id">
																	<span v-if="account.bank_id">
																		{{ account.account_number }} - ({{ account.bank_info.name }}) = ({{ $numFormat(account.balance) }})
																	</span>
																	<span v-else>
																		{{ account.user_name }} = ({{ $numFormat(account.balance) }})
																	</span>
																</option>
                                                            </select>
														</div>
													</div>
                                                    <div class="col-md-4">
														<div class="form-group">
															<label>{{ $t('amount') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<input type="number" class="form-control" v-model="cashInCashOutForm.amount" required>
														</div>
													</div>
                                                    <div class="col-md-4">
														<div class="form-group">
															<label>{{ $t('note') }}</label>
															<textarea rows="1" class="form-control" :placeholder="$t('write_note_here')" v-model="cashInCashOutForm.note"></textarea>
														</div>
													</div>
                                                </div>
											</div>
											<div class="modal-footer justify-content-between">
												<button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
												<div v-if="cashInCashOutForm.id == ''">
													<button type="submit" :disabled="submitLoading" @click="(addAnother=false)" class="btn btn-success mr-1">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('post') }}
													</button>
													<button type="submit" :disabled="submitLoading" @click="(addAnother=true)" class="btn btn-success">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('post_and_add_another') }}
													</button>
												</div>
												<div v-else>
													<button type="submit" :disabled="submitLoading" @click="(addAnother=false)" class="btn btn-success mr-1">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('update_post') }}
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>

							<div class="modal fade" id="balanceTransferModal">
								<div class="modal-dialog modal-xl">
									<div class="modal-content">
										<form @submit.prevent="createUpdateBalanceTransfer()">
											<div class="modal-header">
												<h4 class="modal-title">{{ $t('balance_transfer') }} {{ $t('form') }}</h4>
												<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="row">
                                                    <div class="col-md-4">
														<div class="form-group">
															<label>{{ $t('date') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<VueDatePicker 
																v-model="balanceTransferForm.date_time"
																:placeholder="$t('select_date')"
																:enable-time-picker="false"
																format="dd/MM/yyyy"
																text-input
																required
																>
															</VueDatePicker>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for="">{{ $t('sender_account') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<select v-model="balanceTransferForm.sender_account_id" class="form-control" required>
                                                                <option value="">{{ $t('please_select') }}</option>
                                                                <option v-if="(accounts.length > 0)" v-for="account in accounts" :value="account.id">
																	<span v-if="account.bank_id">
																		{{ account.account_number }} - ({{ account.bank_info.name }}) = ({{ $numFormat(account.balance) }})
																	</span>
																	<span v-else>
																		{{ account.user_name }} = ({{ $numFormat(account.balance) }})
																	</span>
																</option>
                                                            </select>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for="">{{ $t('receiver_account') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<select v-model="balanceTransferForm.receiver_account_in" class="form-control" required>
                                                                <option value="">{{ $t('please_select') }}</option>
                                                                <option v-if="(accounts.length > 0)" v-for="account in accounts" :value="account.id">
																	<span v-if="account.bank_id">
																		{{ account.account_number }} - ({{ account.bank_info.name }}) = ({{ $numFormat(account.balance) }})
																	</span>
																	<span v-else>
																		{{ account.user_name }} = ({{ $numFormat(account.balance) }})
																	</span>
																</option>
                                                            </select>
														</div>
													</div>
                                                    <div class="col-md-4">
														<div class="form-group">
															<label>{{ $t('balance') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<input type="number" class="form-control" v-model="balanceTransferForm.amount" required>
														</div>
													</div>
                                                    <div class="col-md-4">
														<div class="form-group">
															<label>{{ $t('note') }}</label>
															<textarea rows="1" class="form-control" :placeholder="$t('write_note_here')" v-model="balanceTransferForm.note"></textarea>
														</div>
													</div>
                                                </div>
											</div>
											<div class="modal-footer justify-content-between">
												<button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
												<div>
													<button type="submit" :disabled="submitLoading" @click="(addAnother=false)" class="btn btn-success mr-1">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('post') }}
													</button>
													<button type="submit" :disabled="submitLoading" @click="(addAnother=true)" class="btn btn-success">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('post_and_add_another') }}
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>

							<div class="card-body table-responsive p-0">
								<table class="table table-striped text-center table-hover">
									<thead>
										<tr>
											<th style="width:5%">#</th>
                                            <th class="text-left">
                                                {{ $t('transection_details') }}
                                            </th>
                                            <th class="text-left">
                                                {{ $t('account_details') }}
                                            </th>
											<th>{{ $t('cash_in') }}</th>
											<th>{{ $t('cash_out') }}</th>
											<th>{{ $t('action') }}</th>
										</tr>
									</thead>
									<tbody>
										<tr v-if="(listLoading == true)">
											<td colspan="6">
												<div class="d-flex justify-content-center">
													<div class="spinner-border"></div>
												</div>
											</td>
										</tr>
										<tr v-if="(lists.length > 0)" v-for="(list, index) in lists">
											<td>{{ index + pagination.from }}</td>
                                            <td class="text-left">
                                                <small>
                                                    {{ $t('id') }} : <b>{{ list.encrypt }}</b><br>
                                                    {{ $t('date') }} : <b>{{ $getHumanDate(list.date_time) }}</b><br>
                                                    {{ $t('reason') }} : <b>{{ list.transactionable_type? $t(list.transactionable_type) :'---' }} {{ list.transactionable_id? '(' + list.transactionable_id + ')':'' }}</b><br>
                                                    {{ $t('posted_by') }} : <b>{{ list.created_by.first_name }}</b>
                                                </small>
                                            </td>
											<td class="text-left">
                                                <small>
													<b>{{ (list.account && list.account.bank_info )? list.account.bank_info.name : $t('cash_account') }} <span class="text-primary">({{ $numFormat(list.account.balance) }})</span></b><br>
													<span v-if="list.account && list.account.account_number">
														{{ $t('account_number') }} : <b>{{ list.account.account_number }}</b><br>
													</span>
													<span v-if="list.account && list.account.holder_name">
														{{ $t('holder_name') }} : <b>{{ list.account.holder_name }}</b><br>
													</span>
                                                    {{ $t('user_name') }} : <b>{{ list.account.user_name ? list.account.user_name:'---' }}</b><br>
													<span v-if="list.note">({{ list.note }})</span>
                                                </small>
                                            </td>
											<td><b class="text-success">{{ list.type=='in'? $numFormat(list.amount) :'---' }}</b></td>
											<td><b class="text-danger">{{ list.type=='out'? $numFormat(list.amount) :'---' }}</b></td>
											<td>
												<button
													v-if="list.transactionable_type == 'account_trans'"
													type="button" 
													@click="deleteModal(list.id, index)"
													class="btn btn-xs bg-gradient-danger" 
													>
													<i class="fas fa-exchange-alt"></i> {{ $t('roll_back') }}
												</button>
												<span v-else>---</span>
											</td>
										</tr>
										<tr v-if="(lists.length == 0)">
											<td colspan="6"><h5 class="text-danger">{{ $t('no_data') }}</h5></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="card-footer clearfix">
								<div class="row">
									<pagination
										v-model="pagination.currentPage"
										:records="pagination.total"
										:per-page="pagination.perPage" 
										@paginate="pagiAction"
									/>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</section>

		<CurrentBalanceModal ref="cBRef"/>

	</div>
</template>

<script>
import { useToast } from "vue-toastification";
import Swal from 'sweetalert2';
import Pagination from 'v-pagination-3';

import moment from 'moment';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

import CurrentBalanceModal from '../../components/commons/CurrentBalanceModal.vue';
import SearchFilterModal from '../../components/commons/SearchFilterModal.vue';

export default {
	components: {
		Pagination,
		CurrentBalanceModal,
		SearchFilterModal,
		VueDatePicker
	},
	data() {
		return {
			toast: useToast(),
			submitLoading: false,
			addAnother: false,
			listLoading: false,
			lists: {},
            accounts: {},
			settingBanks: {},

			// form
			cashInCashOutForm: {
				index: '',
				id: '',
				account_id: '',
				type: 'in',
				amount: 0,
				date_time: moment().format('YYYY-MM-DD'),
				note: '',
			},

			// form
			balanceTransferForm: {
				index: '',
				id: '',
				date_time: moment().format('YYYY-MM-DD'),
				sender_account_id: '',
				receiver_account_in: '',
				amount: 0,
				note: '',
			},

			// search
			pagination: {
				paginate: true,
				perPage: 20,
				orderBy: 'id',
				orderType: 'DESC',

				currentPage: 1,
				from: 1,
				total: 0,
			},
			rowNumber: 1,

			// search filters
			filterParams: {},
			searchOptions:[
				"perPage",
				"orderType",
				"bank",
                "transactionId",
			],
		}
	},
	created() {
		this.getData();
        this.getAccounts();
		this.getSettingBanks();
	},
	methods: {
		// common
		getData() {
			this.lists = {};
			this.listLoading = true;
			let params = { ...this.pagination, ...this.filterParams };
			axios.get('/api/account-transactions', { params })
				.then((response) => {
					this.lists = response.data.lists.data;
					this.pagination.currentPage = response.data.lists.current_page;
					this.pagination.from = response.data.lists.from;
					this.pagination.total = response.data.lists.total;
				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() =>{
					this.listLoading = false;
				});
		},
		pagiAction(number){
			this.pagination.currentPage = number;
			this.getData();
		},
		getAccounts(){
			axios.get('/api/common/accounts')
				.then((response) => {
					this.accounts = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
		getSettingBanks(){
            axios.get('/api/common/setting-banks')
				.then((response) => {
					this.settingBanks = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
		resetFilter(){
			this.filter.name = '';
		},
		
		// cash in cash out part
		cashIncashOutModal(list, index) {
			$("#cashIncashOutModal").modal("show");
		},
		createUpdateCashInCashOut(){
			this.submitLoading = true;

			var request;
			// update
			if(this.cashInCashOutForm.id){
				request = axios.put('/api/cash-in-cash-outs/' + this.cashInCashOutForm.id, this.cashInCashOutForm)
			// insert
			} else {
				request = axios.post('/api/cash-in-cash-outs', this.cashInCashOutForm)
			}

			request.then((response) => {

				// if update attempt
				if(this.cashInCashOutForm.id){
					// this.lists[this.cashInCashOutForm.index] = response.data.data;
					$("#cashIncashOutModal").modal("hide");
				// if create attempt
				} else {

					// this.lists.unshift(response.data.data);
					if(!this.addAnother){
						this.resetCashInCashOutForm();
						$("#cashIncashOutModal").modal("hide");
					}
				}

                this.getData();
                this.getAccounts();
				this.toast.success(this.$t(response.data.message));

			}).catch((error) => {
				this.toast.warning(error.message);
			}).finally(() => {
				this.submitLoading = false;
			});
		},
		resetCashInCashOutForm() {
			this.cashInCashOutForm.index = '';
			this.cashInCashOutForm.id = '';
			this.cashInCashOutForm.account_id = '';
			this.cashInCashOutForm.type = 'in';
			this.cashInCashOutForm.amount = 0;
			this.cashInCashOutForm.date_time = moment().format('YYYY-MM-DD');
			this.cashInCashOutForm.note = '';
		},

		// balance transfer
		balanceTransferModal(list, index){
			$("#balanceTransferModal").modal("show");
		},
		createUpdateBalanceTransfer(){
			this.submitLoading = true;

			axios
				.post('/api/balance-transfers', this.balanceTransferForm)
				.then((response) => {

					// let lists = this.lists;

					// response.data.data.forEach(function(singleData){
					// 	lists.unshift(singleData);
					// }, lists);
					
					// this.lists = lists;
					if(!this.addAnother){
						this.resetBalanceTransferForm();
						$("#balanceTransferModal").modal("hide");
					}

					this.getData();
                	this.getAccounts();
					this.toast.success(this.$t(response.data.message));

				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() => {
					this.submitLoading = false;
				});
		},
		resetBalanceTransferForm() {
			this.balanceTransferForm.index = '';
			this.balanceTransferForm.id = '';
			this.balanceTransferForm.date_time = moment().format('YYYY-MM-DD');
			this.balanceTransferForm.sender_account_id = '';
			this.balanceTransferForm.receiver_account_in = '';
			this.balanceTransferForm.amount = 0;
			this.balanceTransferForm.note = '';
		},
		// delete
		deleteModal(id, index) {
			Swal.fire({
				title: this.$t('are_you_sure'),
				text: this.$t('you_wont_be_able_to_revert_this'),
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: this.$t('yes'),
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
				let response = await axios.delete('/api/account-transaction-destroy/' + id);
				this.getData();
				this.getAccounts();

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

		// show modal
		// showCurrentBalanceModal(){
		// 	this.$refs.cBRef.getBalance();
		// 	this.$refs.cBRef.getTransection();
		// 	$("#showCurrentBalanceModal").modal("show");
		// },
		showSearchFilterModal(){
			$("#showSearchFilterModal").modal("show");
		},
		setSearchFilterParams(params){
			// set pagination
			this.pagination.perPage = params.perPage
			this.pagination.orderType = params.orderType

			// set filter
			this.filterParams = params;
			this.lists = {};
			this.getData();
		},
	},
	computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
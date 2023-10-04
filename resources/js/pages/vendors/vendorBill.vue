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
									:vehicles="vehicles"
									:clients="clients"
									:settingExpenses="settingExpenses"
									:vendors="vendors"
									
									:searchOptions="searchOptions"
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

										<!-- <button
											:disabled="items.length == 0"
											type="button" 
											class="btn btn-sm bg-gradient-info mr-1"
											:title="$t('make_payment')"
											@click="showLoanPaymentModal()"
											>
											{{ $t('make_payment') }}
										</button> -->
									</div>
							</div>
							<div class="card-body table-responsive p-0">
								<table class="table table-striped text-center table-hover">
									<thead>
										<tr>
											<th class="text-left">#</th>
											<th class="text-left">{{ $t('info') }}</th>
											<th class="text-left">{{ $t('payment_method') }}</th>
											<th class="text-left">{{ $t('vehicle') }}</th>
											<th class="text-left">{{ $t('vendor') }}</th>

											<th class="text-left">{{ $t('date') }}</th>
											<th class="text-left">{{ $t('expense_head') }}</th> <!-- note -->

											<th class="text-right">{{ $t('amount') }}</th>
											<th>{{ $t('action') }}</th>
										</tr>
									</thead>
									<tbody>
										<tr v-if="(listLoading == true)">
											<td colspan="9">
												<div class="d-flex justify-content-center">
													<div class="spinner-border"></div>
												</div>
											</td>
										</tr>
										<tr v-if="(lists.length > 0)" v-for="(list, index) in lists">
											<td class="text-left">
												<!-- <input
													v-if="list.loan && (list.loan.amount > list.loan_paid_sum)"
													type="checkbox" 
													@click="singleCheckbox(list)"
													> -->
											</td>
											<td class="text-left">
												<small>
													{{ $t('id') }} : <b>{{ list.id }}</b><br>
													{{ $t('post_') }} : <b>{{ list.created_info.first_name }}</b><br>
												</small>
											</td>
											<td class="text-left">
												<small v-if="list.transaction && list.transaction.account">
													<b>{{ (list.transaction && list.transaction.account && list.transaction.account.bank_info )? list.transaction.account.bank_info.name : $t('cash_account') }}</b><br>
													<span v-if="list.transaction && list.transaction.account && list.transaction.account.account_number">
														{{ $t('account_number') }} : <b>{{ list.transaction.account.account_number }}</b><br>
													</span>
													<span v-if="list.transaction && list.transaction.account && list.transaction.account.holder_name">
														{{ $t('holder_name') }} : <b>{{ list.transaction.account.holder_name }}</b><br>
													</span>
													{{ $t('user_name') }} : <b>{{ (list.transaction  && list.transaction.account.user_name )? list.transaction.account.user_name :'---' }}</b><br> 
													{{ $t('transaction_id') }} : <b>{{ list.transaction? list.transaction.encrypt :'---' }}</b><br> 
												</small>
												<small v-if="list.loan">
													{{ $t('vendor') }} : <b>{{ list.vendor_id?list.vendor_info.name:'---' }}</b><br>
													<span>{{ $t('loan_id') }} : <b :class="(list.loan_paid_sum == list.loan.amount)?'text-success':'text-danger'">{{ list.loan? list.loan.encrypt :'---' }}</b></span><br>
													<span>{{ $t('loan_paid') }} : <b :class="(list.loan_paid_sum == list.loan.amount)?'text-success':'text-danger'">{{ $numFormat(list.loan_paid_sum) }}</b></span>
												</small>
												<small v-if="!list.loan && !list.transaction">
													<b>---</b>
												</small>
											</td>
											<td class="text-left">
												<small>
													{{ $t('vehicle') }} : <b>{{ list.vehicle_id?list.vehicle_info.number_plate:'---' }}</b><br> 
													{{ $t('challan_number') }} : <b>{{ list.challan_id?list.challan_info.challan_number:'---' }}</b>
												</small>
											</td>
											<td class="text-left">
												<small>
													{{ $t('vendor') }} : <b>{{ list.vendor_id?list.vendor_info.name:'---' }}</b><br> 
													{{ $t('voucher_number') }} : <b>{{ list.voucher_number?list.voucher_number:'---' }}</b>
												</small>
											</td>
											
											<td class="text-left">
												<small><b>{{ $getHumanDate(list.date_time) }}</b></small>
											</td>
											<td class="text-left">
												<small><b>{{ list.expense_info.name }}</b></small>
											</td>
											<td class="text-right">
												<b>{{ $numFormat(list.amount) }}</b>
											</td>
											<td>
												<button
													v-if="list.loan && (list.loan.amount > list.loan_paid_sum)"
													type="button" 
													class="btn btn-xs bg-gradient-info mr-1"
													:title="$t('make_payment')"
													@click="showSingleLoanPaymentModal(list.loan.id, index, true)"
													>
													{{ $t('make_payment') }}
												</button>
												<button
													v-if="list.loan && (list.loan.amount <= list.loan_paid_sum)"
													type="button" 
													class="btn btn-xs bg-gradient-info mr-1"
													:title="$t('payment_history')"
													@click="showSingleLoanPaymentModal(list.loan.id, index, false)"
													>
													{{ $t('payment_history') }}
												</button>
												<!-- <button
													type="button" 
													class="btn btn-xs bg-gradient-primary mr-1"
													:title="$t('edit')"
													data-toggle="modal" 
													data-target="#edit"
													@click="showGeneralExpenseModal(index, list)"
													>
													<i class="fas fa-edit"></i>
												</button> -->
												<!-- <button
													type="button" 
													@click="deleteModal(list.id, index)"
													class="btn btn-xs bg-gradient-danger" 
													:title="$t('delete')"
													>
													<i class="fas fa-trash"></i>
												</button> -->
											</td>
										</tr>
										<tr v-if="(lists.length == 0)">
											<td colspan="9"><h5 class="text-danger">{{ $t('no_data') }}</h5></td>
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

		<SingleLoanPaymentModal
			ref="singleLoanPaymentModalRef"
			@afterAdd="afterSingleLoanPaid"
			@afterDelete="afterDelete"

			:accounts="accounts"
			/>
	</div>
</template>

<script>
import { useToast } from "vue-toastification";
import Swal from 'sweetalert2';
import Pagination from 'v-pagination-3';

import SingleLoanPaymentModal from '../../components/commons/SingleLoanPaymentModal.vue';
import SearchFilterModal from '../../components/commons/SearchFilterModal.vue';
import moment from 'moment';

export default {
	components: {
		Pagination,
		SearchFilterModal,
		SingleLoanPaymentModal
	},
	data() {
		return {
			toast: useToast(),

			// list
			submitLoading: false,
			listLoading: false,
			lists: {},

			// search
			pagination: {
				paginate: true,
				perPage: 50,
				orderBy: 'id',
				orderType: 'DESC',

				currentPage: 1,
				from: 1,
				total: 0,
			},
			rowNumber: 1,

			// component
			challanId: 0,
			challanClientId: 0,
			componentData: {},
			indexId: '',

			// common data
			accounts: {},
            vendors: {},
            settingExpenses: {},

			// search filters
			filterParams: {
				vendor_type: 'other',
				loanCalculation: 'true'
			},
			vehicles: {},
            clients: {},
			searchOptions:[
				"perPage",
				"orderType",
                "timeRange",
                "challanNumber",
				"voucherNumber",
				"transactionId",
				"expense",
                "ownVehicle",
                "vendor",
				"paidStatus",
	
			],
			items: []
		}
	},
	created() {
		this.getData();

		// common data
		this.getVendors();
		this.getAccounts();
		this.getSettingExpenses();

		// search filters
		this.getVehicles();
		this.getClients();
	},
	methods: {
		getData() {
			this.listLoading = true;
			let params = { ...this.pagination, ...this.filterParams };
			axios.get('/api/expense-generals', { params })
				.then((response) => {
					this.lists = response.data.lists.data;
					this.pagination.currentPage = response.data.lists.current_page;
					this.pagination.from = response.data.lists.from;
					this.pagination.total = response.data.lists.total;
				}).catch((error) => {
					// this.toast.warning(error.message);
					this.toast.error(this.$t(error.response.data.message));
				}).finally(() =>{
					this.listLoading = false;
				});
		},
		// deleteModal(id, index) {
		// 	Swal.fire({
		// 		title: this.$t('are_you_sure'),
		// 		text: this.$t('you_wont_be_able_to_revert_this'),
		// 		icon: 'question',
		// 		showCancelButton: true,
		// 		confirmButtonColor: '#3085d6',
		// 		cancelButtonColor: '#d33',
		// 		confirmButtonText: this.$t('yes_delete_it'),
		// 		cancelButtonText: this.$t('cancel'),
		// 	}).then((result) => {
		// 		if (result.isConfirmed) {
		// 			this.destroy(id, index);
		// 		}
		// 	})
		// },
		// async destroy(id, index) {
		// 	try {
		// 		// show loading
		// 		Swal.fire({
		// 			title: this.$t('operation_running'),
		// 			allowOutsideClick: false,
		// 			allowEscapeKey: false,
		// 			allowEnterKey: false,
		// 			didOpen: () => {
		// 				Swal.showLoading();
		// 			}
		// 		})
		// 		// fire request
		// 		await axios.delete('/api/expense-generals/' + id);
        //         this.lists.splice(index, 1);
		// 		this.pagination.total = this.pagination.total - 1;
		// 		this.getAccounts();

		// 		Swal.close();
		// 		Swal.fire({
		// 			position: 'center',
		// 			icon: 'success',
		// 			title: this.$t('successfully_deleted'),
		// 			showConfirmButton: false,
		// 			timer: 1000
		// 		})

		// 	} catch (error) {
		// 		Swal.close();
		// 		Swal.fire({
		// 			position: 'center',
		// 			icon: 'error',
		// 			title: this.$t('something_went_wrong'),
		// 			showConfirmButton: false,
		// 			timer: 1000
		// 		})
		// 	}
		// },
		pagiAction(number){
			this.pagination.currentPage = number;
			this.getData();
		},
		// show modal
		// showGeneralExpenseModal(indexId = null, list = null){
		// 	this.indexId = indexId;

		// 	// reset
		// 	this.$refs.gERef.resetForm();

		// 	if(list){
		// 		// set expense modal form data
		// 		this.$refs.gERef.form.id = list.id;
		// 		this.$refs.gERef.form.date_time = list.date_time?moment(list.date_time).format('YYYY-MM-DD'):moment().format('YYYY-MM-DD');
		// 		this.$refs.gERef.form.vendor_id = list.vendor_id?list.vendor_id:'';
		// 		this.$refs.gERef.form.account_id = list.transaction.account_id;

		// 		this.$refs.gERef.form.vehicle_id = list.vehicle_id?list.vehicle_id:'';
		// 		this.$refs.gERef.form.challan_id = list.challan_id?list.challan_id:'';

		// 		this.$refs.gERef.form.expense_id = list.expense_id?list.expense_id:'';
		// 		this.$refs.gERef.form.voucher_number = list.voucher_number?list.voucher_number:'';
		// 		this.$refs.gERef.form.amount = list.amount?list.amount:'';
		// 		this.$refs.gERef.form.note = list.note?list.note:'';
		// 	}

		// 	$("#showGeneralExpenseModal").modal("show");
		// },
		// refresh
		refreshGeneralExpense(){
			this.getData();
			this.getAccounts();
		},
		// common data
		getVendors(){
			axios.get('/api/common/vendors')
				.then((response) => {
					this.vendors = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
        getAccounts(){
			axios.get('/api/common/accounts')
				.then((response) => {
					this.accounts = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
		getSettingExpenses(){
            axios.get('/api/common/setting-expenses')
				.then((response) => {
					this.settingExpenses = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
		// search filters
		getVehicles() {
			axios.get('/api/common/vehicles')
				.then((response) => {
					this.vehicles = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
		getClients() {
            axios.get('/api/common/clients')
				.then((response) => {
					this.clients = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
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
		showSearchFilterModal(){
			$("#showSearchFilterModal").modal("show");
		},
		showSingleLoanPaymentModal(loanId, indexId, showForm){
			this.$refs.singleLoanPaymentModalRef.resetForm();
			this.$refs.singleLoanPaymentModalRef.showForm = showForm;
			this.$refs.singleLoanPaymentModalRef.form.loan_id = loanId;
			this.$refs.singleLoanPaymentModalRef.form.index_id = indexId;
			this.$refs.singleLoanPaymentModalRef.getLists();
			$("#showSingleLoanPaymentModal").modal("show");
		},
		afterSingleLoanPaid(data){
			// update loan transections
			// this.lists[data.indexId].loan.transactions.push(data.data);
			// update loan transections more accuratly
			this.$refs.singleLoanPaymentModalRef.getLists();
			// update loan paid sum
			this.lists[data.indexId].loan_paid_sum += data.data.amount;
			this.getAccounts();
		},
		afterDelete(data){
			this.lists[data.indexId].loan_paid_sum -= data.amount;
			this.getAccounts();
		},
		


		// singleCheckbox(data){
		// 	const customData = { 'id': data.id };

		// 	if(this.items.includes(customData)){
		// 		this.items.splice(this.items.indexOf(customData), 1);
		// 	} else {
		// 		this.items.push(customData);
		// 	}

		// 	console.log(this.items);

		// 	if(this.items.includes(data)){
		// 		this.items.splice(this.items.indexOf(data), 1);
		// 	} else {
		// 		this.items.push(data);
		// 	}
		// }
	},
	computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
<style>
	@media (max-width: 767px) {
		.table-responsive .dropdown-menu {
			position: static !important;
		}
	}
	@media (min-width: 768px) {
		.table-responsive {
			overflow: inherit;
		}
	}
</style>
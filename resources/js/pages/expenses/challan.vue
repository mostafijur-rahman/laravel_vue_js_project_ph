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
							</div>

							<div class="card-body table-responsive p-0">
								<table class="table table-striped text-center table-hover">
									<thead>
										<tr>
											<th class="text-left">#</th>
											<th class="text-left">{{ $t('payment_method') }}</th>
											<th class="text-left">{{ $t('vehicle') }}</th>
											<th class="text-left">{{ $t('vendor') }}</th>

											<th class="text-left">{{ $t('date') }}</th>
											<th class="text-left">{{ $t('expense_head') }}</th> <!-- note -->

											<th class="text-right">{{ $t('amount') }}</th>
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
										<tr v-if="(lists.length > 0)" v-for="(list, index) in lists">
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
													<span class="text-danger">{{ $t('loan_id') }} : <b>{{ list.loan? list.loan.encrypt :'---' }}</b></span>
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
										</tr>
										<tr v-if="(lists.length == 0)">
											<td colspan="8"><h5 class="text-danger">{{ $t('no_data') }}</h5></td>
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

	</div>
</template>

<script>

import { useToast } from "vue-toastification";
import Pagination from 'v-pagination-3';

import SearchFilterModal from '../../components/commons/SearchFilterModal.vue';

export default {
	components: {
		Pagination,
		SearchFilterModal
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
				onlyChallan: true
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
			filterParams: {},
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
			]
		}
	},
	created() {
		this.getData();

		// search filters
		this.getVehicles();
		this.getClients();
		this.getSettingExpenses();
		this.getVendors();

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
					this.toast.warning(error.message);
				}).finally(() =>{
					this.listLoading = false;
				});
		},
		pagiAction(number){
			this.pagination.currentPage = number;
			this.getData();
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
		getSettingExpenses(){
            axios.get('/api/common/setting-expenses')
				.then((response) => {
					this.settingExpenses = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
		getVendors(){
			axios.get('/api/common/vendors')
				.then((response) => {
					this.vendors = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
		showSearchFilterModal(){
			$("#showSearchFilterModal").modal("show");
		},
		setSearchFilterParams(params){
			// set pagination
			this.pagination.perPage = params.per_page
			this.pagination.orderType = params.order_by

			// set filter
			this.filterParams = params;
			this.lists = {};
			this.getData();
		},
	}
}
</script>
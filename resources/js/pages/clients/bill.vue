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
								</div>
							</div>
							<div class="card-body table-responsive p-0">
								<table class="table table-striped text-center table-hover">
									<thead>
										<tr>
											<th class="text-left">#</th>
											<th class="text-left">{{ $t('client') }}</th>
											<th class="text-left">{{ $t('date') }}</th>
											<th class="text-left">{{ $t('reason') }}</th>
											<th class="text-left">{{ $t('details') }}</th>
											<th class="text-right">{{ $t('amount') }}</th>
										</tr>
									</thead>
									<tbody>
										<tr v-if="(listLoading == true)">
											<td colspan="11">
												<div class="d-flex justify-content-center">
													<div class="spinner-border"></div>
												</div>
											</td>
										</tr>
										<tr v-if="(lists.length > 0)" v-for="(list, index) in lists">
											<td class="text-left">
												<small><b>{{ index + pagination.from }}</b></small>
											</td>
											<td class="text-left">
												<small><b>{{ list.client_info ? list.client_info.name :'---' }}</b></small>
											</td>
											<td class="text-left">
												<small>
													<b>{{ $getHumanDate(list.date_time) }}</b><br>
													<span v-if="list.note">
														{{ $t('note') }} : {{ list.note }}<br>
													</span>
												</small>
											</td>
											<td class="text-left">
												<span v-if="list.purposeable_type == 'challans'">
													<small>
														{{ $t('challan_number') }} : {{ list.purposeable.challan_number }}<br>
														{{ $t('vehicle_number') }} : {{ list.purposeable.rental_vehicle? list.purposeable.rental_vehicle.vehicle_number : '---' }}
													</small>
												</span>
											</td>
											<td class="text-left">
												<small>
													{{ $t('voucher_number') }} : {{ list.voucher_number??'---' }}<br>
													{{ $t('bill_type') }} : <b>{{ $t(list.bill_type) }}</b>
												</small>
											</td>
											<td class="text-right">
												<small><b>{{ $numFormat(list.amount) }}</b></small>
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
                "rentalVehicle",
                "ownVehicle",
                "client"
			]
			// "paidStatus",
		}
	},
	created() {
		this.getData();
		this.getVehicles();
		this.getClients();
	},
	methods: {
		getData() {
			this.listLoading = true;
			let params = { ...this.pagination, ...this.filterParams };
			axios.get('/api/client-bills', { params })
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
		pagiAction(number){
			this.pagination.currentPage = number;
			this.getData();
		},
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
	},

}
</script>
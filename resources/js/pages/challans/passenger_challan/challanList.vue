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
										<button 
											type="button" 
											class="btn btn-sm btn-success mr-1"
											@click="showPassengerChallanModal"
											>
											<i class="fa fa-plus"></i> {{ $t('new_challan') }}
										</button>
									</div>
							</div>

							<div class="card-body table-responsive p-0">
								<table class="table text-center table-bordered text-nowrap">
									<thead>
										<tr>
											<th style="width:5%">#</th>
											<th>{{ $t('primary_info') }}</th>
											<th>{{ $t('going') }}</th>
											<th>{{ $t('incoming') }}</th>
											<th>{{ $t('account_summary') }}</th>
										</tr>
									</thead>
									<tbody>
										<tr v-if="(listLoading == true)">
											<td colspan="7">
												<div class="d-flex justify-content-center">
													<div class="spinner-border"></div>
												</div>
											</td>
										</tr>
										<tr v-if="(lists.length > 0)" v-for="(list, index) in lists">
											<td>
												<b>{{ index + pagination.from }}</b>
											</td>
											<td class="text-left">
												<small>
													{{ $t('challan_number') }} : <b>{{ list.challan_number }}</b><br>
													{{ $t('posted_by') }} : <br>
													<b>{{ list.created_info.first_name }} {{ list.created_at? '(' + $getHumanDateTime(list.created_at) + ')' :'' }}</b>
													<span v-if="list.updated_by">
														<br>
														{{ $t('updated_by') }} : <br>
														<b>{{ list.updated_info.first_name }} {{ list.updated_at? '(' + $getHumanDateTime(list.updated_at) + ')' :'' }}</b>
													</span>
												</small>
												<div class="dropdown">
													<button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														{{ $t('action') }}
													</button>
													<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
														<a :href="'/api/deposit-single-challan?encrypt=' + list.encrypt + '&size=Legal-L&page_header=all_info_in_header&output=stream'" target="_blank" :title="$t('print')" class="dropdown-item btn-sm"><i class="fa fa-print"></i> {{ $t('print') }}</a>
														<a :href="'/api/deposit-single-challan?encrypt=' + list.encrypt  + '&size=Legal-L&page_header=all_info_in_header&output=download'" :title="$t('download')" class="dropdown-item btn-sm"><i class="fa fa-download"></i> {{ $t('download') }}</a>
														<div class="dropdown-divider"></div>
														<button
															type="button"
															class="dropdown-item btn-sm"
															title="{{ $t('delete') }}"
															@click="deleteModal(list.id, index)"
															>
															<i class="fa fa-trash"></i> <b>{{ $t('delete') }}</b>
														</button>									
													</div>
												</div>
											</td>
											<td class="text-left">
												<small>
													{{ $t('date') }} : <b>{{ list.start_date_time? $getHumanDate(list.start_date_time) :'---' }}</b><br>
													{{ $t('coach_no') }} : <b>{{ list.coach_number?list.coach_number:'---' }}</b><br>
													{{ $t('vehicle') }} : <b>{{ list.number_plate?list.number_plate:'---' }}</b><br>
													{{ $t('driver') }} : <b>{{ list.driver_name?list.driver_name:'---' }} {{ list.driver_phone?'('+list.driver_phone+')':'' }}</b><br>
													{{ $t('guide') }} : <b>{{ list.guide_name?list.guide_name:'---' }} {{ list.guide_phone?'('+list.guide_phone+')':'' }}</b><br>
													{{ $t('helper') }} : <b>{{ list.helper_name?list.helper_name:'---' }} {{ list.helper_phone?'('+list.helper_phone+')':'' }}</b><br>
													{{ $t('reach_time') }} : <b>{{ list.destination_reach_date_time? $getHumanDate(list.destination_reach_date_time) :'---' }}</b><br>
													{{ $t('passenger_qty') }} : <b>{{ $numFormat(0) }}</b><br>
													{{ $t('total_deposit') }} : <b>{{ $numFormat(0) }}</b>
												</small>
												<br>
												<button
													type="button" 
													class="btn btn-xs bg-gradient-primary mr-1"
													:title="$t('edit')"
													@click="showPassengerChallanModal()"
													>
													<i class="fa fa-edit"></i>
												</button>
												<button
													type="button" 
													class="btn btn-xs bg-gradient-primary"
													:title="$t('oil_expense')"
													@click="showCounterBookingModal(list.id)"
													>
													<i class="fa fa-users"></i> {{ $t('counter_booking') }}
												</button>
											</td>
											<td class="text-left">
												<small>
													{{ $t('date') }} : <b>{{ list.start_date_time? $getHumanDate(list.start_date_time) :'---' }}</b><br>
													{{ $t('coach_no') }} : <b>{{ list.coach_number?list.coach_number:'---' }}</b><br>
													{{ $t('vehicle') }} : <b>{{ list.number_plate?list.number_plate:'---' }}</b><br>
													{{ $t('driver') }} : <b>{{ list.driver_name?list.driver_name:'---' }} {{ list.driver_phone?'('+list.driver_phone+')':'' }}</b><br>
													{{ $t('guide') }} : <b>{{ list.guide_name?list.guide_name:'---' }} {{ list.guide_phone?'('+list.guide_phone+')':'' }}</b><br>
													{{ $t('helper') }} : <b>{{ list.helper_name?list.helper_name:'---' }} {{ list.helper_phone?'('+list.helper_phone+')':'' }}</b><br>
													{{ $t('reach_time') }} : <b>{{ list.destination_reach_date_time? $getHumanDate(list.destination_reach_date_time) :'---' }}</b><br>
													{{ $t('passenger_qty') }} : <b>{{ $numFormat(0) }}</b><br>
													{{ $t('total_deposit') }} : <b>{{ $numFormat(0) }}</b>
												</small>
												<br>
												<button
													type="button" 
													class="btn btn-xs bg-gradient-primary mr-1"
													:title="$t('edit')"
													@click="showPassengerChallanModal()"
													>
													<i class="fa fa-edit"></i>
												</button>
												<button
													type="button" 
													class="btn btn-xs bg-gradient-primary"
													:title="$t('oil_expense')"
													@click="showCounterBookingModal(list.id)"
													>
													<i class="fa fa-users"></i> {{ $t('counter_booking') }}
												</button>
											</td>
                							<td class="text-right">
												<small v-if="list.general_expenses.length > 0 || list.oil_expenses.length > 0">
													<div style="border-bottom: 2px dashed grey; margin-left: 16px;">

														<p style="margin: 0px;  padding: 0px;"
															v-if="(list.oil_expenses.length > 0)" 
															v-for="oil_expense in list.oil_expenses">
															{{ $t('fuel') }} ( {{ $t('liter') }} = {{ $numFormat(oil_expense.liter) }}, {{ $t('rate') }} = {{ $numFormat(oil_expense.rate) }}) = <b>{{ $numFormat(oil_expense.amount) }}</b>
														</p>

														<p style="margin: 0px;  padding: 0px;"
															v-if="(list.general_expenses.length > 0)" 
															v-for="generalExpense in list.general_expenses">
															{{ generalExpense.expense_info.name }} = <b>{{ $numFormat(generalExpense.amount) }}</b>
														</p>
													</div>
													{{ $t('total_expense') }} = <b>{{ $numFormat($getSumFromLists(list.general_expenses, 'amount') + $getSumFromLists(list.oil_expenses, 'amount')) }}</b>
												</small>
												<small v-else>{{ $t('no_expense') }}</small>
												<br>

												<small>
													<div style="border-bottom: 2px dashed grey; margin-left: 16px;">
														{{ $t('total_deposit') }} = <b>0</b>
													</div>
													{{ $t('balance') }} = <b>0</b>
												</small>
												<br>
												<button
													type="button" 
													class="btn btn-xs bg-gradient-primary mr-1"
													:title="$t('general_expense')"
													@click="showGeneralExpenseModal(list.id, list.vehicle_id, index)"
													>
													<i class="fa fa-plus"></i> {{ $t('general_expense') }}
												</button>
												<button
													type="button" 
													class="btn btn-xs bg-gradient-primary"
													:title="$t('oil_expense')"
													@click="showOilExpenseModal(list.id, list.vehicle_id, index)"
													>
													<i class="fa fa-gas-pump"></i> {{ $t('oil_expense') }}
												</button>
											</td>
										</tr>
										<tr v-if="(lists.length == 0)">
											<td colspan="7"><h5 class="text-danger">{{ $t('no_data') }}</h5></td>
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

		<GeneralExpenseModal 
			ref="gERef"
			:accounts="accounts"
            :vendors="vendors"
            vendorType="other"
            :settingExpenses="settingExpenses"

			@refreshGeneralExpense="refreshGeneralExpense"
			@addExpense="addExpense"
			@addVendor="addVendor"
			/>

		<OilExpenseModal 
			ref="oERef"
			:accounts="accounts"
            :vendors="pumpVendors"
			vendorType="pump"

			@refreshOilExpense="refreshOilExpense"
			@addVendor="addPumpVendor"
			/>

		<PassengerChallanModal 
			ref="passengerChallanModalRef"
			:vehicles="vehicles"
			:drivers="drivers"
			:guides="guides"
			:helpers="helpers"

			@afterAdd="getData"
		/>

		<CounterBookingModal 
			ref="counterBookingModalRef"
			:counters="counters"

			@afterAdd="getData"
		/>

	</div>
</template>

<script>

import { useToast } from "vue-toastification";
import Swal from 'sweetalert2';
import Pagination from 'v-pagination-3';
import moment from 'moment';

import GeneralExpenseModal from '../../../components/commons/GeneralExpenseModal.vue';
import OilExpenseModal from '../../../components/commons/OilExpenseModal.vue';
import SearchFilterModal from '../../../components/commons/SearchFilterModal.vue';

// new modal
import PassengerChallanModal from '../../../components/commons/PassengerChallanModal.vue';
import CounterBookingModal from '../../../components/commons/CounterBookingModal.vue';


export default {
	components: {
		Pagination,
		GeneralExpenseModal,
		OilExpenseModal,
		SearchFilterModal,
		PassengerChallanModal,
		CounterBookingModal
	},
	data() {
		return {
			toast: useToast(),

			// form
			form: {
				id: '',
				name: '',
				status: 'active',
			},
			add_another: false,

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

			submitLoading: false,
			submitBtnName: 'post',
			pageType: 'create',

			// component
			challanId: 0,
			challanClientId: 0,
			componentData: {},
			indexId: '',

			// common data
			vehicles: [],
            drivers: [],
            guides: [],
            helpers: [],
			accounts: {},
            vendors: [],
            pumpVendors: [],
            settingExpenses: [],
            counters: [
				'counter_1', 
				'counter_2', 
				'counter_3', 
				'counter_4', 
				'counter_5'
			],
			// search filters
			filterParams: {},

			searchOptions:[
				"perPage",
				"orderType",
                "timeRange",
                "challanNumber",
                "ownVehicle"
			],
		}
	},
	created() {
		this.getData();

		this.getVehicles();
		this.getDrivers();
        this.getHelpers();
        this.getGuides();
		this.getVendors();
		this.getPumpVendors();
		this.getAccounts();
		this.getSettingExpenses();
	},
	methods: {
		getData() {
			this.listLoading = true;
			let params = { ...this.pagination, ...this.filterParams };
			axios.get('/api/passenger-challans', { params })
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
				let response = await axios.delete('/api/deposit-challans/' + id);
				this.lists.splice(index, 1);
				this.pagination.total = this.pagination.total - 1;
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
		pagiAction(number){
			this.pagination.currentPage = number;
			this.getData();
		},

		// show modal
		showGeneralExpenseModal(challanId, vehicleId, indexId){
			this.indexId = indexId;
			this.$refs.gERef.form.vehicle_id = {'id' : vehicleId } ;
			this.$refs.gERef.getGeneralExpenses({'challanId': challanId});

			$("#showGeneralExpenseModal").modal("show");
		},
		showOilExpenseModal(challanId, vehicleId, indexId){
			this.indexId = indexId;
			this.$refs.oERef.form.vehicle_id =  {'id' : vehicleId } ;
			this.$refs.oERef.getOilExpenses({'challanId': challanId});
			$("#showOilExpenseModal").modal("show");
		},
		showPassengerChallanModal(){
			$("#showPassengerChallanModal").modal("show");
		},
		showCounterBookingModal(challanId, indexId){
			this.indexId = indexId;
			this.$refs.counterBookingModalRef.getData({'challanId': challanId});
			$("#showCounterBookingModal").modal("show");
		},

		// refresh
		refreshGeneralExpense(challanId){
			let params = { 
				'challan_id': challanId,
				'paginate': false
			 };

			axios.get('/api/expense-generals', { params })
				.then((response) => {

					let filterParams = { 'challan_id' : challanId, 'response' : response }; 

					// update in challan list
					this.lists.filter(
						function(list, index, array){
							if(list.id == filterParams.challan_id){
								list.general_expenses = filterParams.response.data.lists;
							}
						}, filterParams
					)

					// update in expense modal
					this.$refs.gERef.getGeneralExpenses({'challanId': challanId});

				}).catch((error) => {
					this.toast.warning(error.message);
				})

			this.getAccounts();
		},
		refreshOilExpense(challanId){
			let params = { 
				'challan_id': challanId,
				'paginate': false
			 };

			axios.get('/api/expense-oils', { params })
				.then((response) => {

					let filterParams = { 'challan_id' : challanId, 'response' : response }; 

					// update in challan list
					this.lists.filter(
						function(list, index, array){
							if(list.id == filterParams.challan_id){
								list.oil_expenses = filterParams.response.data.lists;
							}
						}, filterParams
					)

					// update in expense modal
					this.$refs.oERef.getOilExpenses({'challanId': challanId});

				}).catch((error) => {
					this.toast.warning(error.message);
				})

			this.getAccounts();
		},

		// common data
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
		getGuides() {
            let params = { designation_id: 4 };
            axios.get('/api/common/staffs', { params })
				.then((response) => {
					this.guides = response.data.lists;
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
		getVendors(){
            let params = { type: 'other' };
			axios.get('/api/common/vendors', { params })
				.then((response) => {
					this.vendors = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
		getPumpVendors(){
            let params = { type: 'pump' };
			axios.get('/api/common/vendors', { params })
				.then((response) => {
					this.pumpVendors = response.data.lists;
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
		singleSync(challanId){
			axios.get('/api/deposit-challans/' + challanId)
				.then((response) => {

					var challanId = response.data.data.id;
					var listIndex =  this.lists.findIndex(element => element.id == challanId, challanId);
					this.lists[listIndex] = response.data.data;
					
				}).catch((error) => {
					this.toast.warning(error.message);
				});
			this.getAccounts();
		},


		addExpense(value){
			this.settingExpenses.push(value)
		},
		addVendor(value){
			this.vendors.push(value)
		},
		addPumpVendor(value){
			this.pumpVendors.push(value)
		},

	},
	computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
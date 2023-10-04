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
										<router-link
											to="/commission-challans/create"
											class="btn btn-sm btn-success mr-1"
											>
											<i class="fa fa-plus"></i> {{ $t('create') }}
										</router-link >
										<!-- <button 
											type="button" 
											class="btn btn-sm btn-primary mr-1"
											@click="openUrl"
											>
											<i class="fa fa-search"></i> Badhon
										</button> -->
									</div>
							</div>
							<div class="card-body table-responsive p-0">
								<table class="table text-center table-bordered text-nowrap">
									<thead>
										<tr>
											<th style="width:5%">#</th>
											<th class="text-left">{{ $t('primary_info') }}</th>
											<th class="text-right">{{ $t('transection_with_vehicle_supplier') }}</th>
											<th class="text-right">{{ $t('transection_with_company') }}</th>
											<th class="text-right">{{ $t('commission') }}</th>
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
													{{ $t('start_date') }} : <b>{{ list.start_date_time? $getHumanDateTime(list.start_date_time) :'---' }}</b><br>
													<span v-if="list.rental_vehicle && list.rental_vehicle.vehicle_number">
														{{ $t('vehicle') }} : <b>{{ list.rental_vehicle.vehicle_number }}</b><br>
													</span>
													<span v-if="list.rental_vehicle && list.rental_vehicle.driver_name">
														{{ $t('driver') }} : <b>{{ list.rental_vehicle.driver_name }}</b>
														<span v-if="list.rental_vehicle.driver_phone"> ({{ list.rental_vehicle.driver_phone }})<br></span>
													</span>
													<span v-if="list.rental_vehicle && list.rental_vehicle.reference_name">
														{{ $t('reference') }} : <b>{{ list.rental_vehicle.reference_name }}</b>
														<span v-if="list.rental_vehicle.reference_phone"> ({{ list.rental_vehicle.reference_phone }})<br></span>
													</span>
													<span v-if="list.rental_vehicle && list.rental_vehicle.owner_name">
														{{ $t('owner') }} : <b>{{ list.rental_vehicle.owner_name }}</b>
														<span v-if="list.rental_vehicle.owner_phone"> ({{ list.rental_vehicle.owner_phone }})<br></span>
													</span>
													{{ $t('load_point') }} : <b>{{ list.load?list.load:'---' }}</b><br>
													{{ $t('unload_point') }} : <b>{{ list.unload?list.unload:'---' }}</b><br>
													{{ $t('posted_by') }} : <br>
													<b>{{ list.created_info.first_name }} {{ list.created_at? '(' + $getHumanDateTime(list.created_at) + ')' :'' }}</b>
													<span v-if="list.updated_by">
														<br>
														{{ $t('updated_by') }} : <br>
														<b>{{ list.updated_info.first_name }} {{ list.updated_at? '(' + $getHumanDateTime(list.updated_at) + ')' :'' }}</b>
													</span>
													<!-- {{ $t('challan_received') }} : <b>---</b> -->
												</small>
												<div class="dropdown">
													<button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														{{ $t('action') }}
													</button>
													<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
														<a :href="'/api/commission-single-challan?encrypt=' + list.encrypt + '&size=Legal-L&page_header=all_info_in_header&output=stream'" target="_blank" :title="$t('print')" class="dropdown-item btn-sm"><i class="fa fa-print"></i> {{ $t('print') }}</a>
														<a :href="'/api/commission-single-challan?encrypt=' + list.encrypt  + '&size=Legal-L&page_header=all_info_in_header&output=download'" :title="$t('download')" class="dropdown-item btn-sm"><i class="fa fa-download"></i> {{ $t('download') }}</a>
														<router-link class="dropdown-item btn-sm" :to="'/commission-challan/' + list.id" :title="$t('edit')"><i class="fa fa-edit"></i> {{ $t('edit') }}</router-link>
														<button class="dropdown-item btn-sm" disabled :title="$t('copy')" ><i class="fa fa-copy"></i> {{ $t('copy') }}</button>
														<button class="dropdown-item btn-sm" @click="showNoteModal(list.id, index, list.note, list.note_color)" :title="$t('note')" ><i class="fa fa-edit"></i> {{ $t('note') }}</button>
														
														<div class="dropdown-divider"></div>
														<button 
															type="button"
															class="dropdown-item btn-sm"
															:title="$t('delete')"
															@click="deleteModal(list.id, index)"
															><i class="fa fa-trash"></i> {{ $t('delete') }}
														</button>									
													</div>
												</div>
											</td>
											<td class="text-right">
												<small>
													<span v-if="list.rental_vehicle && list.rental_vehicle.vendor">
														<b>{{ list.rental_vehicle.vendor.name }}</b><br>

														<span v-if="list.rental_vehicle && list.vendor_bills.length > 0">
															<div style="border-bottom: 2px dashed grey; margin-left: 16px;">
																{{ $t('contract_rent') }} = <b>{{ $numFormat($getSumFromLists(list.vendor_contract_bills, 'amount')) }}</b><br>
																<!-- {{ $t('extend_rent') }} = <b>{{ $numFormat($getSumFromLists(list.vendor_extend_bills, 'amount')) }}</b><br> -->
																{{ $t('demurrage_rent') }} = <b>{{ $numFormat($getSumFromLists(list.vendor_demurrage_bills, 'amount')) }}</b><br>
																{{ $t('received_money') }} = <b>{{ $numFormat($getSumFromLists(list.vendor_security_received_money_payments, 'amount')) }}</b>
															</div>
															<div style="border-bottom: 2px dashed grey; margin-left: 16px;">
																{{ $t('total_rent') }} = <b>{{ $numFormat($getSumFromLists(list.vendor_bills, 'amount') + $getSumFromLists(list.vendor_security_received_money_payments, 'amount')) }}</b><br>
																<!-- {{ $t('advance_provide') }} = (-) <b>{{ $numFormat($getSumFromLists(list.vendor_advance_payments, 'amount')) }}</b><br>
																{{ $t('after_advance_provide') }} = (-) <b>{{ $numFormat($getSumFromLists(list.vendor_after_advance_payments, 'amount')) }}</b><br> -->
																{{ $t('total_paid') }} = (-) <b>{{ $numFormat($getSumFromLists(list.vendor_payments, 'amount')) }}</b><br>
															</div>
															<span v-if="(($getSumFromLists(list.vendor_bills, 'amount')  + $getSumFromLists(list.vendor_security_received_money_payments, 'amount')) - $getSumFromLists(list.vendor_payments, 'amount')) > 0" class="text-danger">
																{{ $t('challan_due') }} = 
																<b>
																	{{ $numFormat(($getSumFromLists(list.vendor_bills, 'amount') + $getSumFromLists(list.vendor_security_received_money_payments, 'amount')) - $getSumFromLists(list.vendor_payments, 'amount')) }}
																</b>
															</span>
															<span v-else>
																{{ $t('challan_due') }} = <b>0</b>
															</span>
															<!-- <span v-if="list.vendor_security_paid_money_payments.length > 0">
																<br>
																{{ $t('paid_money') }} = <b>{{ $numFormat($getSumFromLists(list.vendor_security_paid_money_payments, 'amount')) }}</b>
															</span> -->
														</span>
														<br>
														<button
															type="button" 
															class="btn btn-xs bg-gradient-primary mr-1"
															:title="$t('add_bill')"
															@click="showBillModal(list.id, list.rental_vehicle.vendor_id, index)"
															>
															<i class="fa fa-plus"></i> {{ $t('add_bill') }}
														</button>
														<button
															v-if="list.rental_vehicle && list.vendor_bills.length > 0"
															type="button" 
															class="btn btn-xs bg-gradient-info mr-1"
															:title="$t('make_payment')"
															@click="showVendorPaymentProvideModal(list.id, list.rental_vehicle.vendor_id, index)"
															>
															<i class="fa fa-money-bill-alt"></i> {{ $t('make_payment') }}
														</button>
														<button
															v-if="list.rental_vehicle && list.vendor_bills.length > 0"
															type="button" 
															class="btn btn-xs bg-gradient-primary mr-1"
															:title="$t('make_payment')"
															@click="showSecurityModal(list.id, list.rental_vehicle.vendor_id, index)"
															>
															<i class="fa fa-handshake"></i> {{ $t('security') }}
														</button>
													</span>
													<span v-else>{{ $t('no_information_provided') }}</span>
												</small>
											</td>
											<td class="text-right">
												<small>
													<span v-if="list.client_bills.length > 0">
														<b>{{ list.client_bills[0].client_info.name }}</b><br>
														<span v-if="list.client_bills.length > 0">
															<div style="border-bottom: 2px dashed grey; margin-left: 16px;">
																{{ $t('contract_rent') }} = <b>{{ $numFormat($getSumFromLists(list.client_contract_bills, 'amount')) }}</b><br>
																<!-- {{ $t('extend_rent') }} = <b>{{ $numFormat($getSumFromLists(list.vendor_extend_bills, 'amount')) }}</b><br> -->
																{{ $t('demurrage_rent') }} = <b>{{ $numFormat($getSumFromLists(list.client_demurrage_bills, 'amount')) }}</b>
															</div>
															<div style="border-bottom: 2px dashed grey; margin-left: 16px;">
																{{ $t('total_rent') }} = <b>{{ $numFormat($getSumFromLists(list.client_bills, 'amount')) }}</b><br>
																{{ $t('advance_received') }} = (-) <b>{{ $numFormat($getSumFromLists(list.client_advance_payments, 'amount')) }}</b><br>
																{{ $t('after_advance_rent') }} = (-) <b>{{ $numFormat($getSumFromLists(list.client_after_advance_payments, 'amount')) }}</b><br>
															</div>
															<span v-if="($getSumFromLists(list.client_bills, 'amount') - $getSumFromLists(list.client_payments, 'amount')) > 0" class="text-danger">
																{{ $t('challan_due') }} = 
																<b>
																	{{ $numFormat($getSumFromLists(list.client_bills, 'amount') - $getSumFromLists(list.client_payments, 'amount')) }}
																</b>
															</span>
															<span v-else>
																{{ $t('challan_due') }} = <b>0</b>
															</span>
															<br>
														</span>
														<button
															v-if="list.client_bills.length > 0"
															type="button" 
															class="btn btn-xs bg-gradient-primary mr-1"
															:title="$t('add_bill')"
															@click="showClientBillModal(list.id, list.client_bills[0].client_id, index)"
															>
															<i class="fa fa-plus"></i> {{ $t('add_bill') }}
														</button>
														<button
															v-if="list.client_bills.length > 0"
															type="button" 
															class="btn btn-xs bg-gradient-info mr-1"
															:title="$t('payment_received')"
															@click="showClientPaymentReceivedModal(list.id, list.client_bills[0].client_id, index)"
															>
															<i class="fa fa-money-bill-alt"></i> {{ $t('payment_received') }}
														</button>
													</span>
													<span v-else>
														{{ $t('no_information_provided') }}<br>
														<button
															type="button" 
															class="btn btn-xs bg-gradient-primary mr-1"
															:title="$t('add_bill')"
															@click="showClientBillModal(list.id, '', index)"
															>
															<i class="fa fa-plus"></i> {{ $t('add_bill') }}
														</button>
													</span>
												</small>
											</td>
											<td class="text-right">
												<small>
													<span 
														v-if="list.vendor_security_received_money_payments && list.vendor_security_received_money_payments.length > 0" 
														:class="{ 'text-green': $getSumFromLists(list.vendor_security_received_money_payments, 'amount') >= ($getSumFromLists(list.client_bills, 'amount') - $getSumFromLists(list.vendor_bills, 'amount')) }">
														{{ $t('contract_commission') }} = <b>{{ $numFormat($getSumFromLists(list.client_bills, 'amount') - $getSumFromLists(list.vendor_bills, 'amount')) }}</b><br><br>
													</span>
													<span v-else >
														{{ $t('contract_commission') }} = <b>{{ $numFormat($getSumFromLists(list.client_bills, 'amount') - $getSumFromLists(list.vendor_bills, 'amount')) }}</b><br><br>
													</span>

													<div style="border-bottom: 2px dashed grey; margin-left: 16px;">
														{{ $t('received_from_company') }} = <b>{{ $numFormat($getSumFromLists(list.client_payments, 'amount')) }}</b><br>
														{{ $t('rental_paid') }} = <b>{{ $numFormat($getSumFromLists(list.vendor_payments, 'amount')) }}</b><br>
													</div>
													<!-- <div style="border-bottom: 2px dashed grey; margin-left: 16px;">
														{{ $t('commission') }} {{ $t('balance') }} = <b>0</b><br>
														{{ $t('demurrage') }} {{ $t('commission') }} = (+) <b>0</b>
													</div> -->
													{{ $t('commission') }} {{ $t('balance') }} = <b>{{ $numFormat($getSumFromLists(list.client_payments, 'amount') - $getSumFromLists(list.vendor_payments, 'amount')) }}</b>
													
													<span v-if="list.note">
														<br>
														<b :style="{ color: list.note_color?list.note_color:'#000000' }">({{ list.note }})</b>
													</span>
												</small>
												<br>
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

		<BillAddModal 
			ref="billAddModalRef"
			:vendors="vendorAsArray"
			:clients="clientAsArray"

			@afterAdd="addBill"
			@afterDelete="singleSync"
			/>

		<VendorPaymentProvideModal 
			ref="vendorPaymentProvideModalRef"
			:accounts="accounts"
			:vendors="vendors"

			@afterAdd="addPaymentInVendor"
			@afterDelete="singleSync"
			/>

		<ClientPaymentReceivedModal 
			ref="clientPaymentReceivedModal"
			:accounts="accounts"
			:clients="clients"

			@afterAdd="addPaymentInClient"
			@afterDelete="singleSync"
			/>

		<SecurityModal 
			ref="securityModalRef"
			:accounts="accounts"
			:vendors="vendors"

			@afterAdd="addPaymentInVendor"
			@afterDelete="singleSync"
			/>

		<NoteModal 
			ref="noteModalRef"
			@afterAdd="singleSync"
			/>

	</div>
</template>

<script>

import { useToast } from "vue-toastification";
import Swal from 'sweetalert2';
import Pagination from 'v-pagination-3';
import moment from 'moment';

import SearchFilterModal from '../../../components/commons/SearchFilterModal.vue';
import BillAddModal from '../../../components/commons/BillAddModal.vue';
import VendorPaymentProvideModal from '../../../components/commons/VendorPaymentProvideModal.vue';
import ClientPaymentReceivedModal from '../../../components/commons/ClientPaymentReceivedModal.vue';
import SecurityModal from '../../../components/commons/SecurityModal.vue';
import NoteModal from '../../../components/commons/NoteModal.vue';


export default {
	components: {
		SearchFilterModal,
		Pagination,
		BillAddModal,
		VendorPaymentProvideModal,
		ClientPaymentReceivedModal,
		SecurityModal,
		NoteModal
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

			// search filters
			filterParams: {},
			vehicles: {},
            clients: {},
			accounts: {},
            vendors: {},
			vendorAsArray: [],
			clientAsArray: [],
			searchOptions:[
				"perPage",
				"orderType",
                "timeRange",
                "challanNumber",
                "rentalVehicle",
                "client",
			],
			listIndex: ''
		}
	},
	created() {
		this.getData();
		this.getVendors();
		this.getClients();
		this.getAccounts();
	},
	methods: {
		getData() {
			this.listLoading = true;
			let params = { ...this.pagination, ...this.filterParams };
			axios.get('/api/commission-challans', { params })
				.then((response) => {
					this.lists = response.data.lists.data;
					this.pagination.currentPage = response.data.lists.current_page;
					this.pagination.from = response.data.lists.from;
					this.pagination.total = response.data.lists.total;

					this.listLoading = false;
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
				let response = await axios.delete('/api/commission-challans/' + id);
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
		// vendor and client both part
		showBillModal(challanId, vendorId, listIndex){

			this.listIndex = listIndex;

			this.$refs.billAddModalRef.resetForm();
			this.$refs.billAddModalRef.dealWith = 'vendor';
			this.$refs.billAddModalRef.disabledVendor = true;

			this.$refs.billAddModalRef.form.vendor_id = vendorId;
			this.$refs.billAddModalRef.form.purposeable_id = challanId;
			this.$refs.billAddModalRef.form.purposeable_type = 'challans';
			this.$refs.billAddModalRef.apiLink = '/api/vendor-bills';

			this.$refs.billAddModalRef.setDefaultVendor();
			this.$refs.billAddModalRef.getLists();
			$("#showBillAddModal").modal("show");
	
		},
		// client part
		showClientBillModal(challanId, clientId, listIndex){

			this.listIndex = listIndex;

			this.$refs.billAddModalRef.resetForm();
			this.$refs.billAddModalRef.dealWith = 'client';
			this.$refs.billAddModalRef.disabledClient = (clientId == '') ? false : true;

			this.$refs.billAddModalRef.form.client_id = clientId;
			this.$refs.billAddModalRef.form.purposeable_type = 'challans';
			this.$refs.billAddModalRef.form.purposeable_id = challanId;
			this.$refs.billAddModalRef.apiLink = '/api/client-bills';
			
			this.$refs.billAddModalRef.setDefaultClient();
			this.$refs.billAddModalRef.getLists();
			$("#showBillAddModal").modal("show");

		},
		// vendor part
		showVendorPaymentProvideModal(challanId, vendorId, listIndex){

			this.listIndex = listIndex;
			this.$refs.vendorPaymentProvideModalRef.resetForm();

			this.$refs.vendorPaymentProvideModalRef.form.vendor_id = vendorId;
			this.$refs.vendorPaymentProvideModalRef.disabledVendorSelection = true;

			this.$refs.vendorPaymentProvideModalRef.form.purposeable_type = 'challans';
			this.$refs.vendorPaymentProvideModalRef.form.purposeable_id = challanId;

			this.$refs.vendorPaymentProvideModalRef.apiLink = '/api/vendor-payments';
			this.$refs.vendorPaymentProvideModalRef.apiLinkQuery = '/api/vendor-payments?security_money=false';
			this.$refs.vendorPaymentProvideModalRef.getLists();

			$("#showVendorPaymentProvideModal").modal("show");
		},
		addPaymentInVendor(response){

			switch (response.payment_type) {

				case 'advance_rent':
					this.lists[this.listIndex].vendor_advance_payments.push(response);
					break;

				case 'after_advance_rent':
					this.lists[this.listIndex].vendor_after_advance_payments.push(response);
					break;

				case 'received_money':
					this.lists[this.listIndex].vendor_security_received_money_payments.push(response);
					break;

				case 'paid_money':
					this.lists[this.listIndex].vendor_security_paid_money_payments.push(response);
					break;

				// other payment method here
			}

			// only trigger when payment happen
			switch (response.payment_type) {
				case 'advance_rent':
				case 'after_advance_rent':
					this.lists[this.listIndex].vendor_payments.push(response);
					this.$refs.vendorPaymentProvideModalRef.getLists();
					break;

				case 'received_money':
				case 'paid_money':
					this.$refs.securityModalRef.getLists();
					break;
			}

			this.getAccounts();
		},
		// client part
		showClientPaymentReceivedModal(challanId, clientId, listIndex){
			this.listIndex = listIndex;

			this.$refs.clientPaymentReceivedModal.resetForm();

			this.$refs.clientPaymentReceivedModal.form.client_id = clientId;
			this.$refs.clientPaymentReceivedModal.disabledClientSelection = true;

			this.$refs.clientPaymentReceivedModal.form.purposeable_type = 'challans';
			this.$refs.clientPaymentReceivedModal.form.purposeable_id = challanId;

			this.$refs.clientPaymentReceivedModal.apiLink = '/api/client-payments';
			this.$refs.clientPaymentReceivedModal.getLists();

			$("#showClientPaymentReceivedModal").modal("show");
		},
		addPaymentInClient(response){
			switch (response.payment_type) {

				case 'advance_rent':
					this.lists[this.listIndex].client_advance_payments.push(response);
					break;

				case 'after_advance_rent':
					this.lists[this.listIndex].client_after_advance_payments.push(response);
					break;

				// other payment method here
			}

			this.lists[this.listIndex].client_payments.push(response);
			this.$refs.clientPaymentReceivedModal.getLists();
			this.getAccounts();
		},
		showSecurityModal(challanId, vendorId, listIndex){

			this.listIndex = listIndex;
			this.$refs.securityModalRef.resetForm();

			this.$refs.securityModalRef.form.vendor_id = vendorId;
			this.$refs.securityModalRef.disabledVendorSelection = true;

			this.$refs.securityModalRef.form.purposeable_type = 'challans';
			this.$refs.securityModalRef.form.purposeable_id = challanId;

			this.$refs.securityModalRef.apiLink = '/api/vendor-payments';
			this.$refs.securityModalRef.apiLinkQuery = '/api/vendor-payments?security_money=true';
			
			this.$refs.securityModalRef.getLists();
			$("#showSecurityModal").modal("show");
		},
		// emit event
		addBill(response){
			if(this.$refs.billAddModalRef.dealWith == 'vendor'){

				switch (response.bill_type) {

					case 'contract_rent':
						this.lists[this.listIndex].vendor_contract_bills.push(response);
						break;

					case 'demurrage_rent':
						this.lists[this.listIndex].vendor_demurrage_bills.push(response);
						break;
				}

				this.lists[this.listIndex].vendor_bills.push(response);
			}

			if(this.$refs.billAddModalRef.dealWith == 'client'){

				switch (response.bill_type) {

					case 'contract_rent':
						this.lists[this.listIndex].client_contract_bills.push(response);
						break;

					case 'demurrage_rent':
						this.lists[this.listIndex].client_demurrage_bills.push(response);
						break;
				}

				this.lists[this.listIndex].client_bills.push(response);
			}
		},
		singleSync(challanId){
			axios.get('/api/commission-challans/' + challanId)
				.then((response) => {

					var challanId = response.data.data.id;
					var listIndex =  this.lists.findIndex(element => element.id == challanId, challanId);
					this.lists[listIndex] = response.data.data;
					
				}).catch((error) => {
					this.toast.warning(error.message);
				});
			this.getAccounts();
		},

		// common data
		getVendors(){
			axios.get('/api/common/vendors')
				.then((response) => {
					this.vendors = response.data.lists;
					this.vendorAsArray = response.data.lists;
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
		// search filters
		getClients() {
            axios.get('/api/common/clients')
				.then((response) => {
					this.clients = response.data.lists;
					this.clientAsArray = response.data.lists;
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
		showNoteModal(challanId, listIndex, challanNote, challanNoteColor){
			this.listIndex = listIndex;
			this.$refs.noteModalRef.form.id = challanId;
			this.$refs.noteModalRef.form.note = challanNote;
			this.$refs.noteModalRef.form.note_color = challanNoteColor?challanNoteColor:'#000000';
			this.$refs.noteModalRef.apiLink = '/api/challan-note';
			$("#showNoteModal").modal("show");
		}
	},
	computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
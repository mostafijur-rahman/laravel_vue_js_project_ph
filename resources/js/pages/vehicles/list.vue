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
										to="/vehicles/create"
										class="btn btn-sm btn-success mr-1"
										>
										<i class="fa fa-plus"></i> {{ $t('create') }}
									</router-link>
								</div>
							</div>
							<div class="modal fade" id="details">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">{{ $t('details') }}</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<p>details here...</p>
										</div>
										<div class="modal-footer justify-content-between">
											<button type="button" class="btn btn-default" ref="editModal" data-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body table-responsive p-0">

								<table class="table table-striped text-center table-hover">
									<thead>
										<tr>
											<th style="width:5%">#</th>
											<th>{{ $t('vehicle_info') }}</th>
											<th>{{ $t('staff') }}</th>
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
													{{ $t('ownership') }} : <b>{{ $t(list.ownership) }}</b><br>
													{{ $t('number_plate') }} : <b>{{ list.number_plate }}</b><br>
													{{ $t('registration_number') }} : <b>{{ list.registration_number?list.registration_number:'---' }}</b>
												</small>
											</td>
											<td class="text-left">
												<small>
													{{ $t('driver') }} : <b>{{ list.driver_id?list.driver.name:'---' }} {{ list.driver_id?'(' + list.driver.phone + ')':'' }}</b><br>
													{{ $t('helper') }} : <b>{{ list.helper_id?list.helper.name:'---' }}  {{ list.helper_id?'(' + list.helper.phone + ')':'' }}</b>
												</small>
											</td>
											<td>
												<button 
													type="button" 
													class="btn btn-xs bg-gradient-warning mr-1"
													:title="$t('print')"
													>
													<i class="fas fa-print"></i>
												</button>
												<button 
													type="button" 
													class="btn btn-xs bg-gradient-info mr-1"
													:title="$t('details')"
													data-toggle="modal" 
													data-target="#details"
													>
													<i class="fas fa-list"></i>
												</button>
												<router-link 
													:to="{ name: 'vehicle-edit', params: { id: list.id} }"
													class="btn btn-xs bg-gradient-primary mr-1"
													:title="$t('edit')"
												>
													<i class="fas fa-edit"></i>
												</router-link>
												<button 
													type="button" 
													@click="deleteModal(list.id, index)"
													class="btn btn-xs bg-gradient-danger" 
													:title="$t('delete')"
													>
													<i class="fas fa-trash"></i>
												</button>
											</td>
										</tr>
										<tr v-else>
											<td colspan="6">
												<h5 class="text-danger">{{ $t('no_data') }}</h5>
											</td>
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
import Swal from 'sweetalert2';
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

			filter: {
				number_plate: '',
			},

			// search
			pagination: {
				paginate: true,
				perPage: 20,
				orderBy: 'id',
				orderType: 'ASC',

				currentPage: 1,
				from: 1,
				total: 0,
			},
			// search filters
			filterParams: {},
			vehicles: {},
			searchOptions:[
				"perPage",
				"orderBy",
                "timeRange",
                "ownVehicle",
			],
			rowNumber: 1
		}
	},
	created() {
		this.getData();
		this.getVehicles();
	},
	methods: {
		resetData() {
			this.filter.number_plate = '';
			this.getData();
		},
		getData() {
			this.listLoading = true;
			let params = { ...this.pagination, ...this.filter };
			axios.get('/api/vehicles', { params })
				.then((response) => {
					this.lists = response.data.lists.data;
					this.pagination.currentPage = response.data.lists.current_page;
					this.pagination.from = response.data.lists.from;
					this.pagination.total = response.data.lists.total;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
			this.listLoading = false;
		},
		deleteModal(id, index) {
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
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
					title: 'Working on it',
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					didOpen: () => {
						Swal.showLoading();
					}
				})
				// fire request
				let response = await axios.delete('/api/vehicles/' + id);
				this.lists.splice(index, 1);
				Swal.close();
				Swal.fire({
					position: 'center',
					icon: 'success',
					title: 'Successfully deleted!',
					showConfirmButton: false,
					timer: 1000
				})

			} catch (error) {
				Swal.close();
				Swal.fire({
					position: 'center',
					icon: 'error',
					title: 'Something went wrong!',
					showConfirmButton: false,
					timer: 1000
				})
			}
		},
		pagiAction(number){
			this.pagination.currentPage = number;
			this.getData();
		},
		getVehicles() {
			axios.get('/api/common/vehicles')
				.then((response) => {
					this.vehicles = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
		showSearchFilterModal(){
			$("#showSearchFilterModal").modal("show");
		},
		setSearchFilterParams(params){
			if(params.perPage){
				this.pagination.perPage = params.perPage
			}
			if(params.orderType){
				this.pagination.orderType = params.orderType
			}
			// set filter
			this.filterParams = params;
			this.lists = {};
			this.getData();
		},
	},
}
</script>
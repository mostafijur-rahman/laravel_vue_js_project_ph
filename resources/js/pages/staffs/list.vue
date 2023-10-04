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
											<th style="width:5%">#</th>
											<th>{{ $t('personal_information') }}</th>
											<th>{{ $t('document') }}</th>
											<th>{{ $t('reference') }}</th>
											<th>{{ $t('status') }}</th>
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
													{{ $t('name') }} : <b>{{ list.name }}</b><br>
													{{ $t('designation') }} : <b>{{ list.designation_id?$t(list.designation.name):'---' }}</b><br>
													{{ $t('phone') }} : <b>{{ list.phone?list.phone:'---' }}</b><br>
													{{ $t('email') }} : <b>{{ list.email?list.email:'---' }}</b>
												</small>
											</td>
											<td class="text-left">
												<small>
													{{ $t('company_id') }} : <b>{{ list.company_id_number?list.company_id_number:'---' }}</b><br>
													{{ $t('nid_number') }} : <b>{{ list.nid_number?list.nid_number:'---' }}</b><br>
													{{ $t('driving_license_number') }} : <b>{{ list.driving_license_number?list.driving_license_number:'---' }}</b><br>
													{{ $t('port_id') }} : <b>{{ list.port_id?list.port_id:'---' }}</b>
												</small>
											</td>
											<td>----</td>
											<td>
												<h6 v-if="list.status == 'active'"><span class="badge badge-success">{{ $t('active') }}</span></h6>
												<h6 v-else-if="list.status == 'inactive'"><span class="badge badge-warning">{{ $t('inactive') }}</span></h6>
												<h6 v-else><span class="badge badge-danger">{{ $t('blocked') }}</span></h6>
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
													:title="$t('profile')"
													>
													<i class="fas fa-user"></i>
												</button>
												<router-link 
													:to="{ name: 'staff-reference-list', params: { id: list.id} }"
													class="btn btn-xs bg-gradient-primary mr-1"
													:title="$t('reference')"
													>
													<i class="fas fa-user-friends"></i>
												</router-link>
												<router-link 
													:to="{ name: 'staff-edit', params: { id: list.id} }"
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

			// filter: {
			// 	name: '',
			// },

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
			searchOptions:[
				"perPage",
				"orderType",
				"name",
			],
			rowNumber: 1
		}
	},
	created() {
		this.getData();

	},
	methods: {
		resetData() {
			this.filter.name = '';
			this.getData();
		},
		getData() {
			this.listLoading = true;
			let params = { ...this.pagination, ...this.filterParams };
			axios.get('/api/staffs', { params })
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
				let response = await axios.delete('/api/staffs/' + id);
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
		pagiAction(number){
			this.pagination.currentPage = number;
			this.getData();
		}
	},
}
</script>
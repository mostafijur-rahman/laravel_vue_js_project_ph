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
											class="btn btn-sm btn-success"
											@click="showAddModal"
											>
											<i class="fa fa-plus"></i> {{ $t('create') }}
										</button>
									</div>
							</div>
							<div class="modal fade" id="add">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<form @submit.prevent="createData()">
											<div class="modal-header">
												<h4 class="modal-title">{{ $t('create_form') }}</h4>
												<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<label for="">{{ $t('name') }} <small class="text-danger">({{ $t('required') }})</small></label>
													<input type="text" class="form-control" v-model="form.name" :placeholder="$t('name')" required>
												</div>
												<div class="form-group">
													<label for="">{{ $t('status') }}</label>
													<select v-model="form.status" class="form-control">
														<option value="active">{{ $t('active') }}</option>
														<option value="inactive">{{ $t('inactive') }}</option>
													</select>
												</div>
											</div>
											<div class="modal-footer justify-content-between">
												<button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
												<div>
													<button type="submit" :disabled="submitLoading" @click="addAnother=false" class="btn btn-success mr-1">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('post') }}
													</button>
													<button type="submit" :disabled="submitLoading" @click="addAnother=true" class="btn btn-success">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('post_and_add_another') }}
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>

							<div class="modal fade" id="edit">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<form @submit.prevent="updateData()">
											<div class="modal-header">
												<h4 class="modal-title">{{ $t('edit_form') }}</h4>
												<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<label for="">{{ $t('name') }} <small class="text-danger">({{ $t('required') }})</small></label>
													<input type="text" class="form-control" v-model="form.name" :placeholder="$t('name')" required>
												</div>
												<div class="form-group">
													<label for="">{{ $t('status') }}</label>
													<select v-model="form.status" class="form-control">
														<option value="active">{{ $t('active') }}</option>
														<option value="inactive">{{ $t('inactive') }}</option>
													</select>
												</div>
											</div>
											<div class="modal-footer justify-content-between">
												<button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
												<button type="submit" :disabled="submitLoading" @click="addAnother=false" class="btn btn-success mr-1">
													<i v-bind:class="submitBtnIcon"></i> {{ $t('update_post') }}
												</button>
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
											<th>{{ $t('name') }}</th>
											<th>{{ $t('status') }}</th>
											<th>{{ $t('action') }}</th>
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
											<td>{{ index + pagination.from }}</td>
											<td>{{ list.name }}</td>
											<td>
												<h6 v-if="list.status == 'active'"><span class="badge badge-success">{{ $t('active') }}</span></h6>
												<h6 v-else><span class="badge badge-danger">{{ $t('inactive') }}</span></h6>
											</td>
											<td>
												<button 
													type="button" 
													class="btn btn-xs bg-gradient-primary mr-1"
													title="{{ $t('edit') }}"
													data-toggle="modal" 
													data-target="#edit"
													@click="editModal(list)"
													>
													<i class="fas fa-edit"></i>
												</button>
												<button 
													type="button" 
													@click="deleteModal(list.id, index)"
													class="btn btn-xs bg-gradient-danger" 
													title="{{ $t('delete') }}"
													>
													<i class="fas fa-trash"></i>
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

	</div>
</template>

<script>
import { useToast } from "vue-toastification";
import Swal from 'sweetalert2';
import Pagination from 'v-pagination-3';
// import SearchFilter from '../../components/commons/SearchFilter.vue';
import VendorModal from '../../components/commons/VendorModal.vue';
import SearchFilterModal from '../../components/commons/SearchFilterModal.vue';

export default {
	components: {
		Pagination,
		VendorModal,
		SearchFilterModal
	},
	data() {
		return {
			toast: useToast(),
			submitLoading: false,
            addAnother: false,

			// form
			form: {
				id: '',
				name: '',
				status: 'active',
			},

			// list
			isListLoaded: false,
			lists: {},
			listLoading: false,

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
			vehicles: {},
            clients: {},
			searchOptions:[
				"perPage",
				"orderBy",
				"orderType",
                "name",
				"modalCreate1"
			]
		}
	},
	created() {
		this.getData();

		// search filters
		this.getVehicles();
		this.getClients();
	},
	methods: {
		resetData() {
			this.filter.name = '';
			this.getData();
		},
		getData() {
			this.listLoading = true;
			let params = { ...this.pagination, ...this.filterParams };
			
			axios.get('/api/settings/areas', { params })
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
		showSearchFilterModal(){
			$("#showSearchFilterModal").modal("show");
		},
		addModal() {
			// reset form
			this.form.id = ''; 
			this.form.name = ''; 
			this.form.status = 'active';
		},
		createData() {
			// loading
			this.submitLoading = true;
			// insertion
			axios.post('/api/settings/areas', this.form)
				.then((response) => {

					this.toast.success(this.$t(response.data.message));

					this.getData();

					if(!this.addAnother){
                        this.resetForm();
						$("#add").modal("hide");
					}

				}).catch((error) => {
					this.toast.warning(error.message);
					this.submitLoading = false;
				}).finally(() => {
					this.submitLoading = false;
				})
		},
		editModal(list) {
			this.form = { ...this.form, ...list};
		},
		updateData() {
			// loading
			this.submitLoading = true;

			// insertion
			axios.put('/api/settings/areas/' + this.form.id, this.form)
				.then((response) => {

					this.toast.success(this.$t(response.data.message));

					$("#edit").modal("hide");
					this.resetForm();
					this.getData();
					
				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() => {
					this.submitLoading = false
				})
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
				let response = await axios.delete('/api/settings/areas/' + id);
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
		showAddModal(){
			$("#add").modal("show");
		},
		resetForm(){
            this.form.id = '';
            this.form.name = '';
            this.form.status = 'active';
        },
	},
	computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
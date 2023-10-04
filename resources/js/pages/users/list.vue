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
											class="btn btn-sm btn-success mr-1"
											@click="showAddModal(null)"
											>
											<i class="fa fa-plus"></i> {{ $t('create') }}
										</button>	
									</div>
							</div>
							<div class="modal fade" id="addEdit">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<form @submit.prevent="addUpdate()">
											<div class="modal-header">
												<h4 v-if="!form.id" class="modal-title">{{ $t('create_form') }}</h4>
												<h4 v-else class="modal-title">{{ $t('update_form') }}</h4>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="">{{ $t('role') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<select 
																class="form-control" 
																:class="{ 'is-invalid': (validationErrors && validationErrors['role_id'])?true:false }"
																v-model="form.role_id" 
																required>
																<option value="">{{ $t('please_select') }}</option>
																<option v-if="(roles.length > 0)" v-for="role in roles" :value="role.id">{{ role.name }}</option>
															</select>
															<span
																class="error invalid-feedback"
																v-if="(validationErrors && validationErrors['role_id'])?true:false"
																v-for="(error) in validationErrors['role_id']"
																>{{ error }}
															</span>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="">{{ $t('phone') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<input 
																type="text" 
																class="form-control"
																:class="{ 'is-invalid': (validationErrors && validationErrors['phone'])?true:false }"
																v-model="form.phone" 
																placeholder="017 XXXX XXXX" 
																required>
															<span
																class="error invalid-feedback"
																v-if="(validationErrors && validationErrors['phone'])?true:false"
																v-for="(error) in validationErrors['phone']"
																>{{ error }}
															</span>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="">{{ $t('first_name') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<input 
																type="text" 
																class="form-control" 
																v-model="form.first_name" 
																:placeholder="$t('example_mostafijur')" 
																:class="{ 'is-invalid': (validationErrors && validationErrors['first_name'])?true:false }"
																required>
															<span
																class="error invalid-feedback"
																v-if="(validationErrors && validationErrors['first_name'])?true:false"
																v-for="(error) in validationErrors['first_name']"
																>{{ error }}
															</span>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="">{{ $t('last_name') }}</label>
															<input 
																type="text" 
																class="form-control" 
																:class="{ 'is-invalid': (validationErrors && validationErrors['last_name'])?true:false }"
																v-model="form.last_name" 
																:placeholder="$t('example_rahman')">
															<span
																class="error invalid-feedback"
																v-if="(validationErrors && validationErrors['last_name'])?true:false"
																v-for="(error) in validationErrors['last_name']"
																>{{ error }}
															</span>
														</div>
													</div>
													<div class="col-md-6" v-if="!form.id">
														<div class="form-group">
															<label for="">{{ $t('password') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<input 
																type="text" 
																class="form-control" 
																:class="{ 'is-invalid': (validationErrors && validationErrors['password'])?true:false }"
																v-model="form.password" 
																:placeholder="$t('password')" required>
															<span
																class="error invalid-feedback"
																v-if="(validationErrors && validationErrors['password'])?true:false"
																v-for="(error) in validationErrors['password']"
																>{{ error }}
															</span>
														</div>
													</div>
													<div class="col-md-6" v-if="!form.id">
														<div class="form-group">
															<label for="">{{ $t('confirm_password') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<input 
																type="text" 
																class="form-control" 
																:class="{ 'is-invalid': (validationErrors && validationErrors['password_confirmation'])?true:false }"
																v-model="form.password_confirmation" 
																:placeholder="$t('confirm_password')" required>
															<span
																class="error invalid-feedback"
																v-if="(validationErrors && validationErrors['password_confirmation'])?true:false"
																v-for="(error) in validationErrors['password_confirmation']"
																>{{ error }}
															</span>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="">{{ $t('status') }}</label>
															<select class="form-control" v-model="form.status">
																<option value="active">{{ $t('active') }}</option>
																<option value="pending">{{ $t('pending') }}</option>
																<option value="blocked">{{ $t('blocked') }}</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="modal-footer justify-content-between">
												<button type="button" class="btn btn-default" ref="addModal" data-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
												<div v-if="!form.id">
													<button type="submit" :disabled="submitLoading" @click="addAnother=false" class="btn btn-success mr-1">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('post') }}
													</button>
													<button type="submit" :disabled="submitLoading" @click="addAnother=true" class="btn btn-success">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('post_and_add_another') }}
													</button>
												</div>
												<div v-else>
													<button type="submit" :disabled="submitLoading" @click="addAnother=false" class="btn btn-success mr-1">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('update_post') }}
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>			

							<div class="modal fade" id="pass">
								<div class="modal-dialog modal-md">
									<div class="modal-content">
										<form @submit.prevent="updatePasswordData()">
											<div class="modal-header">
												<h4 class="modal-title">{{ $t('password_update_form') }}</h4>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<label for="">{{ $t('new_password') }} <small class="text-danger">({{ $t('required') }})</small></label>
													<input 
														type="text" 
														class="form-control"
														:class="{ 'is-invalid': (validationErrors && validationErrors['password'])?true:false }" 
														v-model="passForm.password" 
														:placeholder="$t('new_password')" required>
													<span
														class="error invalid-feedback"
														v-if="(validationErrors && validationErrors['password'])?true:false"
														v-for="(error) in validationErrors['password']"
														>{{ error }}
													</span>
												</div>
												<div class="form-group">
													<label for="">{{ $t('confirm_password') }} <small class="text-danger">({{ $t('required') }})</small></label>
													<input 
														type="text" 
														class="form-control" 
														:class="{ 'is-invalid': (validationErrors && validationErrors['password_confirmation'])?true:false }"
														v-model="passForm.password_confirmation" 
														:placeholder="$t('confirm_password')" required>
													<span
														class="error invalid-feedback"
														v-if="(validationErrors && validationErrors['password_confirmation'])?true:false"
														v-for="(error) in validationErrors['password_confirmation']"
														>{{ error }}
													</span>
												</div>
											</div>
											<div class="modal-footer justify-content-between">
												<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
												<button type="submit" :disabled="submitLoading" class="btn btn-success mr-1">
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
											<th>{{ $t('role') }}</th>
											<th>{{ $t('name') }}</th>
											<th>{{ $t('phone') }}</th>
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
											<td> 
												<h5><span class="badge badge-secondary">{{ (list.roles && list.roles.length > 0)?list.roles[0]['name']:'---' }}</span></h5>
											</td>
											<td>{{ list.first_name }}</td>
											<td>{{ list.phone }}</td>
											<td>
												<h6 v-if="list.status == 'active'"><span class="badge badge-success">{{ $t('active') }}</span></h6>
												<h6 v-else-if="list.status == 'pending'"><span class="badge badge-warning">{{ $t('pending') }}</span></h6>
												<h6 v-else-if="list.status == 'blocked'"><span class="badge badge-danger">{{ $t('blocked') }}</span></h6>
											</td>
											<td>
												<button 
													type="button" 
													class="btn btn-xs bg-gradient-info mr-1"
													title="{{ $t('password') }}"
													data-toggle="modal" 
													data-target="#pass"
													@click="showPassModal(list.id)"
													>
													<i class="fas fa-key"></i>
												</button>
												<button 
													type="button" 
													class="btn btn-xs bg-gradient-primary mr-1"
													title="{{ $t('edit') }}"
													data-toggle="modal" 
													data-target="#edit"
													@click="showAddModal(list)"
													>
													<i class="fas fa-edit"></i>
												</button>
												<button
													v-if="list.roles && list.roles[0].name != 'Admin'"
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
			submitLoading: false,
			addAnother: false,

			// form
			form: {
				id: '',
				role_id: '',
				phone: '',
				first_name: '',
				last_name: '',
				password: '',
				password_confirmation: '',
				status: 'active'
			},
			passForm: {
				id: '',
				password: '',
				password_confirmation: ''
			},

			// list
			listLoading: false,
			lists: {},

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
			rowNumber: 1,
			roles: [],
			filterParams: {
				name: '',
			},
			searchOptions:[
				"perPage",
				"orderType"
			],
			validationErrors: {}
		}
	},
	created() {
		this.getData();
	},
	methods: {
		getData() {
			this.listLoading = true;
			let params = { ...this.pagination, ...this.filterParams };
			axios.get('/api/user', { params })
				.then((response) => {
					this.lists = response.data.lists.data;
					this.pagination.currentPage = response.data.lists.current_page;
					this.pagination.from = response.data.lists.from;
					this.pagination.total = response.data.lists.total;
					this.roles = response.data.roles;

					this.listLoading = false;
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
		addUpdate() {
			this.submitLoading = true;
			this.validationErrors = {};
			var request;

			// update
			if(this.form.id){
				request = axios.put('/api/user/' + this.form.id, this.form)
			// insert
			} else {
				request = axios.post('/api/user', this.form)
			}
			request
				.then((response) => {
					this.resetForm();
					if(!this.addAnother){
						$("#addEdit").modal("hide");
					}
					this.getData();
					this.toast.success(this.$t(response.data.message));
				}).catch((error) => {

					if (error.response.status == 422){
						this.validationErrors = error.response.data.errors;
					}
					this.submitLoading = false
				}).finally(() => {
					this.submitLoading = false
				});
		},
		showAddModal(list) {
			this.validationErrors = {};
			this.resetForm();
			if(list){
				this.form = { ...this.form, ...list};
				this.form.role_id = list.roles[0]['id'];
			}
			$("#addEdit").modal("show");
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
				let response = await axios.delete('/api/user/' + id);
				this.lists.splice(index, 1);
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
		showPassModal(id){
			this.passForm.id = id;
			this.validationErrors = {};
			$("#pass").modal("show");
		},
		updatePasswordData(){
			this.submitLoading = true;
			this.validationErrors = {};
			axios.post('/api/user-update-password/' + this.passForm.id, this.passForm)
				.then((response) => {

					this.toast.success(this.$t(response.data.message));
					this.resetPass();
					$("#pass").modal("hide");

				}).catch((error) => {

					if (error.response.status == 422){
						this.validationErrors = error.response.data.errors;
					}
					this.submitLoading = false

				}).finally(() => {
					this.submitLoading = false
				})
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
		resetForm() {
			// reset form
			this.form.id = ''; 
			this.form.role_id = ''; 
			this.form.phone = ''; 
			this.form.first_name = ''; 
			this.form.last_name = ''; 
			this.form.password = ''; 
			this.form.password_confirmation = '';
			this.form.status = 'active';
		},
		resetPass() {
			this.passForm.id = ''; 
			this.passForm.password = ''; 
			this.passForm.password_confirmation = '';
		},
	},
	computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
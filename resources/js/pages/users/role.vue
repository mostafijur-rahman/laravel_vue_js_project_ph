<template>
	<div class="content-wrapper">
		<section class="content-header"></section>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">

						<div class="card">
							<div class="card-header">
								<div class="card-tools">
									<button 
										type="button" 
										class="btn btn-sm btn-success"
										data-toggle="modal" 
										data-target="#add"
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
												<h4 class="modal-title">{{ $t('create_form') }}</h4>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<label for="">{{ $t('name') }} <small class="text-danger">({{ $t('required') }})</small></label>
													<input 
														type="text" 
														class="form-control" 
														v-model="form.name" 
														:class="{ 'is-invalid': (validationErrors && validationErrors['name'])?true:false }"
														:placeholder="$t('name')" required>
													<span
														class="error invalid-feedback"
														v-if="(validationErrors && validationErrors['name'])?true:false"
														v-for="(error) in validationErrors['name']"
														>{{ error }}
													</span>
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
						
							<div class="card-body table-responsive p-0">
								<table class="table table-striped text-center table-hover">
									<thead>
										<tr>
											<th style="width:5%">#</th>
											<th>{{ $t('name') }}</th>
											<th>{{ $t('action') }}</th>
										</tr>
									</thead>
									<tbody>
										<tr v-if="(listLoading == true)">
											<td colspan="3">
												<div class="d-flex justify-content-center">
													<div class="spinner-border"></div>
												</div>
											</td>
										</tr>
										<tr v-if="(lists.length > 0)" v-for="(list, index) in lists">
											<td>{{ index }}</td>
											<td>{{ list.name }}</td>
											<td>
												---
												<!-- <router-link
													to="/users/role/permission"
													class="btn btn-xs bg-gradient-info mr-1"
													title="{{ $t('permission') }}"
													><i class="fas fa-user-shield"></i> {{ $t('permission') }}
												</router-link> 
												<button 
													type="button" 
													class="btn btn-xs bg-gradient-primary mr-1"
													title="{{ $t('edit') }}"
													data-toggle="modal" 
													data-target="#edit"
													@click="showAddModal(list)"
													>
													<i class="fas fa-edit"></i>
												</button> -->
												<!-- <button 
													type="button" 
													@click="deleteModal(list.id, index)"
													class="btn btn-xs bg-gradient-danger" 
													title="{{ $t('delete') }}"
													>
													<i class="fas fa-trash"></i>
												</button> -->
												<!-- we are not able to calculate users with role that's why this feature has disabled -->
											</td>
										</tr>
										<tr v-if="(lists.length == 0)">
											<td colspan="3"><h5 class="text-danger">{{ $t('no_data') }}</h5></td>
										</tr>
									</tbody>
								</table>
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

export default {
	data() {
		return {
			toast: useToast(),
			submitLoading: false,
			addAnother: false,

			// form
			form: {
				id: '',
				name: '',
			},
			add_another: false,

			// list
			listLoading: false,
			lists: {},
			rowNumber: 1,
			validationErrors: {}
		}
	},
	created() {
		this.getData();
	},
	methods: {
		getData() {
			this.listLoading = true;
			axios.get('/api/user-roles')
				.then((response) => {
					this.lists = response.data.lists;

					this.listLoading = false;
				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() =>{
					this.listLoading = false;
				});
		},
		showAddModal(list) {
			this.validationErrors = {};
			this.resetForm();
			if(list){
				this.form = { ...this.form, ...list};
			}
			$("#addEdit").modal("show");
		},
		addUpdate() {
			this.submitLoading = true;
			this.validationErrors = {};
			var request;

			// update
			if(this.form.id){
				request = axios.put('/api/user-roles/' + this.form.id, this.form)
			// insert
			} else {
				request = axios.post('/api/user-roles', this.form)
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
				let response = await axios.delete('/api/user-roles/' + id);
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
		resetForm(){
			this.form.id = ''; 
			this.form.name = ''; 
		}

	},
}
</script>
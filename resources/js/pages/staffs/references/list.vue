<template>
	<div class="content-wrapper">
		<section class="content-header"></section>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">

						<div class="card">
							<div class="card-header">
								<h3 class="card-title"><b>{{ staffInfo.name }}</b></h3>
								<div class="card-tools">
									<router-link to="/staffs" class="btn btn-sm btn-secondary mr-2"><i class="fa fa-arrow-left"></i> {{ $t('staff_list') }}</router-link>
									<button 
										type="button" 
										class="btn btn-sm btn-success"
										data-toggle="modal" 
										data-target="#add"
										@click="resetForm()"
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
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<label for="">{{ $t('name') }} <small class="text-danger">({{ $t('required') }})</small></label>
													<input type="text" class="form-control" v-model="form.name" :placeholder="$t('name')" required>
												</div>
												<div class="form-group">
													<label for="">{{ $t('relation') }}</label>
													<input type="text" class="form-control" v-model="form.relation" :placeholder="$t('relation')">
												</div>
												<div class="form-group">
													<label for="">{{ $t('phone') }}</label>
													<input type="text" class="form-control" v-model="form.phone" placeholder="0171 xxx xxx">
												</div>
												<div class="form-group">
													<label for="">{{ $t('nid_number') }}</label>
													<input type="text" class="form-control" v-model="form.nid_number" :placeholder="$t('nid_number')">
												</div>
												<div class="form-group">
													<label for="">{{ $t('address') }}</label>
													<textarea rows="1" class="form-control" v-model="form.address" :placeholder="$t('address')"></textarea>
												</div>
												<div class="form-group">
													<label for="">{{ $t('main_referrer') }}</label>
													<select v-model="form.main_referrer" class="form-control">
														<option value="1">{{ $t('yes') }}</option>
														<option value="0">{{ $t('no') }}</option>
													</select>
												</div>
											</div>
											<div class="modal-footer justify-content-between">
												<button type="button" class="btn btn-default" ref="addModal" data-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
												<div>
													<button type="submit" :disabled="loading" @click="(add_another=false)" class="btn btn-success mr-1">
														<i v-if="!loading" class="fas fa-save"></i>
														<i v-if="loading" class="fas fa-circle-notch fa-spin"></i> {{ $t('post') }}
													</button>
													<button type="submit" :disabled="loading" @click="(add_another=true)" class="btn btn-success">
														<i v-if="!loading" class="fas fa-save"></i>
														<i v-if="loading" class="fas fa-circle-notch fa-spin"></i> {{ $t('post_and_add_another') }}
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
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">

												<div class="form-group">
													<label for="">{{ $t('name') }} <small class="text-danger">({{ $t('required') }})</small></label>
													<input type="text" class="form-control" v-model="form.name" :placeholder="$t('name')" required>
												</div>
												<div class="form-group">
													<label for="">{{ $t('relation') }}</label>
													<input type="text" class="form-control" v-model="form.relation" :placeholder="$t('relation')">
												</div>
												<div class="form-group">
													<label for="">{{ $t('phone') }}</label>
													<input type="text" class="form-control" v-model="form.phone" placeholder="0171 xxx xxx">
												</div>
												<div class="form-group">
													<label for="">{{ $t('nid_number') }}</label>
													<input type="text" class="form-control" v-model="form.nid_number" :placeholder="$t('nid_number')">
												</div>
												<div class="form-group">
													<label for="">{{ $t('address') }}</label>
													<textarea rows="1" class="form-control" v-model="form.address" :placeholder="$t('address')"></textarea>
												</div>
												<div class="form-group">
													<label for="">{{ $t('main_referrer') }}</label>
													<select v-model="form.main_referrer" class="form-control">
														<option value="1">{{ $t('yes') }}</option>
														<option value="0">{{ $t('no') }}</option>
													</select>
												</div>

											</div>
											<div class="modal-footer justify-content-between">
												<button type="button" class="btn btn-default" ref="editModal" data-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
												<button type="submit" :disabled="loading" class="btn btn-success mr-1">
													<i v-if="!loading" class="fas fa-save"></i>
													<i v-if="loading" class="fas fa-circle-notch fa-spin"></i> {{ $t('update_post') }}
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
											<th>{{ $t('relation') }}</th>
											<th>{{ $t('phone') }}</th>
											<th>{{ $t('nid_number') }}</th>
											<th>{{ $t('address') }}</th>
											<th>{{ $t('main_referrer') }}</th>
											<th>{{ $t('action') }}</th>
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
											<td>{{ index + pagination.from }}</td>
											<td>{{ list.name?list.name:'---' }}</td>
											<td>{{ list.relation?list.relation:'---' }}</td>
											<td>{{ list.phone?list.phone:'---' }}</td>
											<td>{{ list.nid_number?list.nid_number:'---' }}</td>
											<td>{{ list.address?list.address:'---' }}</td>
											<td>
												<h6 v-if="list.main_referrer == 1"><span class="badge badge-success">{{ $t('yes') }}</span></h6>
												<h6 v-else><span class="badge badge-secondary">{{ $t('no') }}</span></h6>
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
										<tr v-else>
											<td colspan="8">
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

export default {
	components: {
		Pagination
	},
	data() {
		return {
			toast: useToast(),
			staffInfo: {},
			// form
			form: {
				id: '',
				name: '',
				relation: '',
				phone: '',
				nid_number: '',
				address: '',
				main_referrer: 0,
				staff_id: ''
			},
			add_another: false,

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
				staff_id: '',

			},
			rowNumber: 1,
		}
	},
	created() {
		if(this.$route.params && this.$route.params.id){
			this.pagination.staff_id = this.$route.params.id;
			this.form.staff_id = this.$route.params.id;
            this.getData();
        }
	},
	methods: {
		getData() {
			this.listLoading = true;
			let params = this.pagination;
			axios.get('/api/staff-references', { params })
				.then((response) => {
					this.staffInfo = response.data.staff_info;
					this.lists = response.data.lists.data;
					this.pagination.currentPage = response.data.lists.current_page;
					this.pagination.from = response.data.lists.from;
					this.pagination.total = response.data.lists.total;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
			this.listLoading = false;
		},
		resetForm() {
			this.form.id = ''; 
			this.form.name = ''; 
			this.form.relation = ''; 
			this.form.phone = ''; 
			this.form.nid_number = ''; 
			this.form.address = ''; 
			this.form.main_referrer = 0;
		},
		createData() {
			// loading
			this.loading = true;
			// insertion
			axios.post('/api/staff-references', this.form)
				.then((response) => {
					this.toast.success(this.$t(response.data.message));
					this.getData();
					if(!this.add_another){
						this.$refs.addModal.click();
					}
					this.resetForm();
				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() => {
					this.loading = false
				})
		},
		editModal(list) {
			this.form = { ...this.form, ...list};
		},
		updateData() {
			// loading
			this.loading = true;
			// insertion
			axios.put('/api/staff-references/' + this.form.id, this.form)
				.then((response) => {

					this.toast.success(this.$t(response.data.message));
					this.getData();
					this.$refs.editModal.click();
					this.resetForm();
				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() => {
					this.loading = false
				})
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
				let response = await axios.delete('/api/staff-references/' + id);
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
		pagiAction(number){
			this.pagination.currentPage = number;
			this.getData();
		}

	},
}
</script>
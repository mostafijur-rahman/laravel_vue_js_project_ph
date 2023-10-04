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
										data-toggle="modal" 
										data-target="#add"
										@click="createEditModal(null, null)"
										>
										<i class="fa fa-plus"></i> {{ $t('create') }}
									</button>
								</div>
							</div>

							<div class="modal fade" id="showCreateEditModal">
								<div class="modal-dialog modal-xl">
									<div class="modal-content">
										<form @submit.prevent="createUpdate()">
											<div class="modal-header">
												<h4 class="modal-title">{{ $t(formName) }}</h4>
												<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label for="">{{ $t('account_type') }}</label>
															<select 
																v-model="form.type" 
																class="form-control" 
																:class="{ 'is-invalid': (validationErrors && validationErrors['account_type'])?true:false }"
																required>
																<option value="bank">{{ $t('bank') }}</option>
																<option value="cash">{{ $t('cash') }}</option>
															</select>
															<span
																class="error invalid-feedback"
																v-if="(validationErrors && validationErrors['account_type'])?true:false"
																v-for="(error) in validationErrors['account_type']"
																>{{ error }}
															</span>
														</div>
													</div>
													<div class="col-md-4" v-if="form.type == 'bank'">
														<div class="form-group">
															<label for="">{{ $t('bank_selection') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<select 
																v-model="form.bank_id" 
																class="form-control" 
																:class="{ 'is-invalid': (validationErrors && validationErrors['bank_id'])?true:false }"
																required>
																<option value="">{{ $t('please_select') }}</option>
																<option v-if="(settingBanks.length > 0)" v-for="bank in settingBanks" :value="bank.id">{{ bank.name }}</option>
															</select>
															<span
																class="error invalid-feedback"
																v-if="(validationErrors && validationErrors['bank_id'])?true:false"
																v-for="(error) in validationErrors['bank_id']"
																>{{ error }}
															</span>
														</div>
													</div>
													<div class="col-md-4" v-if="form.type == 'bank'">
														<div class="form-group">
															<label>{{ $t('holder_name') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<input 
																type="text" 
																class="form-control"
																:class="{ 'is-invalid': (validationErrors && validationErrors['holder_name'])?true:false }"
																v-model="form.holder_name" 
																:placeholder="$t('holder_name')" 
																required>
															<span
																class="error invalid-feedback"
																v-if="(validationErrors && validationErrors['holder_name'])?true:false"
																v-for="(error) in validationErrors['holder_name']"
																>{{ error }}
															</span>
														</div>
													</div>
													<div class="col-md-4" v-if="form.type == 'bank'">
														<div class="form-group">
															<label>{{ $t('account_number') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<input 
																type="text" 
																class="form-control" 
																:class="{ 'is-invalid': (validationErrors && validationErrors['account_number'])?true:false }"
																v-model="form.account_number" 
																:placeholder="$t('account_number')" 
																required>
															<span
																class="error invalid-feedback"
																v-if="(validationErrors && validationErrors['account_number'])?true:false"
																v-for="(error) in validationErrors['account_number']"
																>{{ error }}
															</span>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label>{{ $t('user_name') }} <small class="text-danger">({{ $t('required') }})</small></label>
															<input 
																type="text" 
																class="form-control" 
																:class="{ 'is-invalid': (validationErrors && validationErrors['user_name'])?true:false }"
																v-model="form.user_name" 
																:placeholder="$t('user_name')" 
																required>
															<span
																class="error invalid-feedback"
																v-if="(validationErrors && validationErrors['user_name'])?true:false"
																v-for="(error) in validationErrors['user_name']"
																>{{ error }}
															</span>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label>{{ $t('note') }}</label>
															<textarea rows="1" class="form-control" :placeholder="$t('write_name_here')" v-model="form.note"></textarea>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for="">{{ $t('status') }}</label>
															<select v-model="form.status" class="form-control">
																<option value="active">{{ $t('active') }}</option>
																<option value="inactive">{{ $t('inactive') }}</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="modal-footer justify-content-between">
												<button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
												
												<div v-if="form.id == ''">
													<button type="submit" :disabled="submitLoading" @click="(addAnother=false)" class="btn btn-success mr-1">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('post') }}
													</button>
													<button type="submit" :disabled="submitLoading" @click="(addAnother=true)" class="btn btn-success">
														<i v-bind:class="submitBtnIcon"></i> {{ $t('post_and_add_another') }}
													</button>
												</div>
												<div v-else>
													<button type="submit" :disabled="submitLoading" @click="(addAnother=false)" class="btn btn-success mr-1">
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
											<th>{{ $t('bank_name') }}</th>
											<th>{{ $t('account_number') }}</th>
											<th>{{ $t('holder_name') }}</th>
											<th>{{ $t('user_name') }}</th>
											<th>{{ $t('balance') }}</th>
											<th>{{ $t('status') }}</th>
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

											<td>{{ list.bank_id?list.bank_info.name:$t('cash_account') }}</td>
											<td>{{ list.account_number?list.account_number:'---' }}</td>
											<td>{{ list.holder_name?list.holder_name:'---' }}</td>
											<td>{{ list.user_name?list.user_name:'---' }}</td>
											<td>{{ $numFormat(list.balance) }}</td>
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
													@click="createEditModal(list, index)"
													>
													<i class="fas fa-edit"></i>
												</button>
												<button
													:disabled="list.transections && list.transections.length > 0"
													type="button" 
													@click="deleteModal(list.id, index)"
													class="btn btn-xs bg-gradient-danger" 
													:title="list.transections && list.transections.length > 0 ?$t('account_has_transection'):$t('delete')"
													>
													<i class="fas fa-trash"></i>
												</button>
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
			listLoading: false,
			lists: {},
			settingBanks: {},
			formName: 'create_form',

			// form
			form: {
				index: '',
				id: '',
				type: 'bank',
				bank_id: '',
				holder_name: '',
				user_name: '',
				account_number: '',
				note: '',
				status: 'active',
			},
			filter: {
				name: '',
			},
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
			searchOptions:[
				"perPage",
				"orderType",
                "name",
			],
			validationErrors: {},
		}
	},
	created() {
		this.getData();
		this.getSettingBanks();
	},
	methods: {
		getData() {
			this.listLoading = true;
			let params = { ...this.pagination, ...this.filter };
			axios.get('/api/accounts', { params })
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
		createEditModal(list, index) {
			if(list && list.id){
				this.form.index = index;
				this.form.id = list.id;
				this.form.type = list.type;
				this.form.bank_id = list.bank_id;
				this.form.holder_name = list.holder_name;
				this.form.user_name = list.user_name;
				this.form.account_number = list.account_number;
				this.form.note = list.note;
				this.form.status = list.status;

				this.formName = 'edit_form';

			} else {
				this.resetForm();
				this.formName = 'create_form';
			}
			$("#showCreateEditModal").modal("show");
		},
		createUpdate(){

			this.submitLoading = true;
			this.validationErrors = {};
			var request;
			// update
			if(this.form.id){
				request = axios.put('/api/accounts/' + this.form.id, this.form)
			// insert
			} else {
				request = axios.post('/api/accounts', this.form)
			}
			request.then((response) => {

				// if update attempt
				if(this.form.id){
					
					this.lists[this.form.index] = response.data.data;
					this.resetForm();
					$("#showCreateEditModal").modal("hide");

				// if create attempt
				} else {

					this.lists.unshift(response.data.data);
					this.toast.success(this.$t(response.data.message));

					if(!this.addAnother){
						this.resetForm();
						$("#showCreateEditModal").modal("hide");
					}
				}

			}).catch((error) => {
				this.toast.warning(error.message);
				if (error.response.status == 422){
					this.validationErrors = error.response.data.errors;
				}
			}).finally(() => {
				this.submitLoading = false
			})
		},
		pagiAction(number){
			this.pagination.currentPage = number;
			this.getData();
		},
		getSettingBanks(){
            axios.get('/api/common/setting-banks')
				.then((response) => {
					this.settingBanks = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
		resetForm() {
			this.form.index = '';
			this.form.id = '';
			this.form.type = 'bank';
			this.form.bank_id = '';
			this.form.holder_name = '';
			this.form.user_name = '';
			this.form.account_number = '';
			this.form.note = '';
			this.form.status = 'active';

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
				let response = await axios.delete('/api/accounts/' + id);
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
	},
	computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
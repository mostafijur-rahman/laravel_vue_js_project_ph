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
										@click="showClientModal"
										>
										<i class="fa fa-plus"></i> {{ $t('create') }}
									</button>
								</div>
							</div>
							<div class="card-body table-responsive p-0">
								<table class="table table-striped text-center table-hover">
									<thead>
										<tr>
											<th class="text-left">#</th>
											<th class="text-left">{{ $t('name') }}</th>
											<th class="text-left">{{ $t('address') }}</th>
											<th class="text-right">{{ $t('balance') }}</th>
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
											<td class="text-left">
												{{ index + pagination.from }}
											</td>
											<td class="text-left">
												{{ list.name?list.name:'---' }} <br>
												<small>{{ list.phone?list.phone:'' }}</small>
											</td>
											<td class="text-left">
												<small>
													<b>{{ list.address?list.address:'---' }}</b><br>
													<span v-if="list.note">
														{{ $t('note') }} : {{ list.note }}<br>
													</span>
												</small>
											</td>
											<td class="text-right">
												<small>
													<span :class="list.total_loan_sum>0?'text-danger':''">{{ $t('loan') }}/{{ $t('will_give') }} = <b>{{ $numFormat(list.total_loan_sum) }}</b></span><br>
													{{ $t('advance') }}/{{ $t('will_get') }} = <b>{{ $numFormat(list.previous_advance) }}</b>
												</small>
											</td> 
											<td>
												<h6 v-if="list.status == 'active'"><span class="badge badge-success">{{ $t('active') }}</span></h6>
												<h6 v-else><span class="badge badge-danger">{{ $t('inactive') }}</span></h6>
											</td>
											<td>
												<button
													type="button" 
													class="btn btn-xs bg-gradient-primary mr-1"
													:title="$t('edit')"
													data-toggle="modal" 
													data-target="#edit"
													@click="showClientModal(index, list)"
													>
													<i class="fas fa-edit"></i>
												</button>
												<button 
													v-if="!list.bills.length > 0 || !list.payments.length > 0" 
													type="button" 
													@click="deleteModal(list.id, index)"
													class="btn btn-xs bg-gradient-danger" 
													:title="$t('delete')"
													:disabled="!list.delete_status"
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

		<showClientModal ref="cRef" @refresh="refresh"/>
	</div>
</template>

<script>

import { useToast } from "vue-toastification";
import Swal from 'sweetalert2';
import Pagination from 'v-pagination-3';

import showClientModal from '../../components/commons/ClientModal.vue';
import SearchFilterModal from '../../components/commons/SearchFilterModal.vue';


export default {
	components: {
		Pagination,
		showClientModal,
		SearchFilterModal
	},
	data() {
		return {
			toast: useToast(),

			// list
			submitLoading: false,
			listLoading: false,
			lists: {},
			indexId: '',

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
			searchOptions: ["name"]
		}
	},
	created() {
		this.getData();
	},
	methods: {
		getData() {
			this.listLoading = true;
			let params = { ...this.pagination, ...this.filterParams };
			axios.get('/api/clients', { params })
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
				await axios.delete('/api/clients/' + id);
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




		// show modal
		showClientModal(indexId = null, list = null){
			this.indexId = indexId;

			// reset
			this.$refs.cRef.resetForm();

			if(list){
				// set expense modal form data
				this.$refs.cRef.form.id = list.id;
				this.$refs.cRef.form.name = list.name?list.name:'';
				this.$refs.cRef.form.phone = list.phone?list.phone:'';
				this.$refs.cRef.form.address = list.address?list.address:'';
				this.$refs.cRef.form.status = list.status?list.status:'active';
				this.$refs.cRef.form.note = list.note?list.note:'';

				this.$refs.cRef.form.previous_loan = list.previous_loan?list.previous_loan:0;
				this.$refs.cRef.form.loan_date = list.loan_date?list.loan_date:'';

				this.$refs.cRef.form.previous_advance = list.previous_advance?list.previous_advance:0;
				this.$refs.cRef.form.advance_date = list.advance_date?list.advance_date:'';
			}

			$("#showClientModal").modal("show");
		},

		// refresh
		refresh(){
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
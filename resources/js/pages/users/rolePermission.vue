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
                                    <router-link
                                        to="/users/role"
                                        class="btn btn-sm btn-secondary mr-1">
                                        <i class="fas fa-arrow-left"></i> {{ $t('back') }}
                                    </router-link>
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-info mr-1">
                                        <i class="fas fa-sync-alt"></i> {{ $t('refresh') }}
                                    </button>
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-success">
                                        <i class="fa fa-upload"></i> {{ $t('update') }}
                                    </button>
                                </div>
							</div>
				
							<div class="card-body table-responsive p-0">
								<table class="table table-striped text-center table-hover">
									<thead>
										<tr>
											<th style="width:5%">#</th>
											<th>{{ $t('module_name') }}</th>
											<th>{{ $t('create') }}</th>
											<th>{{ $t('read') }}</th>
											<th>{{ $t('update') }}</th>
											<th>{{ $t('delete') }}</th>
										</tr>
									</thead>
									<tbody>
										<tr v-if="(lists.length > 0)" v-for="(list, index) in lists">
											<td>{{ ++index }}</td>
											<td>Module name will here</td>
											<td><input type="checkbox" checked="" onclick="return false"></td>
											<td><input type="checkbox" onclick="return false"></td>
											<td><input type="checkbox" onclick="return false"></td>
											<td><input type="checkbox" onclick="return false"></td>
										</tr>
										<tr v-else>
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
import Pagination from 'v-pagination-3';

export default {
	components: {
		Pagination
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

			// list
			isListLoaded: false,
			lists: {},

			filter: {
				name: '',
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
			let params = { ...this.pagination, ...this.filter };
			axios.get('/api/user/activities', { params })
				.then((response) => {
					this.lists = response.data.lists.data;
					this.pagination.currentPage = response.data.lists.current_page;
					this.pagination.from = response.data.lists.from;
					this.pagination.total = response.data.lists.total;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
		pagiAction(number){
			this.pagination.currentPage = number;
			this.getData();
		}

	},
}
</script>
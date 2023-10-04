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
									</div>
							</div>
							<div class="card-body table-responsive p-0">
								<table class="table table-striped text-center table-hover">
									<thead>
										<tr>
											<th style="width:5%">#</th>
											<th>{{ $t('user') }}</th>
											<th>{{ $t('event') }}</th>
											<th>{{ $t('system') }}</th>
											<th>{{ $t('location') }}</th>
											<th>{{ $t('date_time') }}</th>
										</tr>
									</thead>
									<tbody>
										<tr v-if="(lists.length > 0)" v-for="(list, index) in lists">
											<td>{{ index + pagination.from }}</td>
											<td>
												<small>
													{{ list.causer_id ? list.causer.first_name : '---' }} <br>
													<span v-if="list.causer_id">
														{{ $t('role') }} : <b>{{ (list.causer.roles && list.causer.roles.length > 0)?list.causer.roles[0]['name']:'---' }}</b>
													</span>
												</small>
											</td>
											<td>
												{{ list.description }} <br>
												<button
													:title="$t('details')"
													@click="showDetailsModal(list.properties)"
													class="btn btn-xs btn-info">{{ $t('details') }}</button>
											</td>
											<td>
												<small>
													{{ $t('browser') }} : <b>{{ (list.properties && list.properties.attributes && list.properties.attributes.browser)?list.properties.attributes.browser.browserName:'---' }}</b><br>
													{{ $t('operating_system') }} : <b>{{ (list.properties && list.properties.attributes && list.properties.attributes.browser)?list.properties.attributes.browser.platformName:'---' }}</b>
												</small>
											</td>
											<td>
												<small>
													{{ $t('ip') }} : <b>{{ (list.properties && list.properties.attributes && list.properties.attributes.ip)?list.properties.attributes.ip:'---' }}</b><br>
													{{ $t('location') }} : <b>{{ (list.properties && list.properties.attributes && list.properties.attributes.location)?list.properties.attributes.location:'---' }}</b>
												</small>
											</td>
											<td>{{ $getHumanDateTime(list.created_at) }}</td>
										</tr>
										<tr v-else>
											<td colspan="9"><h5 class="text-danger">{{ $t('no_data') }}</h5></td>
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

		<!-- details modal -->
		<div class="modal fade" id="showDetailsModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('details') }}</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
								{{ properties }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

	</div>
</template>

<script>
import { useToast } from "vue-toastification";
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

			// list
			isListLoaded: false,
			lists: {},
			users: {},

			filter: {
				user_id: ''
			},

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

			// filter
			filterParams: {},
			searchOptions:[
				"perPage",
				"orderType"
			],
			properties: {}
		}
	},
	created() {
		this.getData();
	},
	methods: {
		getData() {
			let params = { ...this.pagination, ...this.filter };
			axios.get('/api/user-activities', { params })
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
		showDetailsModal(properties){
			this.properties = properties
			$("#showDetailsModal").modal("show");
		}

	},
}
</script>
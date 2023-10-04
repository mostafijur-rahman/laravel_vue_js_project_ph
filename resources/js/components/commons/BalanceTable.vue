<template>
    <div>
        <div class="row">
            <div class="col-md-12 table-responsive p-0">
                <table class="table table-striped text-center table-hover text-nowrap">                              
                    <thead>
                        <tr>
                            <th width="5%">{{ $t('sl') }}</th>
                            <th>{{ $t('bank_name') }}</th>
                            <th>{{ $t('account_number') }}</th>
                            <th>{{ $t('holder_name') }}</th>
                            <th>{{ $t('user_name') }}</th>
                            <th>{{ $t('balance') }}</th>
                            <th>{{ $t('status') }}</th>
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

                            <td>{{ list.bank_id?list.bank_info.name:$t('cash_account') }}</td>
                            <td>{{ list.account_number?list.account_number:'---' }}</td>
                            <td>{{ list.holder_name?list.holder_name:'---' }}</td>
                            <td>{{ list.user_name?list.user_name:'---' }}</td>
                            <td><b>{{ $numFormat(list.balance) }}</b></td>
                            <td>
                                <h6 v-if="list.status == 'active'"><span class="badge badge-success">{{ $t('active') }}</span></h6>
                                <h6 v-else><span class="badge badge-danger">{{ $t('inactive') }}</span></h6>
                            </td>
                        </tr>
                        <tr v-if="(lists.length > 0)">
                            <td colspan="5" class="text-right"><b>{{ $t('total') }}</b></td>
                            <td><b>{{ $numFormat($getSumFromLists(lists, 'balance')) }}</b></td>
                            <td></td>
                        </tr>
                        <tr v-if="(lists.length == 0)">
                            <td colspan="7"><h5 class="text-danger">{{ $t('no_data') }}</h5></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <pagination
                    v-model="pagination.currentPage"
                    :records="pagination.total"
                    :per-page="pagination.perPage" 
                    @paginate="pagiAction"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { useToast } from "vue-toastification";
import Pagination from 'v-pagination-3';

export default {

    name: 'BalanceTable',
    components: {
		Pagination
	},
	data() {
		return {
            toast: useToast(),
            listLoading: false,
            lists: {},

            pagination: {
				paginate: true,
				perPage: 20,
				orderBy: 'id',
				orderType: 'DESC',

				currentPage: 1,
				from: 1,
				total: 0,
			},
			filter: {
				name: ''
			},
		}
	},
    created() {
	},
	methods: {
		getTest() {
			this.lists = {};
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
        pagiAction(number){
			this.pagination.currentPage = number;
			this.getTest();
		},
    },
    computed: {}
}
</script>
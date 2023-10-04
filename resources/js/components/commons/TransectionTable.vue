<template>
    <div>
        <div class="row">
            <div class="col-md-12 table-responsive p-0">
                <table class="table table-striped text-center table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th class="text-left">
                                {{ $t('transection_details') }}
                            </th>
                            <th class="text-left">
                                {{ $t('account_details') }}
                            </th>
                            <th>{{ $t('cash_in') }}</th>
                            <th>{{ $t('cash_out') }}</th>
                            <!-- <th>{{ $t('action') }}</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="(listLoading == true)">
                            <td colspan="5">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border"></div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="(lists.length > 0)" v-for="(list, index) in lists">
                            <td>{{ index + pagination.from }}</td>
                            <td class="text-left">
                                <small>
                                    {{ $t('id') }} : <b>{{ list.encrypt }}</b><br>
                                    {{ $t('date') }} : <b>{{ $getHumanDate(list.date_time) }}</b><br>
                                    {{ $t('reason') }} : <b>{{ list.transactionable_type? $t(list.transactionable_type) :'---' }} {{ list.transactionable_id? '(' + list.transactionable_id + ')':'' }}</b><br>
                                    {{ $t('posted_by') }} : <b>{{ list.created_by.first_name }}</b>
                                </small>
                            </td>
                            <td class="text-left">
                                <small>
                                    <b>{{ (list.account && list.account.bank_info )? list.account.bank_info.name : $t('cash_account') }} <span class="text-primary">({{ $numFormat(list.account.balance) }})</span></b><br>

                                    <span v-if="list.account && list.account.account_number">
                                        {{ $t('account_number') }} : <b>{{ list.account.account_number }}</b><br>
                                    </span>
                                    <span v-if="list.account && list.account.holder_name">
                                        {{ $t('holder_name') }} : <b>{{ list.account.holder_name }}</b><br>
                                    </span>
                                    {{ $t('user_name') }} : <b>{{ list.account.user_name ? list.account.user_name:'---' }}</b><br>
                                    <span v-if="list.note">({{ list.note }})</span>
                                </small>
                            </td>
                            <td><b class="text-success">{{ list.type=='in'? $numFormat(list.amount) :'---' }}</b></td>
                            <td><b class="text-danger">{{ list.type=='out'? $numFormat(list.amount) :'---' }}</b></td>
                            <!-- <td>
                                <button
                                    v-if="list.transactionable_type == 'account_trans'"
                                    type="button" 
                                    @click="deleteModal(list.id, index)"
                                    class="btn btn-xs bg-gradient-danger" 
                                    >
                                    <i class="fas fa-exchange-alt"></i> {{ $t('roll_back') }}
                                </button>
                                <span v-else>---</span>
                            </td> -->
                        </tr>
                        <tr v-if="(lists.length == 0)">
                            <td colspan="5"><h5 class="text-danger">{{ $t('no_data') }}</h5></td>
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

    name: 'TransectionTable',
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
			axios.get('/api/account-transactions', { params })
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
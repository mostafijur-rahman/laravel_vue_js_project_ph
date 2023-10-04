<template>

    <div class="row">

        <div class="col-md-3"  v-if="searchOptions.includes('perPage')">
            <div class="form-group">
                <select class="form-control" v-model="filter.perPage">
                    <option value="50">{{ $t('50') }}</option>
                    <option value="100">{{ $t('100') }}</option>
                    <option value="500">{{ $t('500') }}</option>
                    <option value="1000">{{ $t('1000') }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3"  v-if="searchOptions.includes('orderType')">
            <div class="form-group">
                <select class="form-control" v-model="filter.orderType">
                    <option value="ASC">{{ $t('from_start') }}</option>
                    <option value="DESC">{{ $t('from_end') }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('timeRange')">
            <div class="form-group">
                <select v-model="filter.range_status" class="form-control">
                    <option value="all_time">{{ $t('all_time') }}</option>
                    <option value="monthly_report">{{ $t('monthly_report') }}</option>
                    <option value="yearly_report">{{ $t('yearly_report') }}</option>
                    <option value="date_wise">{{ $t('date_wise') }}</option>                                
                </select>
            </div>
        </div>
        <div 
            class="col-md-3"
            v-if="searchOptions.includes('timeRange') && filter.range_status == 'monthly_report'"
            >
            <div class="form-group">
                <select v-model="filter.month" class="form-control">
                    <option value="">{{ $t('select_month') }}</option>
                    <option value="1">{{ $t('january') }}</option>
                    <option value="2">{{ $t('february') }}</option>
                    <option value="3">{{ $t('march') }}</option>
                    <option value="4">{{ $t('april') }}</option>
                    <option value="5">{{ $t('may') }}</option>
                    <option value="6">{{ $t('june') }}</option>
                    <option value="7">{{ $t('july') }}</option>
                    <option value="8">{{ $t('august') }}</option>
                    <option value="9">{{ $t('september') }}</option>
                    <option value="10">{{ $t('october') }}</option>
                    <option value="11">{{ $t('november') }}</option>
                    <option value="12">{{ $t('december') }}</option>
                </select>
            </div>
        </div>
        <div 
            class="col-md-3"
            v-if="searchOptions.includes('timeRange') && filter.range_status == 'monthly_report' || filter.range_status == 'yearly_report'"
            >
            <div class="form-group">
                <select v-model="filter.year" class="form-control">
                    <option value="">{{ $t('select_year') }}</option>
                    <option v-for="year in years" :value="year">{{ year }}</option>
                </select>
            </div>
        </div>
        <div 
            class="col-md-3"
            v-if="searchOptions.includes('timeRange') && filter.range_status == 'date_wise'"
            >
            <div class="form-group">
                <input type="date" class="form-control" v-model="filter.daterange">
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('account')">
            <div class="form-group">
                <select v-model="filter.account_id" class="form-control">
                    <option value="">{{ $t('all_accounts') }}</option>
                    <option v-if="(accounts.length > 0)" v-for="account in accounts" :value="account.id">{{ account.user_name }} ({{ account.balance }})</option>
                </select>
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('bank')">
            <div class="form-group">
                <select v-model="filter.bank_id" class="form-control">
                    <option value="">{{ $t('all_banks') }}</option>
                    <option v-if="(settingBanks.length > 0)" v-for="bank in settingBanks" :value="bank.id">{{ bank.name }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('challanNumber')">
            <div class="form-group">
                <input type="text" class="form-control" v-model="filter.challan_number" :placeholder="$t('write_challan_number_here')">
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('name')">
            <div class="form-group">
                <input type="text" class="form-control" v-model="filter.name" :placeholder="$t('write_name_here')">
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('phone')">
            <div class="form-group">
                <input type="number" class="form-control" v-model="filter.phone" :placeholder="$t('write_phone_here')">
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('voucherNumber')">
            <div class="form-group">
                <input type="text" class="form-control" v-model="filter.voucher_number" :placeholder="$t('write_voucher_number_here')">
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('transactionId')">
            <div class="form-group">
                <input type="text" class="form-control" v-model="filter.transaction_id" :placeholder="$t('write_transaction_id_here')">
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('expense')">
            <div class="form-group">
                <select v-model="filter.expense_id" class="form-control">
                    <option value="">{{ $t('all_expense') }}</option>
                    <option v-if="(settingExpenses.length > 0)" v-for="expense in settingExpenses" :value="expense.id">{{ expense.name }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('vendor')">
            <div class="form-group">
                <select v-model="filter.vendor_id" class="form-control">
                    <option value="">{{ $t('all_vendor') }}</option>
                    <option v-if="(vendors.length > 0)" v-for="vendor in vendors" :value="vendor.id">{{ vendor.name }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('ownVehicle')">
            <div class="form-group">
                <select v-model="filter.vehicle_id" class="form-control">
                    <option value="">{{ $t('all_vehicle') }}</option>
                    <option v-if="(vehicles.length > 0)" v-for="vehicle in vehicles" :value="vehicle.id">{{ vehicle.number_plate }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3" v-if="searchOptions.includes('client')">
            <div class="form-group">
                <select v-model="filter.client_id" class="form-control">
                    <option value="">{{ $t('all_company') }}</option>
                    <option v-if="(clients.length > 0)" v-for="client in clients" :value="client.id">{{ client.name }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3"  v-if="searchOptions.includes('paidStatus')">
            <div class="form-group">
                <select class="form-control" v-model="filter.paid_status">
                    <option value="all">{{ $t('all') }}</option>
                    <option value="paid">{{ $t('paid') }}</option>
                    <option value="due">{{ $t('due') }}</option>
                </select>
            </div>
        </div>

        <div class="col-md-5">
            <div class="form-group">
                <button 
                    type="button" 
                    class="btn btn-sm btn-primary mr-1"
                    :disabled="submitLoading"
                    @click="sendParams"
                    >
                    <i class="fa fa-search"></i> {{ $t('search') }}
                </button>
                <button 
                    type="button" 
                    class="btn btn-sm btn-secondary mr-1"
                    @click="resetFilter"
                    >
                    <i class="fa fa-filter"></i> {{ $t('reset_filter') }}
                </button>

                <!-- conditional button -->
                <router-link
                    v-if="searchOptions.includes('challanCreate')"
                    to="/deposit-challans/create"
                    class="btn btn-sm btn-success mr-1"
                    >
                    <i class="fa fa-plus"></i> {{ $t('create') }}
                </router-link >
                <button
                    v-if="searchOptions.includes('modalCreate1')"
                    type="button" 
                    class="btn btn-sm btn-success mr-1"
                    @click="createAction"
                    >
                    <i class="fa fa-plus"></i> {{ $t('create') }}
                </button>

            </div>
        </div>

    </div>

</template>

<script>

export default {
    name: 'SearchFilter',
    props: {
        searchOptions: {type: Array, default: []},

        // common dropdown
        accounts: { type: [Object], default: {}},
        settingBanks: { type: [Object], default: {}},
        vehicles: { type: [Object], default: {}},
        clients: { type: [Object], default: {}},
        vendors: { type: [Object], default: {}},
        settingExpenses: { type: [Object], default: {}},
        
        submitLoading: { type: [Boolean], default: false},
    },
	data() {
		return {
            filter: {},
		}
	},
    created() {
        this.setFilter();
    },
    mounted() {},
	methods: {
        setFilter(){
            if(this.searchOptions.includes('perPage')){
                this.filter.perPage = 50;
            }
            if(this.searchOptions.includes('orderBy')){
                this.filter.orderBy = 'id';
            }
            if(this.searchOptions.includes('orderType')){
                this.filter.orderType = 'DESC';
            }
            if(this.searchOptions.includes('timeRange')){
                this.filter.range_status = 'all_time';
                this.filter.month = '';
                this.filter.year = '';
                this.filter.daterange = '';
            }
            if(this.searchOptions.includes('account')){
                this.filter.account_id = '';
            }
            if(this.searchOptions.includes('bank')){
                this.filter.bank_id = '';
            }
            if(this.searchOptions.includes('challanNumber')){
                this.filter.challan_number = '';
            }
            if(this.searchOptions.includes('name')){
                this.filter.name = '';
            }
            if(this.searchOptions.includes('phone')){
                this.filter.phone = '';
            }
            if(this.searchOptions.includes('voucherNumber')){
                this.filter.voucher_number = '';
            }
            if(this.searchOptions.includes('transactionId')){
                this.filter.transaction_id = '';
            }
            if(this.searchOptions.includes('expense')){
                this.filter.expense_id = '';
            }
            if(this.searchOptions.includes('ownVehicle')){
                this.filter.vehicle_id = '';
            }
            if(this.searchOptions.includes('client')){
                this.filter.client_id = '';
            }
            if(this.searchOptions.includes('vendor')){
                this.filter.vendor_id = '';
            }
            if(this.searchOptions.includes('paidStatus')){
                this.filter.paid_status = 'all';
            }

		},
        resetFilter(){
            this.setFilter();
            this.$emit("sendParams", this.filter);
		},
		sendParams(){
			this.$emit("sendParams", this.filter);
		},
        createAction(){
			this.$emit("sendCreateAction");
		},
    },
	computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        },
		years: function(){
			let years = [];
			for(var i = new Date().getFullYear(); i > 2010; i--) { 
				years.push(i);
			}
			return years;
		}
    }
}
</script>
<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ filterName }}</h3>
            </div>
            <div class="card-body">
                
                <div class="row">
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
                            <VueDatePicker 
                                v-model="filter.daterange"
                                :placeholder="$t('select_date')"
                                :enable-time-picker="false"
                                range
                                format="dd/MM/yyyy"
                                text-input
                                @update:model-value="handleDate"
                                >
                            </VueDatePicker>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="searchOptions.includes('account')">
                        <div class="form-group">
                            <select v-model="filter.account_id" class="form-control">
                                <option value="">{{ $t('all_accounts') }}</option>
                                <option v-if="(accounts.length > 0)" v-for="account in accounts" :value="account.id">
                                    <span v-if="account.bank_id">
                                        {{ account.account_number }} - ({{ account.bank_info.name }}) = ({{ $numFormat(account.balance) }})
                                    </span>
                                    <span v-else>
                                        {{ account.user_name }} = ({{ $numFormat(account.balance) }})
                                    </span>
                                </option>
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
                    <div class="col-md-3" v-if="searchOptions.includes('expense')">
                        <div class="form-group">
                            <select v-model="filter.expense_id" class="form-control">
                                <option value="">{{ $t('all_expense') }}</option>
                                <option v-if="(settingExpenses.length > 0)" v-for="expense in settingExpenses" :value="expense.id">{{ expense.name }}</option>
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
                    <div class="col-md-3" v-if="searchOptions.includes('voucherNumber')">
                        <div class="form-group">
                            <input type="text" class="form-control" v-model="filter.voucher_number" :placeholder="$t('write_voucher_number_here')">
                        </div>
                    </div>
                    <div class="col-md-3" v-if="searchOptions.includes('rentalVehicle')">
                        <div class="form-group">
                            <input type="text" class="form-control" v-model="filter.vehicle_number" placeholder="DHK-9091">
                        </div>
                    </div>
                    <div class="col-md-3" v-if="searchOptions.includes('ownVehicle')">
                        <div class="form-group">
                            <select v-model="filter.own_vehicle_id" class="form-control">
                                <option value="">{{ $t('all_vehicle') }}</option>
                                <option v-if="(vehicles.length > 0)" v-for="vehicle in vehicles" :value="vehicle.id">{{ vehicle.number_plate }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="searchOptions.includes('ownVehicleLedgerType')">
                        <div class="form-group">
                            <select class="form-control" v-model="filter.own_vehicle_ledger_type">
                                <option value="deposit_">{{ $t('deposit') }} {{ $t('report') }}</option>
                                <option value="depositexpense_">{{ $t('deposit') }} - {{ $t('expense') }} {{ $t('report') }}</option>
                                <option v-if="(settingExpenses.length > 0)" v-for="expense in settingExpenses" :value="'expense_' + expense.id">{{ expense.name }} ({{ $t('expense') }})</option>
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
                    <div class="col-md-3" v-if="searchOptions.includes('vendor')">
                        <div class="form-group">
                            <select v-model="filter.vendor_id" class="form-control">
                                <option value="">{{ $t('all_vendor') }}</option>
                                <option v-if="(vendors.length > 0)" v-for="vendor in vendors" :value="vendor.id">{{ vendor.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="searchOptions.includes('pdfSize')">
                        <div class="form-group">
                            <select class="form-control" v-model="filter.size">
                                <option value="A4-L">{{ $t('a4') }} - {{ $t('landscape') }}</option>
                                <option value="A4-P">{{ $t('a4') }} - {{ $t('portrait') }}</option>
                                <option value="Legal-P">{{ $t('legal') }} - {{ $t('portrait') }}</option>
                                <option value="Legal-L">{{ $t('legal') }} - {{ $t('landscape') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="searchOptions.includes('pdfHeader')">
                        <div class="form-group">
                            <select class="form-control" v-model="filter.page_header">
                                <option value="only_company_name_in_header">{{ $t('only_company_name_in_header') }}</option>
                                <option value="all_info_in_header">{{ $t('all_info_in_header') }}</option>
                                <option value="">{{ $t('blank_header') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="searchOptions.includes('calculation')">
                        <div class="form-group">
                            <select class="form-control" v-model="filter.calculation">
                                <option value="hide">{{ $t('hide_calculation') }}</option>
                                <option value="show">{{ $t('show_calculation') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <button 
                                type="button" 
                                class="btn btn-sm btn-secondary mr-1"
                                @click="resetFilter"
                                >
                                <i v-bind:class="resetFilterBtnIcon"></i> {{ $t('reset_filter') }}
                            </button>
                            <button
                                type="button" 
                                class="btn btn-sm btn-info mr-1"
                                :disabled="submitLoading"
                                @click="showReport"
                                >
                                <i v-bind:class="showReportBtnIcon"></i> {{ $t('show_report') }}
                            </button>
                            <button
                                type="button" 
                                class="btn btn-sm btn-primary"
                                :disabled="submitLoading"
                                @click="download"
                                >
                                <i v-bind:class="downloadBtnIcon"></i> {{ $t('download') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    name: 'SearchFilterCard',
    components: { 
        VueDatePicker 
    },
    props: {
        searchOptions: {type: Array, default: []},

        filterName: { type: [String], default: ''},
        apiLink: { type: [String], default: ''},

        // common dropdown
        accounts: { type: [Object], default: {}},
        settingBanks: { type: [Object], default: {}},
        settingExpenses: { type: [Object], default: {}},
        vehicles: { type: [Object], default: {}},
        clients: { type: [Object], default: {}},
        vendors: { type: [Object], default: {}},
        vendorType: { type: [String], default: ''},
    },
	data() {
		return {
            filter: {},
            apiLinkWithQuery : '',
            submitLoading: false
		}
	},
    created() {
        this.setFilter();
    },
    mounted() {},
	methods: {
        setFilter(){

            this.searchOptions.includes('perPage') ? this.filter.perPage = 50 : this.filter.perPage = '';
            this.searchOptions.includes('orderType') ? this.filter.orderType = 'ASC' : this.filter.orderType = '';

            if(this.searchOptions.includes('timeRange')){
                this.filter.range_status = 'all_time';
                this.filter.month = '';
                this.filter.year = '';
                this.filter.daterange = [moment().format('YYYY/MM/DD'), moment().days(7).format('YYYY/MM/DD')];
            }

            this.searchOptions.includes('account') ? this.filter.account_id = '' : this.filter.account_id = '';
            this.searchOptions.includes('bank') ? this.filter.bank_id = '' : this.filter.bank_id = '';
            this.searchOptions.includes('challanNumber') ? this.filter.challan_number = '' : this.filter.challan_number = '';
            this.searchOptions.includes('ownVehicle') ? this.filter.own_vehicle_id = '' : this.filter.own_vehicle_id = '';
            this.searchOptions.includes('ownVehicleLedgerType') ? this.filter.own_vehicle_ledger_type = 'deposit_' : this.filter.own_vehicle_ledger_type = '';
            this.searchOptions.includes('rentalVehicle') ? this.filter.vehicle_number = '' : this.filter.vehicle_number = '';
            this.searchOptions.includes('name') ? this.filter.name = '' : this.filter.name = '';
            this.searchOptions.includes('voucherNumber') ? this.filter.voucher_number = '' : this.filter.voucher_number = '';
            this.searchOptions.includes('transactionId') ? this.filter.transaction_id = '' : this.filter.transaction_id = '';
            this.searchOptions.includes('expense') ? this.filter.expense_id = '' : this.filter.expense_id = '';
            this.searchOptions.includes('client') ? this.filter.client_id = '' : this.filter.client_id = '';
            this.searchOptions.includes('vendor') ? this.filter.vendor_id = '' : this.filter.vendor_id = '';
            this.searchOptions.includes('vendorType') ? this.filter.vendorType = this.vendorType : this.filter.vendorType = '';

            this.searchOptions.includes('paidStatus') ? this.filter.paid_status = 'all' : this.filter.paid_status = '';
            this.searchOptions.includes('pdfSize') ? this.filter.size = 'A4-L' : this.filter.size = '';
            this.searchOptions.includes('pdfHeader') ? this.filter.page_header = 'only_company_name_in_header' : this.filter.page_header = '';
            this.searchOptions.includes('calculation') ? this.filter.calculation = 'hide' : this.filter.calculation = '';
		},
        resetFilter(){
            this.setFilter();
		},
        showReport(){
            this.submitLoading = true;
			this.filter.output = 'stream';
            
            var queryLink = this.attachQueryWithLink();
            window.open(queryLink, '_blank');
            this.submitLoading = false;
		},
		download(){
            this.submitLoading = true;
			this.filter.output = 'download';
            var queryLink = this.attachQueryWithLink();
            window.open(queryLink, '_self');
            this.submitLoading = false;
		},
        attachQueryWithLink(){
            return this.apiLink 
                + '?per_page=' + this.filter.perPage
                + '&order_type=' + this.filter.orderType
                + '&range_status=' + this.filter.range_status
                + '&month=' + this.filter.month
                + '&year=' + this.filter.year
                + '&daterange=' + this.filter.daterange
                + '&account_id=' + this.filter.account_id
                + '&bank_id=' + this.filter.bank_id
                + '&challan_number=' + this.filter.challan_number
                + '&own_vehicle_id=' + this.filter.own_vehicle_id
                + '&own_vehicle_ledger_type=' + this.filter.own_vehicle_ledger_type
                + '&vehicle_number=' + this.filter.vehicle_number
                + '&name=' + this.filter.name
                + '&voucher_number=' + this.filter.voucher_number
                + '&transaction_id=' + this.filter.transaction_id
                + '&expense_id=' + this.filter.expense_id
                + '&client_id=' + this.filter.client_id
                + '&vendor_id=' + this.filter.vendor_id
                + '&vendor_type=' + this.filter.vendorType
                + '&paid_status=' + this.filter.paid_status
                + '&size=' + this.filter.size
                + '&page_header=' + this.filter.page_header
                + '&output=' + this.filter.output
                + '&calculation=' + this.filter.calculation;
        },
        handleDate(value){
            // dateValue = Tue Jun 06 2023 00:00:00 GMT+0600 (Bangladesh Standard Time)
            // need to convert dateValue raw value  into our expected date format
            this.filter.daterange = [moment(value[0]).format('YYYY/MM/DD'), moment(value[1]).format('YYYY/MM/DD')];
        }
    },
	computed: {
        downloadBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fa fa-cloud-download-alt';
        },
        showReportBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fa fa-file-alt';
        },
        resetFilterBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fa fa-filter';
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
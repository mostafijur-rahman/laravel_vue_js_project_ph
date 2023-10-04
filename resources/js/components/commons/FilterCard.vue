<template>
    <div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ filterName }}</h3>
            </div>
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-3"  v-if="searchOptions.includes('orderBy')">
                        <div class="form-group">
                            <select class="form-control" v-model="filter.order_by">
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
                    <div class="col-md-3" v-if="searchOptions.includes('ownVehicle')">
                        <div class="form-group">
                            <select v-model="filter.own_vehicle_id" class="form-control">
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
                    <div class="col-md-4">
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

export default {
    name: 'FilterCard',
    props: {
        searchOptions: {type: Array, default: []},
        filterName: { type: [String], default: ''},

        // common dropdown
        accounts: { type: [Object], default: {}},
        settingBanks: { type: [Object], default: {}},
        vehicles: { type: [Object], default: {}},
        clients: { type: [Object], default: {}},
        vendors: { type: [Object], default: {}},
        
        submitLoading: { type: [Boolean], default: false},
    },
	data() {
		return {
			filter: {
				range_status: 'all_time',
				month: '',
				year: '',
				daterange: '',
				account_id: '',
				bank_id: '',
				challan_number: '',
				own_vehicle_id: '',
				client_id: '',
				vendor_id: '',
				size: 'A4-L',
				page_header: 'only_company_name_in_header',
				download: false,
				order_by: 'ASC',
			},
		}
	},
    created() {},
    mounted() {},
	methods: {
        resetFilter(){
			this.filter.range_status = 'all_time';
			this.filter.month = '';
			this.filter.year = '';
			this.filter.daterange = '';
			this.filter.account_id = '';
			this.filter.bank_id = '';
			this.filter.challan_number = '';
			this.filter.own_vehicle_id = '';
			this.filter.client_id = '';
			this.filter.vendor_id = '';
			this.filter.size = 'A4-P';
			this.filter.page_header = 'only_company_name_in_header';
			this.filter.download = false;
			this.filter.order_by = 'ASC';
		},
		showReport(){
			this.filter.download = false;
			this.$emit("sendFilterParams", this.filter);
		},
		download(){
			this.filter.download = true;
			this.$emit("sendFilterParams", this.filter);
		},
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
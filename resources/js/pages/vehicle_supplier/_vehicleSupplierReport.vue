<template>
	<div class="content-wrapper">
		<section class="content-header"></section>

		<section class="content">
			<div class="container-fluid">

				<FilterCard
					:filterName="firstFormat"
					:vehicles="vehicles"
					:clients="clients"
					:vendors="vendors"

					:searchOptions="searchOptions"
					:submitLoading="submitLoading"
					@sendFilterParams="billingSummaryFirstFormat"
					/>

				<FilterCard
					:filterName="secondFormat"
					:vendors="vendors"

					:searchOptions="secondSearchOptions"
					:submitLoading="submitLoading"
					@sendFilterParams="loanSummaryFirstFormat"
					/>
			</div>
		</section>

	</div>
</template>

<script>
import FilterCard from '../../components/commons/FilterCard.vue';
import moment from 'moment';

export default {
	components: {
		FilterCard
	},
	data() {
		return {
			vehicles: {},
			clients: {},
			vendors: {},
			
			submitLoading: false,
			firstFormat:  this.$t('billing') + ' ' + this.$t('summary') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			searchOptions: [
                "orderBy",
                "timeRange",
                "challanNumber",
                "ownVehicle",
                "vendor",

                "pdfSize",
                "pdfHeader",
            ],
			filterParams: {},

			secondFormat:  this.$t('loan') + ' ' + this.$t('summary') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			secondSearchOptions: [
                "orderBy",
                "timeRange",
                "vendor",

                "pdfSize",
                "pdfHeader",
            ],

		}
	},
	created() {
        this.getAccounts();
		this.getSettingBanks();
		this.getVehicles();
		this.getClients();
		this.getVendors();
	},
	methods: {
        getAccounts(){
			axios.get('/api/common/accounts')
				.then((response) => {
					this.accounts = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
		getSettingBanks(){
            axios.get('/api/common/setting-banks')
				.then((response) => {
					this.settingBanks = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
		getVehicles() {
			axios.get('/api/common/vehicles')
				.then((response) => {
					this.vehicles = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
		getClients() {
            axios.get('/api/common/clients')
				.then((response) => {
					this.clients = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
		getVendors(){
			let params = { 'type': 'other' };
			axios.get('/api/common/vendors', { params })
				.then((response) => {
					this.vendors = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
		// setFilterParams(params){
		// 	this.filterParams = params;
		// 	this.exportPdf();
		// },
		billingSummaryFirstFormat(params){

			this.filterParams = params;
			this.submitLoading = true;

			axios({
				url: "/api/vendor-billing-first-format",
				method: "get",
				params: this.filterParams,
				responseType: "blob",
			}).then((response) => {

				var fileURL = window.URL.createObjectURL(new Blob([response.data]));
				var fileLink = document.createElement("a");
				fileLink.href = fileURL;

				var filename = 'billing_summary' + '_' + moment().format('DD/MM/Y_h_mm_a') + '.pdf';

				fileLink.setAttribute("download", filename);
				document.body.appendChild(fileLink);
				fileLink.click();

			}).finally(() => {
				this.submitLoading = false;
			});
		},

		loanSummaryFirstFormat(params){

			this.filterParams = params;
			this.submitLoading = true;

			axios({
				url: "/api/vendor-loan-first-format",
				method: "get",
				params: this.filterParams,
				responseType: "blob",
			}).then((response) => {

				var fileURL = window.URL.createObjectURL(new Blob([response.data]));
				var fileLink = document.createElement("a");
				fileLink.href = fileURL;

				var filename = 'loan_summary' + '_' + moment().format('DD/MM/Y_h_mm_a') + '.pdf';

				fileLink.setAttribute("download", filename);
				document.body.appendChild(fileLink);
				fileLink.click();

			}).finally(() => {
				this.submitLoading = false;
			});
		},

		
	},
}
</script>
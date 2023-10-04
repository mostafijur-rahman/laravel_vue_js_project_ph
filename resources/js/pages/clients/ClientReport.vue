<template>
	<div class="content-wrapper">
		<section class="content-header"></section>

		<section class="content">
			<div class="container-fluid">

				<SearchFilterCard
					:filterName="firstFormat"
					apiLink="/api/client-billing-first-format"
					:searchOptions="searchOptions"
					
					:vehicles="vehicles"
					:clients="clients"
					/>

				<SearchFilterCard
					:filterName="secondFormat"
					apiLink="/api/client-payment-first-format"
					:searchOptions="secondSearchOptions"
					
					:vehicles="vehicles"
					:clients="clients"
					/>
			</div>
		</section>

	</div>
</template>

<script>
import SearchFilterCard from '../../components/commons/SearchFilterCard.vue';

export default {
	components: {
		SearchFilterCard
	},
	data() {
		return {
			vehicles: {},
			clients: {},
			vendors: {},
			
			submitLoading: false,
			filterParams: {},

			firstFormat:  this.$t('billing') + ' ' + this.$t('summary') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			searchOptions: [
                "orderBy",
                "timeRange",
                "challanNumber",
                "ownVehicle",
                "rentalVehicle",
                "client",

                "pdfSize",
                "pdfHeader",
            ],

			secondFormat:  this.$t('payment') + ' ' + this.$t('summary') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			secondSearchOptions: [
				"orderBy",
                "timeRange",
                "challanNumber",
                "ownVehicle",
                "rentalVehicle",
                "client",

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
		// this.getVendors();
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
			let params = { 'type': 'pump' };
			axios.get('/api/common/vendors', { params })
				.then((response) => {
					this.vendors = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
	},
}
</script>
<template>
	<div class="content-wrapper">
		<section class="content-header"></section>

		<section class="content">
			<div class="container-fluid">
				<SearchFilterCard
					:filterName="firstFormat"
					apiLink="/api/vendor-billing-first-format"
					:searchOptions="searchOptions"

					:vehicles="vehicles"
					:vendors="vendors"
					vendorType="other"
					/>

				<SearchFilterCard
					:filterName="secondFormat"
					apiLink="/api/vendor-loan-first-format"
					:searchOptions="secondSearchOptions"

					:vehicles="vehicles"
					:vendors="vendors"
					vendorType="other"
					/>

				<SearchFilterCard
					:filterName="thirdFormat"
					apiLink="/api/vendor-loan-and-payment-first-format"
					:searchOptions="thirdSearchOptions"

					:vehicles="vehicles"
					:vendors="vendors"
					vendorType="other"
					/>
			</div>
		</section>

	</div>
</template>

<script>
import SearchFilterCard from '../../components/commons/SearchFilterCard.vue';
import moment from 'moment';

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
			firstFormat:  this.$t('billing') + ' ' + this.$t('summary') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			searchOptions: [
                "orderBy",
                "timeRange",
                "challanNumber",
                "ownVehicle",
                "vendor",
                "vendorType",

                "pdfSize",
                "pdfHeader",
            ],
			filterParams: {},

			secondFormat:  this.$t('loan') + ' ' + this.$t('summary') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			secondSearchOptions: [
                "orderBy",
                "timeRange",
				"challanNumber",
                "ownVehicle",
                "vendor",
				"vendorType",

                "pdfSize",
                "pdfHeader",
            ],

			thirdFormat:  this.$t('loan') + ' ' + this.$t('and') + ' ' + this.$t('payment') + ' ' + this.$t('summary') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			thirdSearchOptions: [
                "orderBy",
                "timeRange",
				"challanNumber",
                "ownVehicle",
                "vendor",
				"vendorType",

                "pdfSize",
                "pdfHeader",
            ],
		}
	},
	created() {
		this.getVehicles();
		this.getClients();
		this.getVendors();
	},
	methods: {
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
	},
}
</script>
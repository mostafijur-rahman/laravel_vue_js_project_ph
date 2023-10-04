<template>
	<div class="content-wrapper">
		<section class="content-header"></section>

		<section class="content">
			<div class="container-fluid">
				<SearchFilterCard
					:filterName="firstFormat"
					apiLink="/api/deposit-challan-first-format"
					:searchOptions="searchOptions"

					:vehicles="vehicles"
					:clients="clients"
					/>
			</div>
		</section>

	</div>
</template>

<script>
import SearchFilterCard from '../../../components/commons/SearchFilterCard.vue';

export default {
	components: {
		SearchFilterCard
	},
	data() {
		return {
			vehicles: {},
			clients: {},
			
			firstFormat:  this.$t('deposit_challan') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			searchOptions: [
                "orderBy",
                "timeRange",
                "challanNumber",
                "ownVehicle",
                "client",
                "pdfSize",
                "pdfHeader",
            ],
			filterParams: {}
		}
	},
	created() {
		this.getVehicles();
		this.getClients();
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
		}
	},
}
</script>
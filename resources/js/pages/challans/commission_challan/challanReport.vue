<template>
	<div class="content-wrapper">
		<section class="content-header"></section>
		<section class="content">
			<div class="container-fluid">
				<SearchFilterCard
					:filterName="firstFormat"
					apiLink="/api/commission-challan-first-format"
					:searchOptions="searchOptions"

					:clients="clients"
					:vendors="vendors"
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
			clients: {},
			vendors: {},
			
			submitLoading: false,
			firstFormat:  this.$t('commission_challan') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			searchOptions: [
                "orderType",
                "timeRange",
                "challanNumber",
                "rentalVehicle",
                "vendor",
                "client",

                "pdfSize",
                "pdfHeader",
            ],
			filterParams: {},
		}
	},
	created() {
		this.getClients();
		this.getVendors();
	},
	methods: {
		getClients() {
            axios.get('/api/common/clients')
				.then((response) => {
					this.clients = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
		getVendors(){
			let params = { 'type': 'vehicle_supplier' };
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
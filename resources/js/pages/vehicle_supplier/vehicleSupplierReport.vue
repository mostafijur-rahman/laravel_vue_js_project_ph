<template>
	<div class="content-wrapper">
		<section class="content-header"></section>

		<section class="content">
			<div class="container-fluid">
				<SearchFilterCard
					:filterName="firstFormat"
					apiLink="/api/vehicle-supplier-billing-first-format"
					:searchOptions="searchOptions"
					
					:vendors="vendors"
					/>

				<SearchFilterCard
					:filterName="secondFormat"
					apiLink="/api/vehicle-supplier-payment-first-format"
					:searchOptions="secondSearchOptions"
					
					:vendors="vendors"
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
			vendors: {},
			
			submitLoading: false,
			filterParams: {},

			firstFormat:  this.$t('billing') + ' ' + this.$t('summary') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			searchOptions: [
                "orderBy",
                "timeRange",
                "challanNumber",
				"voucherNumber",
                "rentalVehicle",
                "vendor",

                "pdfSize",
                "pdfHeader",
            ],

			secondFormat:  this.$t('payment') + ' ' + this.$t('summary') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			secondSearchOptions: [
				"orderBy",
                "timeRange",
                "challanNumber",
				"voucherNumber",
                "rentalVehicle",
                "vendor",

                "pdfSize",
                "pdfHeader",
            ],

		}
	},
	created() {
        this.getVendors();
	},
	methods: {
		getVendors(){
            let params = { type: 'vehicle_supplier' };
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
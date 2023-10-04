<template>
	<div class="content-wrapper">
		<section class="content-header"></section>

		<section class="content">
			<div class="container-fluid">

				<FilterCard
					:filterName="firstFormat"
					:vehicles="vehicles"
					:clients="clients"
					:searchOptions="searchOptions"
					:submitLoading="firstFormatSubmitLoading"
					@sendFilterParams="setFilterParams"
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
			
			firstFormatSubmitLoading: false,
			firstFormat:  this.$t('challan') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
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
        this.getAccounts();
		this.getSettingBanks();
		this.getVehicles();
		this.getClients();
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
		setFilterParams(params){
			this.filterParams = params;
			this.exportPdf();
		},
		exportPdf(){
			this.firstFormatSubmitLoading = true;

			axios({
				url: "/api/challan-first-format",
				method: "get",
				params: this.filterParams,
				responseType: "blob",
			}).then((response) => {

				var fileURL = window.URL.createObjectURL(new Blob([response.data]));
				var fileLink = document.createElement("a");
				fileLink.href = fileURL;

				var filename = 'challan_summary' + '_' + moment().format('DD/MM/Y_h_mm_a') + '.pdf';

				fileLink.setAttribute("download", filename);
				document.body.appendChild(fileLink);
				fileLink.click();

				// if(this.filterParams.download == true){

				// 	fileLink.setAttribute("download", filename);
				// 	// fileLink.setAttribute("target",'_blank');
				// 	document.body.appendChild(fileLink);
				// 	fileLink.click();

				// } else {
				// 	// window.open(filename, '_blank').focus();
				// 	window.open(fileURL+'.pdf', "_blank");
				// }
			});

			this.firstFormatSubmitLoading = false;
			},
	},
}
</script>
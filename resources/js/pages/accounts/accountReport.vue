<template>
	<div class="content-wrapper">
		<section class="content-header"></section>

		<section class="content">
			<div class="container-fluid">

				<SearchFilterCard
					:filterName="firstFormat"
					apiLink="/api/account-first-format"
					:searchOptions="searchOptions"

					:accounts="accounts"
					:settingBanks="settingBanks"
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
			// common data
			accounts: {},
			settingBanks: {},

			submitLoading: false,
			firstFormat:  this.$t('transaction') +' '+ this.$t('report') +' '+ this.$t('form') +' ('+ this.$t('first_format') +')',
			searchOptions: [
                "timeRange",
                "account",
                "bank",
                "pdfSize",
                "pdfHeader",
                "orderBy",
            ],
			filterParams: {}
		}
	},
	created() {
        this.getAccounts();
		this.getSettingBanks();
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
		setFilterParams(params){
			this.filterParams = params;
			this.exportPdf();
		},
	},
}
</script>
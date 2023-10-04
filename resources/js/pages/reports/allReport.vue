<template>
	<div class="content-wrapper">
		<section class="content-header"></section>

		<section class="content">
			<div class="container-fluid">

				<SearchFilterCard
					:filterName="depositExpenseFormat"
					apiLink="/api/reports/nagadan-or-deposit-expense-first-format"
					:searchOptions="depositExpenseSearchOptions"
					
					:vehicles="vehicles"
					/>

                <SearchFilterCard
					:filterName="vehicleLedgerFormat"
					apiLink="/api/reports/vehicle-ledger-first-format"
					:searchOptions="vehicleLedgerSearchOptions"
					
					:vehicles="vehicles"
					:settingExpenses="settingExpenses"
					/>

                <SearchFilterCard
					:filterName="expenseLedgerFormat"
					apiLink="/api/reports/expense-ledger-first-format"
					:searchOptions="expenseLedgerSearchOptions"
					
					:vehicles="vehicles"
					:settingExpenses="settingExpenses"
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
			settingExpenses: {},
			
			submitLoading: false,
			depositExpenseFormat:  this.$t('nagadan') + ' / ' + this.$t('deposit_expense') + ' '+ this.$t('report'),
			depositExpenseSearchOptions: [
                "orderBy",
                "timeRange",
                "ownVehicle",

                "pdfSize",
                "pdfHeader",
                "calculation",
            ],

            vehicleLedgerFormat:  this.$t('vehicle_ledger') + ' ' + this.$t('report'),
			vehicleLedgerSearchOptions: [
                "orderBy",
                "timeRange",
                "ownVehicle",
                "ownVehicleLedgerType",

                "pdfSize",
                "pdfHeader",
            ],

            expenseLedgerFormat:  this.$t('expense_ledger') + ' ' + this.$t('report'),
			expenseLedgerSearchOptions: [
                "orderBy",
                "timeRange",
				"ownVehicle",
                "expense",

                "pdfSize",
                "pdfHeader",
            ],
		}
	},
	created() {
		this.getVehicles();
		this.getSettingExpenses();
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
        getSettingExpenses(){
            axios.get('/api/common/setting-expenses')
				.then((response) => {
					this.settingExpenses = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
        },
	},
}
</script>
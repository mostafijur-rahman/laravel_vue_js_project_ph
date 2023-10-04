<template>
    <div>
        <div class="modal fade" id="showCurrentBalanceModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

					<div class="modal-body">
						<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
							<li class="nav-item mr-2" role="presentation">
								<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
									{{ $t('balance') }}
								</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
									{{ $t('statement') }}
								</button>
							</li>
						</ul>
						<div class="tab-content" id="pills-tabContent">
							<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
								<BalanceTable ref="balanceRef" />
							</div>
							<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
								<TransectionTable ref="transectionRef" />
							</div>
						</div>
					</div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

import BalanceTable from '../commons/BalanceTable.vue';
import TransectionTable from '../commons/TransectionTable.vue';

export default {
    name: 'CurrentBalanceModal',
	components: {
		BalanceTable,
		TransectionTable,
	},
	data() {
		return {
		}
	},
    created() {

	},
    mounted() {},
	methods: {
		getBalance(){
			this.$refs.balanceRef.getTest();
		},
		getTransection(){
			this.$refs.transectionRef.getTest();
		}
    },
    computed: {}
}
</script>
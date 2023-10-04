<template>
    <div>
        <div class="modal fade" id="showClientModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form @submit.prevent="addClient()">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('add_contract_rent') }}</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!-- <input type="hidden" v-model="form.challan_id" value="1" required> -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">{{ $t('company') }}<small class="text-danger">({{ $t('required') }})</small></label>
                                        <select class="form-control" v-model="form.client_id" required>
                                            <option value="">{{ $t('please_select') }}</option>
                                            <option value="1">Akij Group</option>
                                            <option value="2">Jamuna Group</option>
                                            <option value="3">Akij Group</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('contract_rent') }}</label>
                                        <input type="number" class="form-control" v-model="form.contract_fare">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('advance_received') }}</label>
                                        <input type="number" class="form-control" v-model="form.advance_received" required>
                                    </div>
                                </div>

                                <template v-if="form.advance_received > 0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ $t('received_account') }}</label>
                                            <select v-model="form.received_account_id" class="form-control">
                                                <option value="">{{ $t('please_select') }}</option>
                                                <option v-if="(accounts.length > 0)" v-for="account in accounts" :value="account.id">{{ account.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ $t('advance_received_date') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                            <input type="date" class="form-control" v-model="form.advance_received_date" required>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
                            <button type="submit" :disabled="submitLoading" class="btn btn-success">
                                <i v-bind:class="submitBtnIcon"></i> {{ $t(submitBtnName) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    name: 'showClientModal',
    props: {
        challanId: Number
    },

    // props: {
    //     duplicateData: {
    //         type: [Array, Object],
    //         default: [],
    //     },
    // },
	data() {
		return {
            submitLoading: false,
            submitBtnName: 'post',

            // for client
            form: {
				id: '',
				challan_id: '',
				client_id: '',
				contract_fare: 0,

                // advance
				advance_received: 0,
				received_account_id: '',
				advance_received_date: '',
			},
            accounts: {}
		}
	},
    created() {
        
    },
    mounted(){

    },
    watch: {
        challanId: function(value){
            this.form.challan_id = value
        }
    },
	methods: {
        addClient(){
			// loading
			this.submitLoading = true;
			// insertion
            axios
                .post('/api/client-challans', this.form)
                .then((response) => {
                    this.toast.success(this.$t(response.data.message));
                    this.resetForm();
                    $("#showClientModal").modal("hide");
                }).catch((error) => {
                    this.toast.warning(error.message);
                }).finally(() => {
                    this.submitLoading = false
            })
		},
        resetForm(){
            this.form.id = '';
            this.form.challan_id = '';
            this.form.client_id = '';
            this.form.contract_fare = 0;

            this.form.advance_received = 0;
            this.form.received_account_id = '';
            this.form.advance_received_date = '';
        },
	},
    computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
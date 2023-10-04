<template>
    <div>
        <div class="modal fade" id="showCounterBookingModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('counter_booking') }}</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body" v-if="form.challan_id">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped text-center table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">{{ $t('sl') }}</th>
                                            <th>{{ $t('name_of_counter') }}</th>
                                            <th>{{ $t('passenger_qty') }}</th>
                                            <th>{{ $t('total_taka') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="(listLoading == true)">
											<td colspan="7">
												<div class="d-flex justify-content-center">
													<div class="spinner-border"></div>
												</div>
											</td>
										</tr>
                                        <tr v-if="(generalExpenses.length > 0)" v-for="(expense, expenseIndex) in generalExpenses">
                                            
                                            <td>{{ expenseIndex + 1 }}</td>
                                            <td>Counter Name</td>
                                            <td>
                                                <input type="number" value="10">
                                            </td>
                                            <td>
                                                <input type="number" value="1000">
                                            </td>

                                        </tr>
                                        <tr v-if="(generalExpenses.length == 0)">
											<td colspan="4"><h5 class="text-danger">{{ $t('no_data') }}</h5></td>
										</tr>
                                        <tr>
                                            <td colspan="2" class="text-right">{{ $t('total') }} =</td>
                                            <td>0000</td>
                                            <td>0000</td>
                                       </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
                        <div v-if="!form.id">
                            <button type="submit" :disabled="submitLoading" @click="addAnother=false" class="btn btn-success mr-1">
                                <i v-bind:class="submitBtnIcon"></i> {{ $t('post') }}
                            </button>
                            <button type="submit" :disabled="submitLoading" @click="addAnother=true" class="btn btn-success">
                                <i v-bind:class="submitBtnIcon"></i> {{ $t('post_and_add_another') }}
                            </button>
                        </div>
                        <div v-else>
                            <button type="submit" :disabled="submitLoading" @click="addAnother=false" class="btn btn-success mr-1">
                                <i v-bind:class="submitBtnIcon"></i> {{ $t('update_post') }}
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useToast } from "vue-toastification";
import Swal from 'sweetalert2';

export default {
    name: 'CounterBookingModal',
    props: {
        counters: { type: [Array], default: []},
    },
	data() {
		return {
            toast: useToast(),
            submitLoading: false,
            listLoading: false,
            generalExpenses: {},

            form: {
				challan_id: '',
            },
            
            filter: {
				challan_id: '',
                paginate: false
			},
            validationErrors: {},
            expenseLoading: false,
            vendorLoading: false,
            vehicleLoading: false
		}
	},
    created() {},
    mounted(){},
	methods: {
        addUpdate(){
			this.submitLoading = true;
			this.validationErrors = {};
            var request;

			// update
			if(this.form.id){
				request = axios.put('/api/expense-generals/' + this.form.id, this.form)
			// insert
			} else {
				request = axios.post('/api/expense-generals', this.form)
			}

            request
                .then((response) => {

                    if(this.form.challan_id){
                        this.$emit('refreshGeneralExpense', this.filter.challan_id);
                        this.generalExpenses.push(response.data.data);
                    } else {
                        this.$emit('refreshGeneralExpense');
                    }

                    if(!this.addAnother){
                        this.resetForm();
						$("#showGeneralExpenseModal").modal("hide");
					}

                    this.toast.success(this.$t(response.data.message));

                }).catch((error) => {

                    if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                    }
                    this.submitLoading = false
                }).finally(() => {
                    this.submitLoading = false
                })
		},
        getData(parameters) {
            this.listLoading = true;
            this.generalExpenses = {};

            this.form.challan_id = parameters.challanId;
            this.filter.challan_id = parameters.challanId;
            let params = this.filter;

            axios.get('/api/expense-generals', { params })
				.then((response) => {
					this.generalExpenses = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() =>{
					this.listLoading = false;
				});
		}
	},
    computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
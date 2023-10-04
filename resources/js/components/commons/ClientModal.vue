<template>
    <div>
        <div class="modal fade" id="showClientModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('create_form') }}</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="addUpdate()">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('name') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                        <input type="text" class="form-control" v-model="form.name" :placeholder="$t('write_name_here')" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('phone') }}</label>
                                        <input type="text" class="form-control" v-model="form.phone" placeholder="0171 xxx xxx xxx">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('address') }}</label>
                                        <textarea class="form-control" rows="1" v-model="form.address" :placeholder="$t('write_here')" ></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('note') }}</label>
                                        <textarea rows="1" class="form-control" v-model="form.note" :placeholder="$t('write_here')"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">{{ $t('status') }}</label>
                                        <select v-model="form.status" class="form-control">
                                            <option value="active">{{ $t('active') }}</option>
                                            <option value="inactive">{{ $t('inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-2"><label>{{ $t('before_starting_keep_accounts_in_this_software') }}</label></h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('loan') }} / {{ $t('will_give') }}</label>
                                        <input type="number" class="form-control" v-model="form.previous_loan">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('date') }}</label>
                                        <VueDatePicker 
                                            v-model="form.loan_date"
                                            :placeholder="$t('select_date')"
                                            :enable-time-picker="false"
                                            format="dd/MM/yyyy"
                                            text-input
                                            >
                                        </VueDatePicker>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('advance') }} / {{ $t('will_get') }}</label>
                                        <input type="number" class="form-control" v-model="form.previous_advance">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ $t('date') }}</label>
                                        <VueDatePicker 
                                            v-model="form.advance_date"
                                            :placeholder="$t('select_date')"
                                            :enable-time-picker="false"
                                            format="dd/MM/yyyy"
                                            text-input
                                            >
                                        </VueDatePicker>
                                    </div>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useToast } from "vue-toastification";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    name: 'ClientModal',
    components: { 
        VueDatePicker 
    },
	data() {
		return {
            toast: useToast(),
            submitLoading: false,
            addAnother: false,

            form: {
				id: '',
				name: '',
				phone: '',
				address: '',
				note: '',
				status: 'active',

                previous_loan: 0,
				loan_date: '',

				previous_advance: 0,
				advance_date: '',
			},

		}
	},
	methods: {
        addUpdate(){
			this.submitLoading = true;
            var request;

			// update
			if(this.form.id){
				request = axios.put('/api/clients/' + this.form.id, this.form)
			// insert
			} else {
				request = axios.post('/api/clients', this.form)
			}

            request
                .then((response) => {

                    if(this.form.id){
                        this.$emit('refresh', this.form.id);
                    } else {
                        this.$emit('refresh');
                    }

                    if(!this.addAnother){
                        this.resetForm();
						$("#showClientModal").modal("hide");
					}

                    this.toast.success(this.$t(response.data.message));

                }).catch((error) => {
                    this.toast.error(this.$t(error.response.data.message));
                    this.submitLoading = false
                }).finally(() => {
                    this.submitLoading = false
                })
		},
        resetForm(){
            this.form.id = '';
            this.form.name = '';
            this.form.phone = '';
            this.form.address = '';
            this.form.note = '';
            this.form.status = 'active';

            this.form.previous_loan = 0;
            this.form.loan_date = '';

            this.form.previous_advance = 0;
            this.form.advance_date = '';
        }
	},
    computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
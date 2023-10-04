<template>
	<div class="content-wrapper">
		<section class="content-header"></section>
		<section class="content">
			<div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <form @submit.prevent="createEditData()">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title"> {{ $t('personal_information') }} <span v-if="pageType == 'edit'">({{ $t('edit_page') }})</span></h3>
                                    <router-link to="/staffs" class="btn btn-sm btn-secondary float-right"><i class="fa fa-arrow-left"></i> {{ $t('staff_list') }}</router-link>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('gender') }}</label>
                                                <select v-model="form.gender" class="form-control " required>
                                                    <option value="male">{{ $t('male') }}</option>
                                                    <option value="female">{{ $t('female') }}</option>
                                                    <option value="third">{{ $t('third_gender') }}</option>
                                                    <option value="other">{{ $t('other') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('name') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                                <input type="text" class="form-control" v-model="form.name" :placeholder="$t('name')" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('phone') }} <small class="text-danger">({{ $t('required') }})</small></label>
                                                <input type="text" class="form-control" v-model="form.phone" placeholder="0171 xxx xxx" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('email') }}</label>
                                                <input type="text" class="form-control" v-model="form.email" placeholder="examaple@gmail.com">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('father_name') }}</label>
                                                <input type="text" class="form-control" v-model="form.father_name" :placeholder="$t('father_name')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('mother_name') }}</label>
                                                <input type="text" class="form-control" v-model="form.mother_name" :placeholder="$t('mother_name')">
                                                                                        </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('spouse_name') }}</label>
                                                <input type="text" class="form-control" v-model="form.spouse_name" :placeholder="$t('spouse_name')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('present_address') }}</label>
                                                <textarea class="form-control" v-model="form.present_address" rows="1" :placeholder="$t('present_address')"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('permanent_address') }}</label>
                                                <textarea class="form-control " v-model="form.permanent_address"  rows="1" :placeholder="$t('permanent_address')"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('birth_date') }}</label>
                                                <input type="date" class="form-control" v-model="form.dob">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('blood_group') }}</label>
                                                <select class="form-control" v-model="form.blood_group">
                                                    <option value="A+">A+</option>
                                                    <option value="A-">A-</option>
                                                    <option value="B+">B+</option>
                                                    <option value="B-">B-</option>
                                                    <option value="AB+">AB+</option>
                                                    <option value="AB-">AB-</option>
                                                    <option value="o+">o+</option>
                                                    <option value="o-">o-</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('note') }}</label>
                                                <textarea class="form-control " id="note" name="note" rows="1" :placeholder="$t('note')"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="inputFile">{{ $t('photo') }}</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input " id="inputFile" name="image">
                                                        <label class="custom-file-label" for="inputFile">{{ $t('file_selection') }}</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">{{ $t('upload') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title"> {{ $t('document') }} <span v-if="pageType == 'edit'">({{ $t('edit_page') }})</span></h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('designation') }}</label>
                                                <select  v-model="form.designation_id"  class="form-control " required="">
                                                    <option value="">{{ $t('please_select') }}</option>
                                                    <option v-if="(designations.length > 0)" v-for="designation in designations" :value="designation.id">{{ $t(designation.name) }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('company_id') }}</label>
                                                <input type="text" class="form-control" v-model="form.company_id_number" :placeholder="$t('company_id')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('joining_date') }}</label>
                                                <input type="date" class="form-control" v-model="form.joining_date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('nid_number') }}</label>
                                                <input type="text" class="form-control" v-model="form.nid_number" :placeholder="$t('nid_number')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('driving_license_number') }}</label>
                                                <input type="text" class="form-control" v-model="form.driving_license_number" :placeholder="$t('driving_license_number')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('passport_number') }}</label>
                                                <input type="text" class="form-control" v-model="form.passport_number" :placeholder="$t('passport_number')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('birth_certificate_number') }}</label>
                                                <input type="text" class="form-control" v-model="form.birth_certificate_number" :placeholder="$t('birth_certificate_number')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('port_id') }}</label>
                                                <input type="text" class="form-control" v-model="form.port_id" :placeholder="$t('port_id')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('bank') }}</label>
                                                <select v-model="form.bank_id" class="form-control ">
                                                    <option value="">{{ $t('please_select') }}</option>
                                                    <option v-if="(banks.length > 0)" v-for="bank in banks" :value="bank.id">{{ bank.name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('bank_account_number') }}</label>
                                                <input type="text" class="form-control" v-model="form.bank_account_number" :placeholder="$t('bank_account_number')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('salary_amount') }}</label>
                                                <input type="number" class="form-control" v-model="form.salary_amount" :placeholder="$t('salary_amount')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ $t('termination_date') }}</label>
                                                <input type="date" class="form-control" v-model="form.termination_date">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" :disabled="submitLoading" class="btn btn-success">
                                        <i v-bind:class="submitBtnIcon"></i> {{ $t(submitBtnName) }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</section>
	</div>
</template>

<script>
import { useToast } from "vue-toastification";

export default {

	data() {
		return {
			toast: useToast(),
            submitLoading: false,
            pageType: 'create',
            submitBtnName: 'post',

			// form
			form: {
				id: '',
                
				gender: 'male',
				name: '',
				phone: '',
				email: '',
				father_name: '',
				mother_name: '',
				spouse_name: '',
				present_address: '',
				permanent_address: '',
				dob: '',
				blood_group: 'A+',
				status: 'active',

				company_id_number: '',
				joining_date: '',
				designation_id: 1,
				nid_number: '',
				driving_license_number: '',
				passport_number: '',
				birth_certificate_number: '',
				port_id: '',
				bank_id: 1,
				bank_account_number: '',
				salary_amount: '',
				termination_date: '',
				status: 'active',

			},

            banks: {},
            designations: {}
		}
	},
	created() {
        this.getBank();
        this.getDesignation();

        if(this.$route.params && this.$route.params.id){
            this.getEditData(this.$route.params.id);
            this.submitBtnName = 'update_post';
        }

	},
	methods: {
		createEditData() {
			// loading
			this.submitLoading = true;
			// insertion
            let test;

            if(this.pageType == 'create'){

                test = axios.post('/api/staffs', this.form)
                
            } else {

                test = axios.put('/api/staffs/'+ this.form.id, this.form)

            }

                test.then((response) => {
                    this.toast.success(this.$t(response.data.message));
				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() => {
					this.submitLoading = false
				})
		},
        resetForm(){
            // reset form
            this.form.gender = 'male'; 
            this.form.name = '';
            this.form.phone = '';
            this.form.email = '';
            this.form.father_name = '';
            this.form.mother_name = '';
            this.form.spouse_name = '';

            this.form.present_address = '';
            this.form.permanent_address = '';
            this.form.dob = '';
            this.form.blood_group = 'A+';

            this.form.company_id_number = '';
            this.form.joining_date = '';
            this.form.designation_id = 1;
            this.form.nid_number = '';
            this.form.driving_license_number = '';
            this.form.passport_number = '';
            this.form.birth_certificate_number = '';
            this.form.port_id = '';
            this.form.bank_id = 1;
            this.form.bank_account_number = '';
            this.form.salary_amount = '';
            this.form.termination_date = '';
            this.form.status = 'active';
        },
        getBank() {
			axios.get('/api/common/setting-banks')
				.then((response) => {
					this.banks = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
        getDesignation() {
			axios.get('/api/common/setting-designations')
				.then((response) => {
					this.designations = response.data.lists;
				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
        getEditData(id) {
			axios.get('/api/staffs/' + id)
				.then((response) => {
                    this.form = { ...this.form, ...response.data.data };
                    this.pageType = 'edit';

				}).catch((error) => {
					this.toast.warning(error.message);
				})
		},
	},
    computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
<template>
    <div>
        <div class="modal fade" id="showNoteModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('write_note_here') }}</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="add()">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea rows="2" class="form-control" v-model="form.note" :placeholder="$t('you_can_write_any_note_here')"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <select v-model="form.note_color" class="form-control">
                                                <option value="#000000">{{ $t('black') }}</option>
                                                <option value="#FF0000">{{ $t('red') }}</option>
                                                <option value="#008000">{{ $t('green') }}</option>
                                                <option value="#0000FF">{{ $t('blue') }}</option>
                                                <option value="#800000">{{ $t('maroon') }}</option>
                                                <option value="#FFFF00">{{ $t('yellow') }}</option>
                                                <option value="#808000">{{ $t('olive') }}</option>
                                                <option value="#00FF00">{{ $t('lime') }}</option>
                                                <option value="#00FFFF">{{ $t('aqua') }}</option>
                                                <option value="#000080">{{ $t('navy') }}</option>
                                                <option value="#800080">{{ $t('purple') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fas fa-times"></i> {{ $t('close') }}</button>
                            <div>
                                <button type="submit" :disabled="submitLoading" class="btn btn-sm btn-success mr-1">
                                    <i v-bind:class="submitBtnIcon"></i> {{ $t('post') }}
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

export default {
    name: 'NoteModal',
	data() {
		return {
            toast: useToast(),
            submitLoading: false,
            form: {
				id: '',
				note: '',
				note_color: '',
            },
            apiLink: '',
            validationErrors: {},
		}
	},
    created() {
        // console.log(this.items);
    },
    mounted(){},
	methods: {
        add(){
			this.submitLoading = true;
			this.validationErrors = {};

            axios
                .post(this.apiLink, this.form)
                .then((response) => {

                    $("#showNoteModal").modal("hide");
                    this.$emit('afterAdd', this.form.id);
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
        resetForm(){
            this.form.id = '';
            this.form.note = '';
            this.form.note_color = '#000000';
        },
	},
    computed: {
        submitBtnIcon: function() {
            return (this.submitLoading==true)?'fas fa-circle-notch fa-spin' : 'fas fa-save';
        }
    }
}
</script>
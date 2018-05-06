import AppMask from '../AppMask';
import FormMessage from "../FomMessage";
import FormMessageMixin from "../Mixins/FormMessageMixin";

export default {
    components: {
        FormMessage,
        AppMask
    },

    mixins: [
        FormMessageMixin,
    ],

    data() {
        return {
            formType: "insert",
            formId: "",
            showMask: false
        };
    },

    computed: {
        styles() {
            return {
                formHeader: {
                    "modal-header form-insert-header": this.formType === "insert",
                    "modal-header form-edit-header": this.formType === "edit",
                },
                label: "control-label col-4 font-weight-bold",
                inputGroup: "input-group col-8",
                btnSubmit: {
                    "btn btn-success": this.formType === "insert",
                    "btn btn-danger": this.formType === "edit",
                }
            };
        }
    },

    methods: {
        disableForm(disable) {
            disable = disable === undefined;

            $("#" + this.formId).find('input, select').each(function (item) {
                this.disabled = disable;
            });
        },

        setFieldId(field) {
            return this.formId + '-' + field;
        },

        setFieldMessageError(field, message) {
            let element = document.getElementById(this.setFieldId(field));

            this.showMessageError(message, element);
        }
    }
}
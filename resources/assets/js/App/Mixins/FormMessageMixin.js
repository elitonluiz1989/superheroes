export default {
    data() {
        return {
            formMessageScroll: true,
            formMessageScrollSpeed: 0,
            formMessageShow: false,
            formMessageText: '',
            formMessageType: 'error'
        };
    },

    methods: {
        showMessageError(message, target) {
            if (target) {
                target.focus();
            }
            this.formMessageType = 'error';
            this.formMessageText = message;
            this.formMessageShow = true;
        },

        showMessageSuccess(message, target) {
            if (target) {
                target.focus();
            }

            this.formMessageType = 'success';
            this.formMessageText = message;
            this.formMessageShow = true;
        }
    }
}
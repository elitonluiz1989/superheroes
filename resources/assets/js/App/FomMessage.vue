<template>
    <div class="form-group form-message" :class="styles.show" ref="formMessage">
        <p :class="[styles.message.error, styles.message.success]" v-if="!manyMessages" v-text="text"></p>

        <p :class="[styles.message.error, styles.message.success]" v-if="manyMessages">
            <span v-for="textMessage in text" v-text="textMessage"></span>
        </p>
    </div>
</template>

<script>
    export default {
        name: 'form-message',

        props: {
            scroll: {
                type: Boolean,
                default: true
            },

            scrollSpeed: {
                type: Number,
                default: 500
            },

            show: {
                type: Boolean,
                default: false,
                required: true
            },

            text: {
                type: String|Array,
                required: true
            },

            type: {
                type: String,
                default: 'error',
                required: true
            }
        },

        data() {
            return {
                success: false
            };
        },

        computed: {
            styles() {
                return {
                    show: {
                        'form-message--show': this.show
                    },

                    message: {
                        error: {
                            'alert alert-danger text-center': !this.success
                        },

                        success: {
                            'alert alert-success text-center': this.success
                        }
                    }
                };
            },

            manyMessages() {
                return Array.isArray(this.text);
            }
        },

        watch: {
            type(value) {
                this.setMessageType();
            },

            show(value) {
                if (value && this.scroll) {
                    console.log('sss')
                    this.scrollToMessage();
                }
            }
        },

        mounted() {
            this.setMessageType();
        },

        methods: {
            setMessageType() {
                this.success = this.type === 'success';
            },

            scrollToMessage() {
                let scrollTo = 5;

                $('html, body').animate({scrollTop: scrollTo}, 500);
            }
        }
    }
</script>

<style lang="scss" scoped>
    .form-message {
        display: none;
        opacity: 0;
        transition: opacity .5s;

        &--show {
            display: block;
            opacity: 1;
        }
    }
</style>

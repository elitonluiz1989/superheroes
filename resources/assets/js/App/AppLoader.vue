<template>
    <div class="loader" v-bind:class="styles">
        <div class="spinner"></div>

        <p class="loader-message text-center" v-if="hasMessage">{{ message }}</p>
    </div>
</template>

<script>
    export default {
        name: 'app-loader',

        props: {
            floating: {
                type: Boolean,
                default: true
            },

            hide: {
                type: Boolean,
                default: false
            },

            message: {
                type: String,
                default: ''
            },

            show: {
                type: Boolean,
                default: false
            },
        },

        computed: {
            styles: function() {
                return {
                    'loader-show': this.show,
                    'loader-hide': this.hide,
                    'loader-floating': this.floating,
                    'loader-static': !this.floating,
                }
            },

            hasMessage() {
                return this.message !== "";
            }
        }

    }
</script>

<style lang="scss">
    .loader {
        background-color: transparent;
        opacity: 0;
        transition: display, opacity 1s;

        &-show {
            display: block;
            opacity: 1;
        }

        &-hide {
            display: none;
        }

        &-floating {
            position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -35px;
            margin-left: -35px;
        }

        &-static {
            padding: 5px 0;
        }

        &-message {
            margin: 0;
            padding: 4px 0;
            font-size: 12px;
        }
    }
</style>

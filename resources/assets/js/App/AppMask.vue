<template>
    <div class="mask" :class="maskStyles.main" ref="mask">
        <div class="mask-content" :class="maskStyles.content">
            <mask-loader :show="showMask" :message="loaderMessage" v-if="hasLoader"></mask-loader>
        </div>
    </div>
</template>

<script>
    import TheLoader from './AppLoader';

    export default {
        name: 'app-mask',

        components: {
            'mask-loader': TheLoader
        },

        props: {
            fullscreen: {
                type: Boolean,
                default: false
            },

            hasLoader: {
                type: Boolean,
                default: true
            },

            loaderMessage: {
                type: String,
                default: ''
            },

            maskStyle: {
                type: String,
                default: 'light'
            },

            showMask: {
                type: Boolean,
                default: false,
            }
        },

        data() {
            return {
                maskContentShow: false,
                maskDark: false,
                maskLight: true,
                maskMainShow: false
            }
        },

        computed: {
            maskStyles() {
                return {
                    main: {
                        'mask-show': this.maskMainShow
                    },

                    content: {
                        'mask-dark': this.maskDark,
                        'mask-light': this.maskLight,
                        'mask-content-show': this.maskContentShow
                    }
                }
            }
        },

        watch: {
            showMask() {
                if (this.showMask) {
                    this.setFullscreen();

                    this.maskMainShow = true;

                    setTimeout(() => {
                        this.maskContentShow = true;
                    }, 100);
                } else {
                    this.maskContentShow = false;

                    setTimeout(() => {
                        this.maskMainShow = false;

                        this.setFullscreen(false);
                    }, 800);
                }
            }
        },

        mounted() {
            if (this.maskStyle === 'dark') {
                this.maskDark = true;
                this.maskLight = false;
            }
        },

        methods: {
            setFullscreen(choice) {
                choice  = choice || true;

                if (this.fullscreen) {
                    let mask = $(this.$refs.mask);

                    if (choice) {
                        mask.height($('body').height())
                            .parents('*:not(body, html)')
                                .addClass('mask--reset-position');
                    } else {
                        mask.removeAttr('style')
                            .parents('*')
                                .removeClass('mask--reset-position');
                    }
                }
            }
        }
    }
</script>
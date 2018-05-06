<template>
    <div>
        <button type="button" data-toggle="modal"
                :data-target="'#' + modalId">
            <i class="fa fa-info-circle"></i>
        </button>

        <div :id="modalId" class="superheroes-details modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-sm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit super-hero</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="superheroes-details-label">Nickname:</div>
                                <div class="superheroes-details-content" v-text="nickname"></div>
                            </div>
                            <div class="row">
                                <div class="superheroes-details-label">Real name:</div>
                                <div class="superheroes-details-content" v-text="realName"></div>
                            </div>
                            <div class="row">
                                <div class="superheroes-details-label">Origin description:</div>
                                <div class="superheroes-details-content" v-text="originDesc"></div>
                            </div>
                            <div class="row">
                                <div class="superheroes-details-label">Superpowers</div>
                                <div class="superheroes-details-content" v-text="superpowers"></div>
                            </div>
                            <div class="row">
                                <div class="superheroes-details-label">Catch phrase</div>
                                <div class="superheroes-details-content" v-text="catchPhrase"></div>
                            </div>
                            <div class="row">
                                <div class="col-12">Superhero's images</div>
                                <div class="col-12">
                                    <ul class="superheroes-details-images">
                                        <li v-for="(image, key) in images">
                                            <div class="superheroes-details-image">
                                                <img :src="image" :alt="'Image' + key" class="w-100 h-100">
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import server from '../server/superhero';

    export default {
        name: "superhero-details",

        props: {
            superheroId: {
                type: Number,
                required: true,
                default: 0
            }
        },

        data() {
            return {
                modalId: "superhero-detail-" + this.superheroId,
                nickname: '',
                realName: '',
                originDesc: '',
                superpowers: '',
                catchPhrase: '',
                images: []
            };
        },

        mounted() {

            $("#" + this.modalId)
                .on('show.bs.modal', () => {

                    this.getSuperhero();
                })
                .on('hidden.bs.modal', () => {
                    this.resetFields();
                })
        },

        methods: {
            getSuperhero() {
                server.load(this.superheroId)
                    .then(response => {
                        if (response.data.error) {
                            console.log(response.data.error);

                            this.formMessageShow = true;
                            this.formMessageText = "Occurred an error on load superhero's data."
                        } else {
                            this.nickname = response.data.nickname;
                            this.realName = response.data.real_name;
                            this.originDesc = response.data.origin_description;
                            this.superpowers = response.data.superpowers;
                            this.catchPhrase = response.data.catch_phrase;

                            let length = response.data.images.length;
                            for(let i = 0; i < length; i++) {
                                this.images.push('superheroes/image/' + response.data.images[i].superhero_image + '?width=250');
                            }
                        }
                    })
                    .catch(error => {
                        console.log(error);

                        this.formMessageShow = true;
                        this.formMessageText = "Occurred an error on load superhero's data."
                    });
            },

            resetFields() {
                this.nickname = "";
                this.realName = "";
                this.originDesc = "";
                this.superpowers = "";
                this.catchPhrase = "";
                this.images = [];
            }
        }
    }
</script>
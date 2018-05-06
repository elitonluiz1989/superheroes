<template>
    <div>
        <button type="button" data-toggle="modal"
                :data-target="'#' + modalId">
            <i class="fa fa-trash"></i>
        </button>

        <div :id="modalId" class="modal fade" tabindex="-1" role="dialog">
            <app-mask :show-mask="showMask" mask-style="dark"></app-mask>

            <div class="modal-dialog" role="document">
                <div class="modal-content modal-sm">
                    <div class="modal-header" v-if="showRequestResult">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p class="text-center" v-text="requestMessage" v-if="showRequestResult"></p>

                        <p v-text="message" v-if="!showRequestResult"></p>
                    </div>

                    <div class="modal-footer" v-if="!showRequestResult">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Não</button>
                        <button type="button"  class="btn btn-danger" @click="deleteRecord">Sim</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import server from '../server/superhero';
    import AppMask from '../App/AppMask';

    export default {
        name: "superheroes-delete",

        components: {
          AppMask
        },

        props: {
            superheroId: {
                type: Number,
                required: true
            },
        },

        data() {
            return {
                modalId: "superheroes-delete-modal-" + this.superheroId,
                showMask: false,
                showRequestResult: false,
                showRequestMessage: false,
                requestMessage: '',
                message: "Do you want delete this superhero?",
                messages: {
                    success: "Superhero was deleted.",
                    error: "Occurred an error on delete the superhero."
                }
            }
        },

        mounted() {
            $('#' + this.modalId) .on('hidden.bs.modal', () => {
                window.location.reload();
            });
        },

        methods: {
            deleteRecord() {
                this.showMask = true;

                server.del(this.superheroId)
                    .then(response => {
                        this.showMask = false;
                        this.showRequestResult = true;

                        if (response.data.error) {
                            console.log(response.data.error);

                            this.requestMessage = this.messages.error;
                        } else {
                            this.requestMessage = this.messages.success;
                        }
                    })
                    .catch(error => {
                        this.showMask = false;
                        this.showRequestResult = true;
                        this.requestMessage = this.messages.error;
                    });
            }
        }
    }
</script>

<style lang="scss" scoped>
    .mask {
        z-index: 99999;
    }

    .modal-content,
    .modal-body,
    .modal-footer{
        background-color: #333;
        color: white;
    }

    .modal-header {
        border-bottom: 0;
        padding: 0.4rem 0.4rem 0.4rem 0;

        .close {
            margin: 0 0 0 auto;
            padding: 0;
            font-size: 1.2rem;
        }
    }

    .modal-footer {
        border-top-color: #444;
    }
</style>
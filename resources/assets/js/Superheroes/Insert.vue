<template>
    <div>
        <button type="button" class="superheroes-form-trigger btn btn-success" data-toggle="modal"
                :data-target="'#' + formId + '-modal'">Add superhero</button>

        <div :id="formId + '-modal'" class="superheroes-form modal fade" tabindex="-1" role="dialog">
            <app-mask :show-mask="showMask" mask-style="dark"></app-mask>

            <div class="modal-dialog" role="document">
                <div class="modal-content modal-sm">
                    <form @submit.prevent="submitForm" :id="formId">
                        <div :class="styles.formHeader">
                            <h5 class="modal-title">Add super-hero</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form-message :show="formMessageShow" :text="formMessageText" :type="formMessageType"></form-message>

                            <div class="form-group row">
                                <label :for="setFieldId('nickname')" :class="styles.label">Nickname</label>

                                <div :class="styles.inputGroup">
                                    <input type="text" :id="setFieldId('nickname')" class="form-control" v-model="nickname">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label :for="setFieldId('real-name')" :class="styles.label">Real name</label>

                                <div :class="styles.inputGroup">
                                    <input type="text" :id="setFieldId('real-name')" class="form-control" v-model="realName">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label :for="setFieldId('origin-description')" :class="styles.label">Origin description</label>

                                <div :class="styles.inputGroup">
                                    <textarea :id="setFieldId('origin-description')" class="form-control" v-model="originDesc"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label :for="setFieldId('superpowers')" :class="styles.label">Superpowers</label>

                                <div :class="styles.inputGroup">
                                    <textarea :id="setFieldId('superpowers')" class="form-control" v-model="superpowers"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label :for="setFieldId('catch_phrase')" :class="styles.label">Catch phrase</label>

                                <div :class="styles.inputGroup">
                                    <textarea :id="setFieldId('catch_phrase')" class="form-control" v-model="catchPhrase"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label :for="setFieldId('images')" class="control-label col-12 font-weight-bold">
                                    Superhero images<br>
                                    <small>(max. {{ upload.limit }})</small>
                                    <small>(formats: {{ uploadFormats }})</small>
                                </label>

                                <div class="input-group upload-box" :class="upload.boxClass">
                                    <input type="file" :id="setFieldId('images')" class="upload-box-input" :disabled="upload.status.uploading" @change="filesChange" accept="image/*">

                                    <div class="w-100 text-center" v-text="upload.messageText"></div>
                                </div>

                                <div class="container-fluid" v-if="images.length > 0">
                                    <div class="row justify-content-center justify-content-sm-start">
                                        <div class="col-10 col-sm-3" v-for="(image, key) in images">
                                            <div class="upload-box-image" @click="uploadedImageDelete(key)">
                                                <div class="mask" title="Click to remove">
                                                    <div class="mask-content mask-dark text-center" style="position: absolute !important;">
                                                        <i class="fa fa-times fa-2x"></i>
                                                    </div>
                                                </div>

                                                <img :src="image + '?height=200'">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <input type="reset" class="btn btn-light" value="Limpar">

                            <input  type="submit" :class="styles.btnSubmit" value="Salvar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SuperheroFormMixin from "./Mixins/SuperheroesFormMixin";

    export default {
        name: "superheroes-insert-form",

        mixins: [
            SuperheroFormMixin
        ],

        data() {
            return {
                formId: "superhero-insert"
            };
        },

        mounted() {
            this.resetForm();

            $("#" + this.formId + "-modal").on('hidden.bs.modal', () => {
                this.closeForm();
            })
        },

        methods: {
            submitForm() {
                if (this.validateForm()) {
                    this.showMask = true;

                    let data = {
                        nickname: this.nickname,
                        "real_name": this.realName,
                        "origin_description": this.originDesc,
                        superpowers: this.superpowers,
                        "catch_phrase": this.catchPhrase,
                        images: this.images
                    };

                    this.server.add(data)
                        .then(response => {
                            this.showMask = false;

                            if (response.data.error) {
                                console.log(response.data.error);
                                this.showMessageError(response.data.error.join("<br>"));

                                this.recordSaved = false;
                            } else {
                                this.showMessageSuccess("Superhero was recorded.");

                                this.recordSaved = true;
                            }
                        })
                        .catch(error => {
                            console.log(error);

                            this.showMask = false;
                            this.recordSaved = false;
                            this.showMessageError("There was an error and the superhero wasn't recorded.");
                        })
                }
            }
        }
    }
</script>
<template>
    <div>
        <button type="button" data-toggle="modal"
                :data-target="'#' + formId + '-modal'">
            <i class="fa fa-pencil"></i>
        </button>

        <div :id="formId + '-modal'" class="superheroes-form modal fade" tabindex="-1" role="dialog">
            <app-mask :show-mask="showMask" mask-style="dark"></app-mask>

            <div class="modal-dialog" role="document">
                <div class="modal-content modal-sm">
                    <form @submit.prevent="submitForm" :id="formId">
                        <div :class="styles.formHeader">
                            <h5 class="modal-title">Edit super-hero</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form-message :show="formMessageShow" :text="formMessageText" :type="formMessageType" :scroll="formMessageScroll"></form-message>

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
                                    <input type="file" :id="setFieldId('images')" class="upload-box-input"
                                           :disabled="upload.status.uploading"
                                           @change="filesChange" accept="image/*">

                                    <div class="w-100 text-center" v-text="upload.messageText"></div>
                                </div>

                                <div class="container-fluid" v-if="images.length > 0">
                                    <div class="row justify-content-center justify-content-sm-start">
                                        <div class="col-10 col-sm-3" v-for="(image, key) in images"
                                            v-if="image.src === undefined || image.src !== null">
                                            <div class="upload-box-image" @click="deleteUploadedImages(key)">
                                                <div class="mask" title="Click to remove">
                                                    <div class="mask-content mask-dark text-center" style="position: absolute !important;">
                                                        <i class="fa fa-times fa-2x"></i>
                                                    </div>
                                                </div>

                                                <img :src="setImage(image)">
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
        name: "superheroes-edit-form",

        mixins: [
            SuperheroFormMixin
        ],

        props: {
            superheroId: {
                type: Number,
                required: true
            }
        },

        data() {
            return {
                formId: "superhero-edit-" + this.superheroId,
                record: {},
                oldImages: [],
            };
        },

        mounted() {
            $("#" + this.formId + "-modal")
                .on('show.bs.modal', () => {
                    this.getSuperhero();
                })
                .on('hidden.bs.modal', () => {
                    if (this.uploadedFiles > 0) {
                        let length = this.images.length;
                        let imagesToRemove = [];
                        for(let i = 0; i < length; i++) {
                            if (this.record.images[i] === undefined) { // It's new image
                                imagesToRemove.push(this.images[i]);
                            } else if (this.oldImages[i] !== undefined) {
                                imagesToRemove.push(this.images[i].src);
                            }
                        }
                        this.images = imagesToRemove;
                    }

                    this.closeForm();
                })
        },

        methods: {
            deleteUploadedImages(key) {
                let isRecorded = false;

                if (this.images[key].id !== undefined) {
                    this.oldImages[key] = this.images[key].src;
                    this.availableKeysImageList.push(key);
                    isRecorded = true;
                }

                this.uploadedImageDelete(key, isRecorded);
            },

            diffImages() {
                let length = this.images.length;
                let diff = [];

                for(let i = 0; i < length; i++) {
                    // Verify if the current image is new
                    if (this.record.images[i] === undefined) {
                        diff.push(this.images[i]);
                    } else if (this.oldImages[i] !== undefined) {
                         // Else, verify if on there's a old image in same position
                        let image = {};

                        // After, verify if was remove a image but not uploaded a new.
                        if (this.images[i].src == null) {
                            // True, set to remove the old image
                            image.id = this.images[i].id;
                            image.delete = true; // The old image will be removed.
                        } else {
                            // Else, is an image replacement
                            image.id = this.images[i].id;
                            image.src = this.images[i].src;
                            image.old =  this.record.images[i].src
                        }

                        diff.push(image);
                    }
                }

                return diff;
            },

            getSuperhero() {
                this.resetForm();

                this.server.load(this.superheroId)
                    .then(response => {
                        if (response.data.error) {
                            console.log(response.data.error);

                            this.record = {};

                            this.formMessageShow = true;
                            this.formMessageText = "Occurred an error on load superhero's data."
                        } else {
                            this.record.id = response.data.id;

                            this.nickname = response.data.nickname;
                            this.record.nickname = response.data.nickname;

                            this.realName = response.data.real_name;
                            this.record.realName = response.data.real_name;

                            this.originDesc = response.data.origin_description;
                            this.record.originDesc = response.data.origin_description;

                            this.superpowers = response.data.superpowers;
                            this.record.superpowers = response.data.superpowers;

                            this.catchPhrase = response.data.catch_phrase;
                            this.record.catchPhrase = response.data.catch_phrase;

                            this.record.images = [];
                            let length = response.data.images.length;
                            let image = {};
                            for(let i = 0; i < length; i++) {
                                image = {
                                    id: response.data.images[i].id,
                                    src: 'superheroes/image/' + response.data.images[i].superhero_image
                                };

                                this.images.push(image);
                                this.record.images.push(image);
                            }

                            this.uploadedFiles = length;

                            if (length === this.upload.limit) {
                                this.upload.status.uploading = true;
                            }
                        }
                    })
                    .catch(error => {
                        console.log(error);

                        this.record = {};

                        this.formMessageShow = true;
                        this.formMessageText = "Occurred an error on load superhero's data."
                    });
            },

            setImage(image) {
                image = image.src || image;

                return image + '?height=200';
            },

            submitForm() {
                if (this.validateForm()) {
                    let data = {};
                    let proceed = false;

                    let diffImgs = this.diffImages();

                    if (this.nickname !== this.record.nickname) {
                        data.nickname = this.nickname;
                        proceed = true;
                    }

                    if (this.realName !== this.record.realName) {
                        data.real_name = this.realName;
                        proceed = true;
                    }

                    if (this.originDesc !== this.record.originDesc) {
                        data.origin_description = this.originDesc;
                        proceed = true;
                    }

                    if (this.superpowers !== this.record.superpowers) {
                        data.superpowers = this.superpowers;
                        proceed = true;
                    }

                    if (this.catchPhrase !== this.record.catchPhrase) {
                        data.catch_phrase = this.catchPhrase;
                        proceed = true;
                    }

                    if (diffImgs.length > 0) {
                        data.images = diffImgs;
                        proceed = true;
                    }

                    if (proceed) {
                        this.showMask = true;
                        data.id = this.record.id;

                        this.server.edit(data)
                            .then(response => {
                                this.showMask = false;

                                if (response.data.error) {
                                    this.showMessageError(response.data.error.join("<br>"));

                                    this.recordSaved = false;
                                } else {
                                    this.showMessageSuccess("Superhero's info updated.");

                                    this.recordSaved = true;
                                }
                            })
                            .catch(error => {
                                console.log(error);

                                this.showMask = false;
                                this.recordSaved = false;
                                this.showMessageError("There was an error and the superhero's info wasn't updated.");
                            });
                    }
                }
            }
        }
    }
</script>
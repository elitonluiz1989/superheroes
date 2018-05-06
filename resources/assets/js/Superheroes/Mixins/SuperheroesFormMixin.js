import SuperheroServer from '../../server/superhero';
import FormMixin from "../../App/Mixins/FormMixin";

export default {
    mixins: [
        FormMixin
    ],

    data() {
        return {
            server: SuperheroServer,
            nickname: "Spider-man",
            realName: "Peter Parker",
            originDesc: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vestibulum eros nec dolor mollis, blandit ullamcorper sem semper. Interdum et malesuada fames ac ante ipsum primis in faucibus. Proin commodo dictum mi, at gravida urna vulputate sit amet. Etiam molestie lobortis leo, ut feugiat sapien accumsan sed. Nunc efficitur tortor vel finibus sagittis. Praesent est erat, suscipit quis erat vel, congue egestas ex. Phasellus et mi in orci elementum egestas eu sit amet diam. Etiam iaculis lorem nec enim egestas, sit amet rhoncus tortor sagittis.",
            superpowers: "Etiam iaculis lorem nec enim egestas, sit amet rhoncus tortor sagittis.",
            catchPhrase: "Nam vestibulum eros nec dolor mollis, blandit ullamcorper sem semper.",
            images: [],
            listToRemove: [],
            availableKeysImageList: [],
            recordSaved: false,
            uploadedFiles: 0,
            upload: {
                boxClass: null,
                limit: 4,
                formats: [ "jpg", "jpeg", "png"],
                message: {
                    default: "Click or grab to upload images.",
                    error: "Occurred an error on image upload. Try again."
                },
                messageText: null,
                status: {
                    uploading: false,
                    saved: false,
                    error: false
                },
                progressBarId: "upload-box-progress-bar"
            }
        };
    },

    computed: {
        uploadFormats() {
            return this.upload.formats.join(", ");
        }
    },

    watch: {
        uploadedFiles(value) {
            if (value === this.upload.limit) {
                this.upload.status.uploading = true; // Lock the uploadings
                this.upload.messageText = "Arrived at the limit of images."
            } else {
                this.upload.status.uploading = false;
                this.upload.messageText = this.upload.message.default;
            }
        }
    },

    mounted() {
        this.uploadStatusReset();
    },

    methods: {
        filesChange(evt) {
            let image = evt.target.files[0];
            this.upload.status.uploading = true;
            this.upload.messageText = "Uploading...";

            this.server.upload(image)
                .then(response => {
                    if (response.data.error) {

                        this.uploadStatusError();
                    } else {
                        let avLength = this.availableKeysImageList.length;

                        if (this.availableKeysImageList.length > 0) {
                            //It's used to update the images reference on database
                            // Used only in record update
                            avLength -= 1;
                            this.images[this.availableKeysImageList[avLength]].src = response.data;

                            if (avLength === 0) {
                                this.availableKeysImageList = [];
                            } else {
                                this.availableKeysImageList.splice(avLength, 1);
                            }
                        } else {
                            this.images.push(response.data);
                        }

                        this.uploadedFiles += 1;
                        this.upload.status.saved = true;
                        this.uploadStatusReset();
                    }
                })
                .catch(err => {
                    console.log(err);

                    this.uploadStatusError();
                });
            this.upload.status.uploading = false;
        },

        uploadedImageDelete(key, isRecorded) {
            if (!isRecorded) {
                this.listToRemove.push(this.images[key]);
                this.images.splice(key, 1);
            } else {
                this.images[key].src = null;
            }

            this.uploadedFiles -= 1;

            this.resetInputFile();
        },

        closeForm() {
            this.resetInputFile();

            if (this.images.length > 0 && !this.recordSaved && this.uploadedFiles > 0) {
                let images = this.listToRemove.concat(this.images);

                this.server.deleteImages(images)
                    .then(response => {
                        if (response.data) {
                            console.log("Uploaded images were removed.")
                        } else {
                            console.log("Uploaded images weren't removed.")
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }

            this.resetForm();

            if (this.recordSaved) {
                window.location.reload();
            }
        },

        uploadStatusReset() {
            this.upload.boxClass = null;
            this.upload.status.error = false;
            this.upload.messageText = this.upload.message.default;
            this.resetInputFile();
        },

        uploadStatusError() {
            this.upload.boxClass = "alert-danger";
            this.upload.status.error = true;
            this.upload.messageText = this.upload.message.error;
            this.resetInputFile();
        },

        validateForm() {
            let validated = false;

            if (this.nickname === "") {
                this.setFieldMessageError("nickname", "Define a nickname to the superhero");
            } else if (this.realName === "") {
                this.setFieldMessageError("real-name", "Define a real name to the superhero");
            } else if (this.originDesc === "") {
                this.setFieldMessageError("origin-description", "Define an origin description to the superhero");
            } else if (this.superpowers === "") {
                this.setFieldMessageError("superpowers", "Define at least one superpower to the superhero");
            } else if (this.catchPhrase === "") {
                this.setFieldMessageError("catch-phrase", "Define a catch phrase to the superhero");
            } else if (this.images.length === 0) {
                this.setFieldMessageError("images", "Define at least one image to the superhero");
            } else {
                validated = true;
            }

            return validated;
        },

        resetInputFile() {
            document.getElementById(this.setFieldId('images')).value = '';
        },

        resetForm() {
            this.nickname = "";
            this.realName = "";
            this.originDesc = "";
            this.superpowers = "";
            this.catchPhrase = "";
            this.images = [];

            this.formMessageShow = false;

            this.resetInputFile();
            this.uploadStatusReset();
        }
    }
}
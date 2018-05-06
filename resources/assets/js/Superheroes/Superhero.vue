<template>
    <div class="superhero">
        <div class="superhero-image">
            <img :src="avatar" :alt="nickname" class="w-100 h-100">
        </div>

        <div class="superhero-nickname" v-text="nickname"></div>

        <div class="superhero-control">
            <superhero-details :superhero-id="superheroId" :class="btnClass" :title="'Click to show details'"></superhero-details>

            <superhero-edit :superhero-id="superheroId" :class="btnClass" :title="'Click to edit'"></superhero-edit>

            <superhero-delete :superhero-id="superheroId" :class="btnClass" :title="'Click to delete'"></superhero-delete>
        </div>
    </div>
</template>

<script>
    import SuperheroDetails from './Details';
    import SuperheroEdit from './Edit';
    import SuperheroDelete from './Delete';

    export default {
        name: "superhero",

        components: {
            SuperheroDetails,
            SuperheroEdit,
            SuperheroDelete
        },

        props: {
            superheroId: {
                type: Number,
                required: true
            },

            nickname: {
                type: String,
                required: true
            },

            image: {
                type: String,
                required: true
            }
        },

        data() {
            return {
                btnClass: "superhero-control-btn"
            };
        },

        computed: {
            avatar() {
                return this.image + '?width=300&height=250';
            }
        }
    }
</script>
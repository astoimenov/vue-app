<template>
    <div class="">
        <h1 class="title">My projects</h1>
        <ul>
            <li v-for="project in projects" v-text="project.name"></li>
        </ul>

        <hr>

        <form method="post" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
            <div class="control">
                <label for="name" class="label">Name</label>
                <input type="text" name="name" class="input" id="name" v-model="form.name">

                <span class="help is-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
            </div>

            <div class="control">
                <label for="description" class="label">Description</label>
                <input type="text" name="description" class="input" id="description" v-model="form.description">

                <span class="help is-danger" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></span>
            </div>

            <div class="control">
                <button type="submit" name="button" class="button is-primary" :class="{ 'is-loading': form.isSubmitting }" :disabled="form.errors.hasAny()">
                    Create
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import Form from '../lib/Form';

    export default {
        data() {
            return {
                form: new Form({
                    name: '',
                    description: ''
                }),
                projects: []
            };
        },
        methods: {
            onSubmit() {
                this.form.submit('post', '/api/projects')
                    .then(data => this.projects.push(data.project))
                    .catch(error => console.error(error));
            }
        },
        created() {
            this.$http.get('/api/projects')
                .then(response => {
                    this.projects = response.data.projects;
                });
        }
    }
</script>

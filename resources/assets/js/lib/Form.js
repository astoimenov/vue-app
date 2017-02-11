import Errors from './Errors';

export default class Form {
    constructor(data) {
        this.isSubmitting = false;
        this.$data = data;
        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
    }

    data() {
        let data = {};

        for (var property in this.$data) {
            data[property] = this[property];
        }

        return data;
    }

    reset() {
        for (let field in this.$data) {
            this[field] = '';
        }

        this.errors.clear();
    }

    submit(type, url) {
        let form = this;

        return new Promise(function(resolve, reject) {
            form.isSubmitting = true;

            Vue.http[type.toLowerCase()](url, form.data())
                .then(response => {
                    form.onSuccess(response.data);

                    resolve(response.data);
                })
                .catch(error => {
                    form.onFail(error.body);

                    reject(error.body);
                });
        });
    }

    onSuccess(data) {
        alert(data.message);

        this.reset();

        this.isSubmitting = false;
    }

    onFail(errors) {
        this.errors.record(errors);

        this.isSubmitting = false;
    }
}

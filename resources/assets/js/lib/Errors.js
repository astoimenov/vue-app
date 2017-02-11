export default class Errors {
    constructor() {
        this.errors = {};
    }

    record(errors) {
        this.errors = errors;
    }

    has(field) {
        return this.errors.hasOwnProperty(field);
    }

    get(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }

    clear(field = null) {
        if (field) {
            delete this.errors[field];
        } else {
            this.errors = {};
        }
    }

    hasAny() {
        return Object.keys(this.errors).length > 0;
    }
}

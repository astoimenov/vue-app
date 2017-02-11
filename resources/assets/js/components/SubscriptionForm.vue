<template>
    <form action="/plans" method="post" class="box column is-half is-offset-one-quarter">
        <section class="control">
            <select class="select" name="plan" v-model="plan">
                <option v-for="plan in plans" :value="plan.id">
                    {{ plan.name }} &mdash; ${{ plan.price / 100 }}
                </option>
            </select>
        </section>

        <section class="control">
            <coupon v-model="coupon" name="coupon" class="input" placeholder="Have a coupon code?">
        </section>

        <hr>

        <footer class="">
            <button type="submit" class="button is-primary" @click.prevent="subscribe">
                Subscribe
            </button>

            <p class="help is-danger" v-text="status" v-show="status"></p>
        </footer>
    </form>
</template>

<script>
    Vue.component('coupon', {
        props: ['code'],
        template: `<input type="text" :value="code" @input="updateCode($event.target.value)" />`,
        methods: {
            updateCode(code) {
                this.$emit('input', code);
            }
        }
    });

    export default {
        props: ['plans'],
        data() {
            return {
                stripeEmail: '',
                stripeToken: '',
                plan: 1,
                status: false,
                coupon: ''
            };
        },
        created() {
            this.stripe = StripeCheckout.configure({
                key: Laravel.stripeKey,
                image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                locale: 'auto',
                panelLabel: 'Subscribe for',
                email: Laravel.user.email,
                token: (token) => {
                    this.stripeToken = token.id;
                    this.stripeEmail = token.email;

                    this.$http.post('/plans', this.$data)
                        .then(response => alert(response.data.message))
                        .catch(response => {
                            this.status = response.body.message;
                        });
                }
            });
        },
        methods: {
            subscribe() {
                this.status = false;
                let plan = this.findPlanById(this.plan);

                this.stripe.open({
                    name: plan.name,
                    description: '',
                    amount: plan.price
                });
            },

            findPlanById(id) {
                return this.plans.find(plan => plan.id == id);
            }
        }
    }
</script>

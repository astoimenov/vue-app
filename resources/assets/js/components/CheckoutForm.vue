<template>
    <form action="/purchases" method="post" class="box">
        <section class="content">
            <select class="" name="product" v-model="product">
                <option v-for="product in products" :value="product.id">
                    {{ product.name }} &mdash; ${{ product.price / 100 }}
                </option>
            </select>
        </section>

        <hr>

        <footer class="">
            <button type="submit" class="button is-primary" @click.prevent="buy">
                Buy my book
            </button>
        </footer>
    </form>
</template>

<script>
    export default {
        props: ['products'],
        data() {
            return {
                stripeEmail: '',
                stripeToken: '',
                product: 1
            };
        },
        created() {
            this.stripe = StripeCheckout.configure({
                key: Laravel.stripeKey,
                image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                locale: 'auto',
                panelLabel: 'Subscribe for',
                token: (token) => {
                    this.stripeToken = token.id;
                    this.stripeEmail = token.email;

                    this.$http.post('/purchases', this.$data)
                        .then(response => alert(response.data.message));
                }
            });
        },
        methods: {
            buy() {
                let product = this.findProductById(this.product);

                this.stripe.open({
                    name: product.name,
                    description: product.description,
                    amount: product.price
                });
            },

            findProductById(id) {
                return this.products.find(product => product.id == id);
            }
        }
    }
</script>

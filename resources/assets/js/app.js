require('./bootstrap');

import Turbolinks from 'turbolinks';

Vue.component('checkout-form', require('./components/CheckoutForm.vue'));
Vue.component('subscription-form', require('./components/SubscriptionForm.vue'));
Vue.component('projects-form', require('./components/ProjectsForm.vue'));

Turbolinks.start();

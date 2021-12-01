import Vue from 'vue';

import VueSweetalert2 from 'vue-sweetalert2';

import {
  library,
} from '@fortawesome/fontawesome-svg-core';
import {
  fas,
} from '@fortawesome/free-solid-svg-icons';
import {
  far,
} from '@fortawesome/free-regular-svg-icons';
import {
  faFacebookF,
  faFacebook,
  faGoogle,
  faTwitter,
  faLine,
} from '@fortawesome/free-brands-svg-icons';
import {
  FontAwesomeIcon,
} from '@fortawesome/vue-fontawesome';
import 'bootstrap';

import VuejsClipper from 'vuejs-clipper';
import App from './App.vue';

Vue.use(VueSweetalert2);

library.add(fas, far);
library.add(faGoogle, faFacebookF, faFacebook, faTwitter, faLine);

Vue.component('font-awesome-icon', FontAwesomeIcon);
// install
Vue.use(VuejsClipper);

Vue.config.productionTip = false;

new Vue({
  render: (h) => h(App),
}).$mount('#app');

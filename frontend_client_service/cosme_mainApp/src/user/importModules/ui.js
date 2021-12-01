import Vue from 'vue';
import vuescroll from 'vuescroll';
import VueLazyLoad from 'vue-lazyload';

import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import {
  faFacebookF, faFacebook, faGoogle, faTwitter, faLine,
} from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import 'bootstrap';

Vue.use(vuescroll);
Vue.use(VueLazyLoad);
library.add(fas, far);
library.add(faGoogle, faFacebookF, faFacebook, faTwitter, faLine);

Vue.component('vue-scroll', vuescroll);
Vue.component('font-awesome-icon', FontAwesomeIcon);

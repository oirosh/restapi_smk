window._ = require('lodash');

window.axios = require('axios');
axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

var $ = require('jquery');

require('./modules/modernizr');
require('./modules/google_tag_manager');
require('./modules/lazy_load.jsx');

$(document).ready(function() {
    require('./modules/fit_to_parent.jsx');
    require('./modules/site_nav.jsx');
});
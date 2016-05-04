var $ = require('jquery');

require('./modules/modernizr');
require('./modules/google_tag_manager');

$(document).ready(function() {
    require('./modules/fit_to_parent.jsx');
    require('./modules/site_nav.jsx');
});
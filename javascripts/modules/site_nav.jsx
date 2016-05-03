/**
 *
 */

var $ = require('jquery');

var mobileNavButton = $('#MobileDropdownIcon');
var mobileNav = $('#MobileNav');

function toggleMobileNav(show, animate) {
  if(show) {
    $(mobileNav).slideDown(function() {
      $(mobileNav).attr('aria-hidden', 'false').attr('aria-expanded', 'true');
      $(mobileNavButton).attr('aria-expanded', 'true');
    });
  } else {
    $(mobileNav).slideUp(function () {
      $(mobileNav).attr('aria-hidden', 'true').attr('aria-expanded', 'false');
      $(mobileNavButton).attr('aria-expanded', 'false');
    });
  }
}

$(mobileNavButton).click(function() {
  var expanded = $(mobileNavButton).attr('aria-expanded');

  console.log(expanded);

  if(expanded == 'true') {
    toggleMobileNav(false, true);
  } else {
    toggleMobileNav(true, true);
  }
});
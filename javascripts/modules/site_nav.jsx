/**
 *
 */

var $ = require('jquery');

var mobileNavButton = $('#MobileDropdownIcon');
var mobileNav = $('#MobileNav');
var animationSpeed = 350;


function getDropdownId(element) {
  return $(element).attr('aria-controls');
}

function getDropDown(element) {
  return $('#' + element);
}

function getExpanded(element) {
  return $(element).attr('aria-expanded');
}


function toggleAriaMeta(control, target, show) {
  var hidden, expanded;

  if(show) {
    hidden = 'false';
    expanded = 'true';
  } else {
    expanded = 'false';
    hidden = 'true';
  }

  $(target).attr('aria-hidden', hidden).attr('aria-expanded', expanded);
  $(control).attr('aria-expanded', expanded);
}

function toggleMobileNav(show, animate) {
  if(show) {
    if(animate) {
      $(mobileNav).slideDown(animationSpeed, function() {
        toggleAriaMeta(mobileNavButton, mobileNav, show);
      });
    } else {
      $(mobileNav).show();
      toggleAriaMeta(mobileNavButton, mobileNav, show);
    }
  } else {
    if(animate) {
      $(mobileNav).slideUp(animationSpeed, function () {
        toggleAriaMeta(mobileNavButton, mobileNav, show);
      });

      hideAllSubnav(true);
    } else {
      $(mobileNav).hide();
      toggleAriaMeta(mobileNavButton, mobileNav, show);
    }
  }
}

$(mobileNavButton).click(function() {
  if(getExpanded(mobileNavButton) == 'true') {
    toggleMobileNav(false, true);
  } else {
    toggleMobileNav(true, true);
  }
});

//

var subNavControls = $('.SiteNav-link.u-controlsDropdown');
var isAnimating = {};
var currentStateIsOpen = {};

function afterAnimation(dropdownId, controlElement, dropdown, show) {
  isAnimating[dropdownId] = false;
  toggleAriaMeta(controlElement, dropdown, show);

  if(currentStateIsOpen[dropdownId] && !show) {
    toggleSubNavDesktop(controlElement, dropdownId, true);
  } else if(!currentStateIsOpen[dropdownId] && show) {
    toggleSubNavDesktop(controlElement, dropdownId, false);
  }
}

function toggleSubNavDesktop(controlElement, dropdownId, show) {
  var dropdown = getDropDown(dropdownId);

  if(!isAnimating[dropdownId]) {
    isAnimating[dropdownId] = true;

    if(show) {
      $(dropdown).slideDown(animationSpeed, function() {
        afterAnimation(dropdownId, controlElement, dropdown, show);
      });
    } else {
      $(dropdown).slideUp(animationSpeed, function() {
        afterAnimation(dropdownId, controlElement, dropdown, show);
      });
    }
  }
}

function hoverFunction(controlElement, show) {
  var dropdownId = getDropdownId(controlElement);
  currentStateIsOpen[dropdownId] = show;
  toggleSubNavDesktop(controlElement, dropdownId, show);
}

function toggleSubNavMobile(control, dropdown, show, animate) {
  if(show) {
    if(animate) {
      $(dropdown).slideDown(animationSpeed, function() {
        toggleAriaMeta(control, dropdown, true);
      });
    } else {
      $(dropdown).show();
      toggleAriaMeta(control, dropdown, true);
    }
  } else {
    if(animate) {
      $(dropdown).slideUp(animationSpeed, function() {
        toggleAriaMeta(control, dropdown, false);
        $(dropdown).attr('style', '');
      });
    } else {
      $(dropdown).hide().attr('style', '');
      toggleAriaMeta(control, dropdown, false);
    }
  }
}

function temp(element) {
  var subNavControl = $(element);
  var dropdownId = getDropdownId(subNavControl);   
  var dropdown = getDropDown(dropdownId);

  return {
    subNavControl: subNavControl,
    dropdownId: dropdownId,
    dropdown: dropdown
  }
}

$('.SiteNavMain-item.u-hasDropDown').hover(function() {
  if(!isMobileNav) {
    hoverFunction($(this).find('.u-controlsDropdown'), true);
  }
}, function() {
  if(!isMobileNav) {
    hoverFunction($(this).find('.u-controlsDropdown'), false);
  }
});

$(subNavControls).each(function() {
  var dropdownId = getDropdownId(this);
  isAnimating[dropdownId] = false;
  currentStateIsOpen[dropdownId] = false;
}).click(function(event) {
  if(isMobileNav) {
    event.preventDefault();
    var elements = temp(this);

    if(getExpanded(elements.subNavControl) == 'true') {
      toggleSubNavMobile(elements.subNavControl, elements.dropdown, false, true);
    } else {
      toggleSubNavMobile(elements.subNavControl, elements.dropdown, true, true);

      $(subNavControls).not(elements.subNavControl).each(function() {
        var elements = temp(this);
        toggleSubNavMobile(elements.subNavControl, elements.dropdown, false, true);
      });
    }
  }
});

function hideAllSubnav(animate) {
  $(subNavControls).each(function() {
    var elements = temp(this);
    toggleSubNavMobile(elements.subNavControl, elements.dropdown, false, animate);
  });
}

//

function loadResizeMobileNav() {
  if(isMobileNav) {
    toggleMobileNav(false, false);
  } else {
    toggleMobileNav(true, false);
    hideAllSubnav(false);
  }  
}

//


var isMobileNav = false;
var prevIsMobile;

function setIfMobileNav() {
  if($(mobileNavButton).is(':visible')) {
    isMobileNav = true;
  } else {
    isMobileNav = false;
  }

  if(typeof prevIsMobile != 'undefined') {
    if(prevIsMobile && !isMobileNav) {
      loadResizeMobileNav();
    } else if(!prevIsMobile && isMobileNav) {
      loadResizeMobileNav();
    }
  }

  prevIsMobile = isMobileNav;
}

exports.isMobileNav = function() {
  return isMobileNav;
}

//

$(document).ready(function() {
  setIfMobileNav();
  loadResizeMobileNav(); 
});

$(window).resize(function() {
  setIfMobileNav();
});
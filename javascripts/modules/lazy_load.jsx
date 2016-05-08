var $ = require('jquery');

function setImageHeight(element) {
  var height = $(element).attr('height');
  var width = $(element).attr('width');

  var actualWidth = $(element).width();
  var aspectRatio = width / height;

  var height = actualWidth / aspectRatio;

  $(element).height(height);
}

$(document).ready(function() {
  $("img[data-src]").each(function () {
    setImageHeight(this);
  });
});

$(window).on('DOMContentLoaded load resize scroll', function () {;
  var images = $("img[data-src]");
  // load images that have entered the viewport
  $(images).each(function () {
    if (isElementInViewport(this)) {
      initiateAttr(this, 'sizes');
      initiateAttr(this, 'srcset');
      initiateAttr(this, 'src');
      $(this).attr('style', '');
    }
  })
  // if all the images are loaded, stop calling the handler
  if (images.length == 0) {
    $(window).off('DOMContentLoaded load resize scroll')
  }
})

function initiateAttr(element, attr) {
  $(element).attr(attr, $(element).attr("data-" + attr));
  $(element).removeAttr("data-" + attr);
}

// source: http://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport/7557433#7557433
function isElementInViewport (el) {
    var rect = el.getBoundingClientRect();
    var buffer = 3000;

    return (
        rect.top >= -buffer &&
        rect.left >= 0 &&
        rect.bottom <= ($(window).height() + buffer) &&
        rect.right <= $(window).width()
    );
}

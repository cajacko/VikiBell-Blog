/**
 * Fit an element to its parent
 */

var $ = require('jquery');

// Fit an element to its parent
function fitToParent(element, load) {
  var elementWidth, elementHeight;

  // Get the height and width attributes of the element if they exist
  var widthAttr = parseInt($(element).attr('width'));

  if(widthAttr) {
    elementWidth = widthAttr;
  }

  var heightAttr = parseInt($(element).attr('height'));

  if(heightAttr) {
    elementHeight = heightAttr;
  }

  // If the element doesn't have height and width attributes then get the actual height/width

  if(!elementWidth || !elementHeight) {
    elementWidth = $(element).width();
    elementHeight = $(element).height();
  }

  // If the height and width are set then position the element, otherwise wait for it to load and try again
  if(elementWidth && elementHeight) {
    // Get the size of the parent, ad calculate aspect ratios
    var elementAspectRatio = elementWidth / elementHeight;
    var parent = $(element).parent();

    var parentHeight = $(parent).height();
    var parentWidth = $(parent).width();
    var parentAspectRatio = parentWidth / parentHeight;

    // Calculate the position
    var width, height, top, left;

    if(elementAspectRatio < parentAspectRatio) {
      width = parentWidth + 'px';     
      height = 'auto';
    } else {
      width = 'auto';
      height = parentHeight + 'px';
    }

    // Set the height and width
    $(element).css({
      width: width,
      height: height
    });

    // Get the new height/width and set the offset
    if(elementAspectRatio < parentAspectRatio) {
      elementHeight = $(element).height();
      left = 0;
      top = -(elementHeight - parentHeight) / 2;
      top += 'px';
    } else {
      elementWidth = $(element).width();
      top  = 0;
      left = -(elementWidth - parentWidth) / 2;
      left += 'px';
    }

    // Set the position
    $(element).css({
      top: top,
      left: left
    }).addClass('u-fittedToParent')

    // Remove the background image on the header
    $(parent).removeClass('Banner--backgroundImage');
  } 
  // If we are to wait for the element to load, then position the element when it has loaded
  else if(load) {
    $(element).load(function() {
      fitToParent(element, false);
    });
  }
}

// Fit all specified elements to their parents
function fitAllToParent(load) {
  $('.u-fitToParent').each(function() {
    fitToParent(this, load);
  });
}

// Initialise the functions
$(document).ready(function() {
  fitAllToParent(true);
});

$(window).resize(function() {
  fitAllToParent(false);
});

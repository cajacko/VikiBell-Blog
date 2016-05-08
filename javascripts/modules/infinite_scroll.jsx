/**
 *
 */

var $ = require('jquery');

var postTemplate = $('.Post').first();
var next = $('#next');
var gettingPosts = false;

function prependPosts(posts) {
  $(posts).each(function() {
    var postContent = $(this);
    var post = $(postTemplate).clone();

    var sizes = '';

    for (var key in postContent[0].image.sizes) {
      sizes += postContent[0].image.sizes[key]+ ' ' + key + 'w,';
    }

    $(post).find('.Post-content').html(postContent[0].content);
    $(post).find('.Post-title').html(postContent[0].title);
    $(post).find('.Post-date').html(postContent[0].date.title).attr('datetime', postContent[0].date.datetime);
    $(post).find('.Post-headerLink').attr('href', postContent[0].url);
    $(post).find('.Post-featuredImage')
      .attr('alt', postContent[0].image.alt)
      .attr('height', postContent[0].image.height)
      .attr('width', postContent[0].image.width)
      .attr('src', postContent[0].image.src)
      .attr('srcset', sizes);

    $(post).find('.Post-featuredImageUrl').attr('content', postContent[0].image.src);
    $(post).find('.Post-featuredImageWidth').attr('content', postContent[0].image.width);
    $(post).find('.Post-featuredImageHeight').attr('content', postContent[0].image.height);
    $(post).find('.Post-dateModified').attr('content', postContent[0].dateModified);
    $(post).find('.Post-description').attr('content', postContent[0].description);

    $('.PostLoop').append(post);
  });
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function updateNextLink() {
  var nextUrl = $(next).attr('href');
  var page = getParameterByName('page', nextUrl);

  if(page) {
    page++;

    nextUrl = nextUrl.replace(/[0-9].*/g, page);
    $(next).attr('href', nextUrl);
  }
}

function getNextPosts() {
  if(next.length && !gettingPosts) {
    gettingPosts = true;

    var nextUrl = $(next).attr('href') + '&json';

    $.ajax({
      url: nextUrl,
      dataType: 'json'
    })
    .done(function(data) {
      updateNextLink();
      prependPosts(data);
    })
    .fail(function() {
    })
    .always(function() {
      gettingPosts = false;
    }); 
  }
}

$(window).scroll(function() {
  var scrollBottom = $(window).scrollTop() + $(window).height();
  var documentHeight = $(document).height();
  var bottomGap = documentHeight - scrollBottom;

  if(bottomGap < 20000) {
    getNextPosts();
  }
});

getNextPosts();
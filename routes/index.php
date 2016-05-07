<?php

// Temp functions for loading dummy data during dev
require_once('../models/temp.php'); 

// Setup the redirect home url depending on our local environment
if($config['environment']['dev']) {
  $redirect_base = 'localhost:3000/';
} else {
  $redirect_base = $redirect = $config['environment']['url'] . '/';
}

// Explode the query into an array
if(isset($_GET['url'])) {
  $request = explode('/', $_GET['url']);
  $request = array_filter($request); // Nedded, as explode will create an array item for trailing slash, this removes it.
} else {
  $request = array();
}

// Get the global data
require_once('../models/site_navigation.php');
require_once('../models/social_navigation.php');
require_once('../models/sidebar.php');
require_once('../models/twitter.php');
require_once('../helpers/bunting.php');

// Setup the variables to pass to the view
$vars = array(
  'icons' => file_get_contents('../icons/icons.svg'),
  'staticPublic' => $static_public,
  'config' => $config,
  'nav' => $site_navigation,
  'socialNav' => $social_navigation,
  'bunting' => $bunting,
  'isSingle' => false,
  'sidebar' => $sidebar,
  // 'canonical' => 'http://address.com',
  'meta' => array(
    'title' => 'Hello',
    'loop' => array(
      'name="description"' => 'Hi there',
    ),
  ),
  'pageTitle' => 'Page title',
  'socialProfiles' => array(
    'instagram' => 'https://www.instagram.com/vikibell/',
    'twitter' => 'https://twitter.com/Vikiibell/',
    'pinterest' => 'https://www.pinterest.com/vikiibell/',
    'linkedin' => 'https://www.linkedin.com/in/vikibell',
  ),
  'next' => 'http://vikibell.com/page/2/',
  'prev' => 'http://vikibell.com/',
  'breadcrumbs' => array(
    'Home' => '/',
    'Categories' => '/categories/',
    'Life' => '/categories/life/',
  ),
  // Get and upload more version of this file
  'bannerImage' => array(
    'classes' => 'Banner-image u-fitToParent',
    'alt' => 'Viki Bell Banner Image',
    'src' => $static_public . '/media/banner.jpg',
    'height' => 400,
    'width' => 2500,
  ),
  'organizationLogo' => array(
    'src' => 'http://vikibell.com/media/banner.jpg',
    'width' => 600,
    'height' => 60
  ),
  'tweets' => $tweets,
);



$template_path = 'templates/';

// Define which page of results we are getting
if(isset($_GET['page']) && is_numeric($_GET['page'])) {
  $pagination = $_GET['page'];
} else {
  $pagination = 0;
}

// Route the request
if(isset($request[0])) {
  switch ($request[0]) {
    case 'categories':
      require_once('categories.php');
      break;

    case 'posts':
      require_once('posts.php');
      break;

    case 'action':
      require_once('action.php');
      break;

    case 'search':
      require_once('search.php');
      break;

    case 'sitemap':
      require_once('sitemap.php');
      break;
    
    default:
      require_once('page.php');
      break;
  }
} else {
  require_once('home.php');
}

// Get the template
$template = $twig->loadTemplate($template_path . '.twig');

// gzip compress the content for optimization
ob_start("ob_gzhandler");

/*

description
title
type
image
imagetype
imagewidth
imageheight
modified
published
twittercard
imagealt

<meta name="description" content="Life on the planet Viki continues..."/>

<meta name="og:title" content="Viki Bell - Life on the planet Viki continues..." />
<meta name="og:description" content="Life on the planet Viki continues..." />
<meta name="og:url" content="http://vikibell.com" />
<meta name="og:site_name" content="Viki Bell" />
<meta name="og:locale" content="en_GB" />
<meta name="og:type" content="website/article" />
<meta name="og:image" content="website" />
<meta name="og:image:type" content="website" />
<meta name="og:image:width" content="website" />
<meta name="og:image:height" content="website" />
<meta name="og:updated_time" content="website" />
<meta name="og:article:author" content="website" />
<meta name="og:article:modified_time" content="website" />
<meta name="og:article:published_time" content="website" />
<meta name="og:article:publisher" content="website" />

<meta name="twitter:card" content="summary/summary_large_image"/>
<meta name="twitter:site" content="@VikiiBell"/>
<meta name="twitter:creator" content="@VikiiBell"/>
<meta name="twitter:description" content="Life on the planet Viki continues..."/>
<meta name="twitter:title" content="Viki Bell - Life on the planet Viki continues..."/>
<meta name="twitter:image" content="VikiiBell"/>
<meta name="twitter:image:alt" content="VikiiBell"/>

<meta name="fb:admins" content="Facebook numeric ID" />

{# Google apparently #}
<meta itemprop="name" content="The Name or Title Here">
<meta itemprop="description" content="This is the page description">
<meta itemprop="image" content="http://www.example.com/image.jpg">

*/


// Render the template
$content = $template->render(array('vars' => $vars));

// require_once('../helpers/minify_classes.php');
// $content = minify_html($content);

/*
$content = preg_replace_callback(
  '/<img.+?>/', 
  function($matches) {
    return '<div class="image"></div>';
  }, $content
);
*/

echo $content;

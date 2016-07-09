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
  $request = $_GET['url'];
  // $request = preg_replace('/\?.*/', '', $request);
  $request = explode('/', $request);
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
require_once('../helpers/set_page_meta.php');

// print_r($_SERVER); exit;

function url_origin($s, $use_forwarded_host = false) {
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443') ) ? '' : ':'.$port;
    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url($s, $use_forwarded_host = false) {
  return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
}

$absolute_url = full_url( $_SERVER );

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
    'loop' => array(
      'name="og:url"' => $absolute_url,
    ),
  ),
  'pageTitle' => 'Page title',
  'socialProfiles' => array(
    'instagram' => 'https://www.instagram.com/vikibell/',
    'twitter' => 'https://twitter.com/Vikiibell/',
    'pinterest' => 'https://www.pinterest.com/vikiibell/',
    'linkedin' => 'https://www.linkedin.com/in/vikibell',
  ),
  'breadcrumbs' => array(
    'Home' => '/',
    'Categories' => '/categories/',
    'Life' => '/categories/life/',
  ),
  // Get and upload more version of this file
  'bannerImage' => array(
    'classes' => 'Banner-image u-fitToParent',
    'alt' => 'Viki Bell Banner Image',
    'src' => $static_uploads . '/2016/05/banner.jpg',
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

function set_prev_next() {
  global $vars, $config;

  $url = $_SERVER['REQUEST_URI'];

  $url = preg_replace('/\?.*/', '', $url);
  $canonical = $config['environment']['url'] . $url;

  // Define which page of results we are getting
  if(isset($_GET['page']) && is_numeric($_GET['page'])) {
    $vars['next'] = $url . '?page=' .($_GET['page'] + 1);
    $vars['canonical'] = $canonical;

    $prev = intval($_GET['page']) - 1;

    if($prev) {
      $vars['prev'] = $url . '?page=' . $prev;
    } else {
      $vars['prev'] = $url;
    }
  } else {
    $vars['next'] = $url . '?page=1';
  }
}

// print_r($_GET);

// Define which page of results we are getting
if(isset($_GET['page']) && is_numeric($_GET['page'])) {
  $pagination = $_GET['page'];
} else {
  $pagination = 0;
}

$header_code = 200;

// Route the request
if(isset($request[0])) {

  require_once('../helpers/redirects.php');

  if(isset($redirects[$_SERVER['REQUEST_URI']])) {
    header('Location: ' . $config['environment']['url'] . $redirects[$_SERVER['REQUEST_URI']]);
    exit;
  } elseif(isset($redirects[$_SERVER['REQUEST_URI'] . '/'])) {
    header('Location: ' . $config['environment']['url'] . $redirects[$_SERVER['REQUEST_URI'] . '/']);
    exit;
  } else {
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
        set_prev_next();
        require_once('search.php');
        break;

      case 'sitemap':
        require_once('sitemap.php');
        break;

      case 'drafts':
        require_once('drafts.php');
        break;
      
      default:
        require_once('page.php');
        break;
    }
  }
} else {
  set_prev_next();
  require_once('home.php');
}

http_response_code($header_code);

// Get the template
$template = $twig->loadTemplate($template_path . '.twig');

// gzip compress the content for optimization
ob_start("ob_gzhandler");

if(isset($_GET['json'])) {
  $json = array('posts' => $vars['posts']);

  if(isset($vars['hasTwitterWidget'])) {
      $json['hasTwitterWidget'] = $vars['hasTwitterWidget'];
  }
  echo json_encode($json); exit;
}

// Render the template
$content = $template->render(array('vars' => $vars));

// Remove empty paragraph tags
$content = str_replace('<p></p>', '', $content);
$content = str_replace('<p><p', '<p', $content);

// require_once('../helpers/minify_classes.php');
// $content = minify_html($content);

echo $content;

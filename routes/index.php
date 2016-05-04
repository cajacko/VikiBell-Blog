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
require_once('../helpers/bunting.php');

// Setup the variables to pass to the view
$vars = array(
  'config' => $config,
  'nav' => $site_navigation,
  'socialNav' => $social_navigation,
  'bunting' => $bunting,
  'isSingle' => false,
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
    case 'category':
      require_once('category.php');
      break;

    case 'categories':
      require_once('categories.php');
      break;

    case 'tag':
      require_once('tag.php');
      break;

    case 'tags':
      require_once('tags.php');
      break;

    case 'post':
      require_once('post.php');
      break;

    case 'search':
      require_once('search.php');
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

<?php
/**
 * Show a category taxonomy page
 */

// If no category was specified then redirect to the tags page
if(!isset($request[1])) {
  header('Location: ' . $redirect_base . 'categories');
  exit;
} 
// Otherwise show the category loop
else {
  require_once('../models/category.php');
  $category = get_category($request);

  // If the category exists then show the loop, otherwise route to 404
  if($category) {
    $posts = get_posts_by_category($category['id'], $pagination);
    $vars['posts'] = $posts;
    $vars['taxonomy'] = array('type' => 'category', 'details' => $category);
    $template_path .= 'loop';
  } else {
    require_once('404.php');
  }
}

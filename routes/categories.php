<?php
/**
 * Show the categories page 
 */

require_once('../models/categories.php');

// If no category was specified then get the categories page
if(!isset($request[1])) {
  $vars['categories'] = get_categories();
  $template_path .= 'categories';
} 
// Otherwise show the category loop
else {
  $category = get_category($request);

  // If the category exists then show the loop, otherwise route to 404
  if($category) {
    $posts = get_posts_by_category($category['term_taxonomy_id'], $pagination);
    $vars['posts'] = $posts;
    $vars['taxonomy'] = array('type' => 'category', 'details' => $category);
    $template_path .= 'loop';
  } else {
    require_once('404.php');
  }
}

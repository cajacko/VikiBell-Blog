<?php
/**
 * Show the categories page 
 */

require_once('../models/categories.php');

// If no category was specified then get the categories page
if(!isset($request[1])) {
  $vars['categories'] = get_categories();
  $template_path .= 'categories';

  $vars['breadcrumbs'] = array(
    'Home' => '/',
    'Categories' => '/categories/',
  );

  $vars['pageTitle'] = 'Categories';

  page_meta(array(
    'title' => 'Categories | Viki Bell',
    'description' => 'Find a topic you\'re interested in.',
  ));
} 
// Otherwise show the category loop
else {
  set_prev_next();
  $category = get_category($request);

  // var_dump($pagination);

  // If the category exists then show the loop, otherwise route to 404
  if($category) {
    $posts = get_posts_by_category($category['term_taxonomy_id'], $pagination);
    $vars['posts'] = $posts;
    $vars['taxonomy'] = array('type' => 'category', 'details' => $category);
    $template_path .= 'loop';

    $vars['breadcrumbs'] = array(
      'Home' => '/',
      'Categories' => '/categories/',
      $category['name'] => '/categories/' . $category['slug'],
    );

    $vars['pageTitle'] = 'Category: ' . $category['name'];

    $page_meta = array(
      'title' => $category['name'] . ' | Viki Bell',
    );

    set_loop_image_meta($vars['posts'], $page_meta);
    page_meta($page_meta);
  } else {
    require_once('404.php');
  }
}

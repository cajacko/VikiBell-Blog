<?php
/**
 * This script routes to an individual post or 404 error.
 */

// If no post slug is defined then route to 404
if(!isset($request[1])) {
  set_prev_next();
  require_once('../models/home.php');

  $vars['posts'] = get_default_posts($pagination);
  $template_path .= 'loop';

  $vars['breadcrumbs'] = array(
    'Home' => '/',
    'Posts' => '/posts/',
  );

  $vars['pageTitle'] = 'Posts';

  page_meta(array(
    'title' => 'Posts | Viki Bell',
    'description' => 'Check out my posts on life and stuff',
  ));
} 
/** 
 * Otherwise, if there is any additional url parameters then redirect 
 * to the actual post url.
 *
 * e.g. /posts/post-title/additional would redirect to /posts/post-title
 */
elseif(isset($request[2])) {
  $redirect = $redirect_base . $request[0] . '/' . $request[1];
  header('Location: ' . $redirect);
  exit;
} 
// Query is good so get the post if it exists
else {
  require_once('../models/post.php');
  $post = get_single_post($request[1], true); // Get the post based on the slug

  // If the post exists then show it, otherwise route to 404
  if(isset($post[0])) {
    $post = $post[0];
    $vars['post'] = $post;
    $vars['relatedPosts'] = get_related_post($post['id']);
    $vars['isSingle'] = true;
    $template_path .= 'single_post';

    $vars['breadcrumbs'] = array(
      'Home' => '/',
      'Posts' => '/posts/',
      $post['title'] => '/posts/' . $post['slug'],
    );

    unset($vars['pageTitle']);

    $page_meta = array(
      'title' => $vars['post']['title'] . ' | Viki Bell',
      'description' => $vars['post']['description'],
    );
  
    if(isset($post['image'])) {
      set_image_meta($post['image'], $page_meta);
    }

    page_meta($page_meta);
  } else {
    require_once('404.php');
  }
}

<?php
/**
 * Show the tags page 
 */

require_once('../models/tags.php');

// If no tag was specified then show the tags page
if(!isset($request[1])) {
  $vars['tags'] = get_tags();
  $template_path .= 'tags';
} 
// If there are additional url params then strip them out and redirect
elseif(isset($request[2])) {
  $redirect = $redirect_base . $request[0];

  if(isset($_GET['page'])) {
    $redirect .= '?page=' . $_GET['page'];
  }

  header('Location: ' . $redirect);
  exit;
} 
// Otherwise show the tag loop
else {
  $tag = get_tag($request[1]);

  // If the tag exists then show the loop, otherwise route to 404
  if($tag) {
    $posts = get_posts_by_tag($tag['id'], $pagination);
    $vars['posts'] = $posts;
    $vars['taxonomy'] = array('type' => 'tag', 'details' => $tag);
    $template_path .= 'loop';
  } else {
    require_once('404.php');
  }
}

<?php
/**
 * Show a tag taxonomy page
 */

// If no tag was specified then redirect to the tags page
if(!isset($request[1])) {
  header('Location: ' . $redirect_base . 'tags');
  exit;
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
  require_once('../models/tag.php');
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

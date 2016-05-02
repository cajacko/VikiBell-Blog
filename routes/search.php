<?php
/**
 * This script routes a search request
 */

// If there is more than 1 url parameter then redirect and strip the additional ones out
if(isset($request[1])) {
  $redirect = $redirect_base . $request[0];

  if(isset($_GET['s']) || isset($_GET['page'])) {
    $redirect .= '?';
  }

  if(isset($_GET['s'])) {
    $redirect .= 's=' . $_GET['s'];
  }

  if(isset($_GET['page'])) {
    if(isset($_GET['s'])) {
      $redirect .= '&';
    }

    $redirect .= 'page=' . $_GET['page'];
  }

  header('Location: ' . $redirect);
  exit;
} 
// Otherwise show the search page
else {
  require_once('../models/search.php');

  // If there is a search query then get the results
  if(isset($_GET['s']) && count($_GET['s'])) {
    $search_query = $_GET['s'];
    $posts = get_posts_by_search($search_query, $pagination);
  } else {
    $search_query = false;
    $posts = false;
  }

  $vars['posts'] = $posts;
  $vars['search'] = array('query' => $search_query);
  $template_path .= 'loop';
}

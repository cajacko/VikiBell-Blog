<?php

require_once('../helpers/post_meta.php');

function get_page($request) {
  global $db, $config, $global_queries;

  $query = '
    SELECT *
    FROM wp_posts
    WHERE ' . $global_queries['page_where'] . ' AND wp_posts.post_name = ?
    LIMIT 1
  ;';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $request[0]);
  $stmt->execute();
  $res = $stmt->get_result();
  $posts = post_meta($res);

  if(isset($posts[0])) {
    return $posts[0];
  } else {
    return false;
  } 
}

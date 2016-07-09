<?php

require_once('../helpers/post_meta.php');

function get_single_post($slug, $draft = false) {
  global $db, $config, $global_queries;

  if($draft) {
    $where = 'wp_posts.ID = ?';
  } else {
    $where = $global_queries['post_where'] . ' AND wp_posts.post_name = ?';
  }

  $query = '
    SELECT *
    FROM wp_posts
    WHERE ' . $where . '
    ' . $global_queries['post_order'] . '
    ' . $global_queries['post_limit'] . '
  ;';

  // print_r($query); exit;

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->bind_param("si", $slug, $pagination);
  $stmt->execute();
  $res = $stmt->get_result();
  $posts = post_meta($res);

  return $posts;
}

function get_related_post($post_id) {
  $related_posts = array();

  for($i = 0; $i < 3; $i++) {
    // $related_posts[] = temp_post();
  }

  return $related_posts;
}

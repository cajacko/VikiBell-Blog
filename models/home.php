<?php

require_once('../helpers/post_meta.php');

function get_default_posts($pagination) {
  global $db, $config, $global_queries, $posts_per_page;

  $query = '
    SELECT *
    FROM wp_posts
    WHERE ' . $global_queries['post_where'] . '
    ' . $global_queries['post_order'] . '
    ' . $global_queries['post_limit'] . '
  ;';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->bind_param("i", $offset);
  $offset = $pagination * $posts_per_page;
  $stmt->execute();
  $res = $stmt->get_result();
  $posts = post_meta($res);

  return $posts;
}

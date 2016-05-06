<?php

require_once('../helpers/post_meta.php');

function get_posts_by_search($search_query, $pagination) {
  global $db, $config, $global_queries;

  $search_term = '%' . $search_query . '%';

  $query = '
    SELECT *
    FROM wp_posts
    WHERE ' . $global_queries['post_where'] . ' AND (wp_posts.post_title LIKE ? OR wp_posts.post_content LIKE ?) 
    ' . $global_queries['post_order'] . '
    ' . $global_queries['post_limit'] . '
  ;';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->bind_param("ssi", $search_term, $search_term, $pagination);
  $stmt->execute();
  $res = $stmt->get_result();
  $posts = post_meta($res);

  return $posts;
}

<?php

$global_queries = array(
  'post_limit' => 'LIMIT ?, 4',
  'post_order' => 'ORDER BY wp_posts.post_date DESC',
  'post_where' => 'wp_posts.post_status = "publish" AND wp_posts.post_type = "post"',
);
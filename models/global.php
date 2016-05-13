<?php

$posts_per_page = 4;

$global_queries = array(
  'post_limit' => 'LIMIT ?, ' . $posts_per_page,
  'post_order' => 'ORDER BY wp_posts.post_date DESC',
  'post_where' => 'wp_posts.post_status = "publish" AND wp_posts.post_type = "post"',
  'page_where' => 'wp_posts.post_status = "publish" AND wp_posts.post_type = "page"',
);
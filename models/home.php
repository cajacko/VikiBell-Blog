<?php

require_once('../helpers/post_formatting.php');
require_once('featured_image.php');

function get_default_posts($pagination) {
  global $db;

  $query = '
    SELECT *
    FROM wp_posts
    WHERE wp_posts.post_status = "publish" AND wp_posts.post_type = "post"
    ORDER BY wp_posts.post_date DESC
    LIMIT ?, 5;
  ';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->bind_param("i", $pagination);
  $stmt->execute();
  $res = $stmt->get_result();
  $posts = array();

  while($post = $res->fetch_assoc()) {
    $post_array = array();

    $post_array['id'] = $post['ID'];

    $featured_image = get_featured_image($post['ID']);

    if($featured_image) {
      $post_array['image'] = $featured_image;
      $post_array['image']['classes'] = 'Post-featuredImage';
    }

    $post_array['date'] = array(
      'title' => format_post_date($post['post_date']),
      'datetime' => $post['post_date'],
    );

    $post_array['title'] = format_post_title($post['post_title']);
    $post_array['content'] = format_post_content($post['post_content']);

    $posts[] = $post_array;
  }

  return $posts;
}

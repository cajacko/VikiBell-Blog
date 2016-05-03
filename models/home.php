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
    LIMIT ?, 10;
  ';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->bind_param("i", $pagination);
  $stmt->execute();
  $res = $stmt->get_result();
  $posts = array();

  while($post = $res->fetch_assoc()) {
    $post_array = array(
      'id' => $post['ID'],
      'date' => array(
        'title' => format_post_date($post['post_date']),
        'datetime' => $post['post_date'],
      ),
      'title' => format_post_title($post['post_title']),
      'content' => format_post_content($post['post_content']),
    );

    $featured_image = get_featured_image($post['ID']);

    if(isset($featured_image['_wp_attached_file'])) {
      $post_array['image']['src'] = $featured_image['_wp_attached_file'];
    }

    if(isset($featured_image['_wp_attachment_image_alt'])) {
      $post_array['image']['alt'] = $featured_image['_wp_attachment_image_alt'];
    }

    $posts[] = $post_array;
  }

  return $posts;
}

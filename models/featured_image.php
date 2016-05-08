<?php

function get_image_by_url($url, $try_again = true) {
  global $db;

  $query = '
    SELECT wp_postmeta.*
    FROM wp_posts
    INNER JOIN wp_postmeta
      ON wp_posts.ID = wp_postmeta.post_id
    WHERE wp_posts.guid = ?
  ';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $url);
  $stmt->execute();
  $res = $stmt->get_result();

  // TODO: error handling

  // If there are no images by that name then strip the size string from the name and try again
  if(!$res->num_rows) {
    $url = preg_replace('/-[0-9]*x[0-9]*(?=\.)/', '', $url);
    $meta = get_image_by_url($url, false);

    return $meta;
  } else {
    return return_image_meta($res);
  }  
}

function get_featured_image($post_id) {
  global $db;

  $query = '
    SELECT *
    FROM wp_postmeta wm1

    LEFT JOIN
      wp_postmeta wm2
      ON (
        wm1.meta_value = wm2.post_id
        AND wm2.meta_value IS NOT NULL
      )
    WHERE wm1.post_id = ?
      AND wm1.meta_value IS NOT NULL
      AND wm1.meta_key = "_thumbnail_id"
  ';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->bind_param("i", $post_id);
  $stmt->execute();
  $res = $stmt->get_result();

  return return_image_meta($res);
}

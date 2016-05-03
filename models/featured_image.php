<?php

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
  
  $meta = array();

  while($post_meta = $res->fetch_assoc()) {
    $meta_value = $post_meta['meta_value'];

    if($post_meta['meta_key'] == '_wp_attachment_metadata') {
      $meta_value = unserialize($meta_value);
    }

    $meta[$post_meta['meta_key']] = $meta_value;
  }

  return $meta;
}

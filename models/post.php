<?php

function get_single_post($slug) {
  return temp_post();
}

function get_related_post($post_id) {
  $related_posts = array();

  for($i = 0; $i < 3; $i++) {
    $related_posts[] = temp_post();
  }

  return $related_posts;
}

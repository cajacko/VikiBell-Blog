<?php 

$temp_post_id = 23;

function temp_post() {
  global $temp_post_id;
  $temp_post_id++;
  
  return array(
    'id' => $temp_post_id,
    'date' => array(
      'title' => '26.04',
      'datetime' => '2016-04-01 12:00:00',
    ),
    'image' => array(
      'src' => '',
      'alt' => '',
    ),
    'title' => 'How to survive a zombie apolalypse',
    'content' => '<p>Hello there</p>',
  );
}

function temp_post_loop() {
  $posts = array();

  for($i = 0; $i < 10; $i++) {
    $posts[] = temp_post();
  }

  return $posts;
}

function temp_tag() {
  return array(
    'id' => 23,
  );
}

function temp_category() {
  return array(
    'id' => 36,
  );
}

function temp_404_suggestions() {
  return array(

  );
}

function temp_get_tags() {
  return array();
}

function temp_get_categories() {
  return array();
}

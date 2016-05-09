<?php

require_once('../models/featured_image.php');
require_once('post_formatting.php');

function post_meta($res) {
  global $config;

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

    $post_array['slug'] = $post['post_name'];
    $post_array['title'] = format_post_title($post['post_title']);
    $post_array['content'] = format_post_content($post['post_content']);
    $post_array['url'] = '/posts/' . $post['post_name'];
    $post_array['description'] = 'Content to get from db';
    $post_array['dateModified'] = '2016-01-01 12:12:12';

    $post_array['tweet'] = array(
      'url' => '',
      'text' => '',
      'via' => '',
    );

    $post_array['facebook'] = array(
      'url' => '',
    );

    $post_array['email'] = array(
      'subject' => '',
      'content' => '',
    );

    $posts[] = $post_array;
  }

  return $posts;
}
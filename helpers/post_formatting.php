<?php

function format_post_date($date) {
  $date = date('d.m', strtotime($date));
  return $date;
}

function format_post_title($title) {
  return $title;
}

function normalize($content) {
    // Normalize line endings
    // Convert all line-endings to UNIX format
    $content = str_replace("\r\n", "\n", $content);
    $content = str_replace("\r", "\n", $content);
    // Don't allow out-of-control blank lines
    $content = preg_replace("/\n{2,}/", "\n\n", $content);
    return $content;
}

function format_post_content($content) {
  global $vars;

  $content = normalize($content);
  $content = preg_replace('/\n/', '</p><p>', $content);
  $content = '<p>'.$content.'</p>';
  $content = str_replace('<p></p>', '', $content);
  $content = preg_replace("/&#?[a-z0-9]+;/i","",$content);

  if($vars['isSingle']) {
    $content = str_replace('<h5>', '<h4 class="Post-content--h3">', $content);
    $content = str_replace('</h5>', '</h4>', $content);
    $content = str_replace('<h4>', '<h4 class="Post-content--h3">', $content);
    $content = str_replace('</h4>', '</h4>', $content);
    $content = str_replace('<h3>', '<h4 class="Post-content--h3">', $content);
    $content = str_replace('</h3>', '</h4>', $content);
    $content = str_replace('<h2>', '<h3 class="Post-content--h2">', $content);
    $content = str_replace('</h2>', '</h3>', $content);
    $content = str_replace('<h1>', '<h2 class="Post-content--h1">', $content);
    $content = str_replace('</h1>', '</h2>', $content);
  } else {
    $content = str_replace('<h5>', '<h5 class="Post-content--h3">', $content);
    $content = str_replace('</h5>', '</h5>', $content);
    $content = str_replace('<h4>', '<h5 class="Post-content--h3">', $content);
    $content = str_replace('</h4>', '</h5>', $content);
    $content = str_replace('<h3>', '<h5 class="Post-content--h3">', $content);
    $content = str_replace('</h3>', '</h5>', $content);
    $content = str_replace('<h2>', '<h4 class="Post-content--h2">', $content);
    $content = str_replace('</h2>', '</h4>', $content);
    $content = str_replace('<h1>', '<h3 class="Post-content--h1">', $content);
    $content = str_replace('</h1>', '</h3>', $content);
  }

  return $content;
}

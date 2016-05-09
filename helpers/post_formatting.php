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

$image_template = $twig->loadTemplate('/sublayouts/image.twig');

function parse_images($content, $classes) {
  global $image_template, $static_public;

  $meta = get_image_by_url($content);
  $meta['classes'] = $classes;
  $image = $image_template->render(array('image' => $meta, 'vars' => array('staticPublic' => $static_public)));
  return $image;
}

function format_post_content($content) {
  global $vars, $twig;

  // Normalize line endings and blank spaces
  $content = normalize($content);

  // Parse captions for images
  $content = preg_replace_callback(
    '/\[caption.+?\](<img.+?>)(.+?)\[\/caption\]/', 
    function($matches) {
      return '<figure>' . $matches[1] . '<figcaption> ' . $matches[2] . '</figcaption></figure>';
    }, 
    $content
  );

  // Parse images and add multiple sources
  $content = preg_replace_callback(
    '/<img.+?src="(.+?)".+?>/', 
    function($matches) {
      return parse_images($matches[1], 'Post-image');
    },
    $content
  );

  // Replace new line with paragraphs
  $content = preg_replace('/\n/', '</p><p>', $content);
  $content = '<p>'.$content.'</p>';

  // Remove classes, this buggers up twitter embeds
  // $content = preg_replace('/ class=".+?"/', '', $content);

  // Remove styles
  $content = preg_replace('/ style=".+?"/', '', $content);

  // Remove empty paragraph tags
  $content = str_replace('<p></p>', '', $content);

  // Remove nested paragraph styles
  $content = str_replace('<p><p', '<p', $content);
  $content = str_replace('</p></p>', '</p>', $content);

  // Remove spans in the content
  $content = preg_replace('/<span.*?>/', '', $content);
  $content = str_replace('</span>', '', $content);

  // Remove those weird characters
  $content = preg_replace("/&#?[a-z0-9]+;/i","",$content);


  // Replace any empty headings
  $content = preg_replace("/<h[1-6]><\/h[1-6]>/","", $content);

  // Replace strong elements inside headings
  $content = preg_replace_callback('/<\/strong>(<\/h[1-6]>)/', function($matches) {
    return $matches[1];
  }, $content);

  $content = preg_replace_callback('/(<h[1-6]>)<strong>/', function($matches) {
    return $matches[1];
  }, $content);

  // Replace any headings within paragraphs
  $content = preg_replace_callback('/(<\/h[1-6]>)<\/p>/', function($matches) {
    return $matches[1];
  }, $content);

  $content = preg_replace_callback('/<p>(<h[1-6]>)/', function($matches) {
    return $matches[1];
  }, $content);

  // PArse headings
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

  // Wrap iframes
  $content = preg_replace_callback(
    '/<iframe.+?src="(.+?)".+?<\/iframe>/', 
    function($matches) {
      if (strpos($matches[1], 'www.boombox.com/widget/quiz') !== false) {
        return $matches[0];
      } else {
        return '<div class="Post-iframeWrap">' . $matches[0] . '</div>';
      }   
    }, 
    $content
  );

  // Remove paragraphs wrapping around lists
  $content = str_replace('<p><ol>', '<ol>', $content);
  $content = str_replace('<ol></p>', '<ol>', $content);
  $content = str_replace('<ol><p>', '<ol>', $content);
  $content = str_replace('</ol></p>', '</ol>', $content);
  $content = str_replace('<p></ol>', '</ol>', $content);
  $content = str_replace('</li></p>', '</li>', $content);
  $content = str_replace('<p><li>', '<li>', $content);
  $content = str_replace('</li><p>', '</li>', $content);

  

  return $content;
}

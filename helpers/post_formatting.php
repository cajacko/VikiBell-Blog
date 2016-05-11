<?php

function format_post_date($date) {
  $date = date('d.m', strtotime($date));
  return $date;
}

function format_post_title($title) {
  return $title;
}

function normalize_line_endings(&$content) {
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

function format_captions(&$content) {
  // Parse captions for images
  $content = preg_replace_callback(
    '/\[caption.+?\](<img.+?>)(.+?)\[\/caption\]/', 
    function($matches) {
      return '<figure>' . $matches[1] . '<figcaption> ' . $matches[2] . '</figcaption></figure>';
    }, 
    $content
  );

  return $content;
}

function format_images(&$content) {
  // Remove links around images
  $content = preg_replace_callback(
    '/<a[^>]*>(<img.+?>)<\/a>/', 
    function($matches) {
      return $matches[1];
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

  return $content;
}

function replace_new_lines_with_p(&$content) {
  // echo $content; exit;
  // Replace new line with paragraphs
  $content = preg_replace_callback(
    '/(<\/li>|<\/ul>|<ul>|<\/ol>)\n/', 
    function($matches) {
      // print_r($matches[1]); exit;
      // return '<ul>';
      return $matches[1];
    }, 
    $content
  );

  // $content = str_replace("\n", '', $content);

  $content = str_replace("\n", '</p><p>', $content);
  $content = str_replace("<p><ul>", '<ul>', $content);
  $content = str_replace("<p><ol>", '<ol>', $content);

  // Replace new line with paragraphs
  $content = preg_replace_callback(
    '/^([^<])/', 
    function($matches) {
      return '<p>' . $matches[0];
    }, 
    $content
  );

  $content = preg_replace_callback(
    '/.*(?<!>)$/', 
    function($matches) {
      return $matches[0] . '</p>';
    }, 
    $content
  );

  return $content;
}

function remove_classes(&$content) {
  $content = preg_replace('/ class=".+?"/', '', $content);
  return $content;
}

function use_correct_headings(&$content) {
  global $vars;

  // PArse headings
  if($vars['isSingle']) {
    $content = str_replace('<h6>', '<h5 class="Post-content--h3">', $content);
    $content = str_replace('</h6>', '</h5>', $content);
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
  } else {
    $content = str_replace('<h6>', '<h6 class="Post-content--h3">', $content);
    $content = str_replace('</h6>', '</h6>', $content);
    $content = str_replace('<h5>', '<h6 class="Post-content--h3">', $content);
    $content = str_replace('</h5>', '</h6>', $content);
    $content = str_replace('<h4>', '<h6 class="Post-content--h3">', $content);
    $content = str_replace('</h4>', '</h6>', $content);
    $content = str_replace('<h3>', '<h6 class="Post-content--h3">', $content);
    $content = str_replace('</h3>', '</h6>', $content);
    $content = str_replace('<h2>', '<h5 class="Post-content--h2">', $content);
    $content = str_replace('</h2>', '</h5>', $content);
    $content = str_replace('<h1>', '<h4 class="Post-content--h1">', $content);
    $content = str_replace('</h1>', '</h4>', $content);
  }

  return $content;
}

function remove_styles(&$content) {
  // Remove styles
  $content = preg_replace('/ style=".+?"/', '', $content);
  return $content;
}

function remove_empty_p(&$content) {
  // Remove empty paragraph tags
  $content = str_replace('<p></p>', '', $content);
  return $content;
}

function remove_blank_div(&$content) {
  // Remove div's doing nothing
  $content = str_replace('<div>', '', $content);
  $content = str_replace('</div>', '', $content);
  return $content;
}

function remove_nested_p(&$content) {
  // Remove nested paragraph styles
  $content = str_replace('<p><p', '<p', $content);
  $content = str_replace('</p></p>', '</p>', $content);
  return $content;
}

function remove_span(&$content) {
  // Remove spans in the content
  $content = preg_replace('/<span.*?>/', '', $content);
  $content = str_replace('</span>', '', $content);
  return $content;
}

function remove_abstract(&$content) {
  // Remove those weird characters
  $content = preg_replace("/&#?[a-z0-9]+;/i","",$content);
  return $content;
}

function remove_empty_headings(&$content) {
  // Replace any empty headings
  $content = preg_replace("/<h[1-6]><\/h[1-6]>/","", $content);
  return $content;
}

function replace_strong_inside_headings(&$content) {
  // Replace strong elements inside headings
  $content = preg_replace_callback('/<\/strong>(<\/h[1-6]>)/', function($matches) {
    return $matches[1];
  }, $content);

  $content = preg_replace_callback('/(<h[1-6]>)<strong>/', function($matches) {
    return $matches[1];
  }, $content);

  return $content;
}

function strip_headings_inside_p(&$content) {
  // Replace any headings within paragraphs
  $content = preg_replace_callback('/(<\/h[1-6]>)<\/p>/', function($matches) {
    return $matches[1];
  }, $content);

  $content = preg_replace_callback('/<p>(<h[1-6]>)/', function($matches) {
    return $matches[1];
  }, $content);

  return $content;
}

function wrap_iframes(&$content) {
  // Wrap iframes
  $content = preg_replace_callback(
    '/<iframe.+?src="(.+?)".+?<\/iframe>/', 
    function($matches) {
      $string = $matches[0];
      $string = str_replace('frameborder="0"', '', $string);
      $string = str_replace('scrolling="no"', '', $string);
      $string = str_replace('webkitAllowFullScreen', '', $string);
      $string = str_replace('mozallowfullscreen', '', $string);
      $string = str_replace('allowFullScreen', '', $string);

      if (strpos($matches[1], 'www.boombox.com/widget/quiz') !== false) {
        return $string;
      } else {
        return '<div class="Post-iframeWrap">' . $string . '</div>';
      }   
    }, 
    $content
  );

  return $content;
}

function remove_p_in_lists(&$content) {
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

function strip_new_lines(&$content) {
  $content = preg_replace_callback('/<blockquote class="twitter-tweet".+?<\/script>|<ul>.+?<\/ul>|<ol>.+?<\/ol>/s', function($matches) {
    $string = $matches[0];
    $string = str_replace("\n", '', $string);
    $string = trim(preg_replace('/\t+/', '', $string));
    $string = $string;
    return $string;
  }, $content);

  return $content;
}

function remove_p_around_blockquote(&$content) {
  $content = str_replace('<p><blockquote', '<blockquote', $content);
  $content = str_replace('</script></p>', '</script>', $content);
  return $content;
}

function remove_blank_headings(&$content) {
  $content = preg_replace_callback('/<h[1-6].+?<\/h[1-6]>/', function($matches) {
    return '';
  }, $content);

  return $content;
}

function format_post_content($content) {
  normalize_line_endings($content); // Normalize line endings and blank spaces
  strip_new_lines($content);
  format_captions($content);
  format_images($content);
  replace_new_lines_with_p($content);
  remove_p_around_blockquote($content);

  // remove_classes($content); // Remove classes, this buggers up twitter embeds
  // remove_blank_div($content)
  remove_styles($content);
  // remove_empty_p($content);
  remove_nested_p($content);
  remove_span($content);
  remove_abstract($content);
  // remove_empty_headings($content);
  replace_strong_inside_headings($content);

  remove_blank_headings($content);
  strip_headings_inside_p($content);
  use_correct_headings($content);
  wrap_iframes($content);
  // remove_p_in_lists($content);

  return $content;
}

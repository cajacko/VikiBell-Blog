<?php


function set_meta_as($val, $keys) {
  global $vars;

  foreach($keys as $key) {
     $vars['meta']['loop'][$key] = $val;
  }
}

function set_image_meta($image, &$page_meta) {
  if(isset($image['src'])) {
    $page_meta['image'] = $image['src'];

    if(isset($image['width'])) {
      $page_meta['og:image:width'] = $image['width'];
    }

    if(isset($image['height'])) {
      $page_meta['og:image:height'] = $image['height'];
    }

    if(isset($image['alt'])) {
      $page_meta['twitter:image:alt'] = $image['alt'];
    }
  }
}

function set_loop_image_meta($posts, &$page_meta) {
  foreach($posts as $post) {
    if(isset($post['image'])) {
      set_image_meta($post['image'], $page_meta);
      break;
    }
  }
}

function page_meta($args) {
  global $vars;

  if(isset($args['title'])) {
    $vars['meta']['title'] = $args['title'];

    set_meta_as($args['title'], array(
      'name="og:title"',
      'name="twitter:title"',
    ));
  }

  if(isset($args['og:title'])) {
    set_meta_as($args['og:title'], array('name="og:title"',));
  }

  if(isset($args['twitter:title'])) {
    set_meta_as($args['twitter:title'], array('name="twitter:title"',));
  }

  if(isset($args['description'])) {
    set_meta_as($args['description'], array(
      'name="description"',
      'name="og:description"',
      'name="twitter:description"'
    ));
  }

  if(isset($args['twitter:description'])) {
    set_meta_as($args['twitter:description'], array('name="twitter:description"',));
  }

  if(isset($args['image'])) {
    set_meta_as($args['image'], array(
      'name="og:image"',
      'name="twitter:image"',
    ));
  }

  if(isset($args['og:image'])) {
    set_meta_as($args['og:image'], array('name="og:image"',));
  }

  if(isset($args['twitter:image'])) {
    set_meta_as($args['twitter:image'], array('name="twitter:image"',));
  }

  if(isset($args['twitter:image:alt'])) {
    set_meta_as($args['twitter:image:alt'], array('name="twitter:image:alt"',));
  }

  if(isset($args['og:image:type'])) {
    set_meta_as($args['og:image:type'], array('name="og:image:type"',));
  }

  if(isset($args['og:image:width'])) {
    set_meta_as($args['og:image:width'], array('name="og:image:width"',));
  }

  if(isset($args['og:image:height'])) {
    set_meta_as($args['og:image:height'], array('name="og:image:height"',));
  }

  if(isset($args['og:updated_time'])) {
    set_meta_as($args['og:updated_time'], array('name="og:updated_time"',));
  }

  if(isset($args['og:type'])) {
    set_meta_as($args['og:type'], array('name="og:type"',));
  } else {
    set_meta_as('website', array('name="og:type"',));
  }

  if(isset($args['og:article:author'])) {
    set_meta_as($args['og:article:author'], array('name="og:article:author"',));
  }

  if(isset($args['og:article:modified_time'])) {
    set_meta_as($args['og:article:modified_time'], array('name="og:article:modified_time"',));
  }

  if(isset($args['og:article:published_time'])) {
    set_meta_as($args['og:article:published_time'], array('name="og:article:published_time"',));
  }
}
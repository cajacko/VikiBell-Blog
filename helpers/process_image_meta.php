<?php

$image_count = 0;

function return_image_meta($res) {
  global $image_count, $static_uploads;
  $meta = array();
  $array = array();
  
  while($post_meta = $res->fetch_assoc()) {
    $meta_value = $post_meta['meta_value'];

    if($post_meta['meta_key'] == '_wp_attachment_metadata') {
      $meta_value = unserialize($meta_value);
    }

    $meta[$post_meta['meta_key']] = $meta_value;
  }

  if(isset($meta['_wp_attached_file'])) {
    $dir = $static_uploads . '/';

    $array['src'] = $dir . $meta['_wp_attached_file'];
    $explode = explode('/', $meta['_wp_attached_file']);
  
    for($i = 0; $i < (count($explode) - 1); $i++) {
      $dir .= $explode[$i] . '/';
    }

    if(isset($meta['_wp_attachment_metadata'])) {
      if(isset($meta['_wp_attachment_metadata']['sizes'])) {
        $srcset = '';
        $count = 0;

        foreach($meta['_wp_attachment_metadata']['sizes'] as $name => $size) {
          if('width' == explode('-', $name)[0]) {
            $count++;
          }
        }

        $length = $count;
        $count = 0;

        foreach($meta['_wp_attachment_metadata']['sizes'] as $name => $size) {
          if('width' == explode('-', $name)[0]) {
            // $array['sizes'][$size['width']] = $dir . $size['file'];

            $srcset .= $dir . $size['file'] . ' ' . $size['width'] . 'w';

            $count++;

            if($count < $length) {
              $srcset .= ', ';
            }
          }
        }

        $array['sizes'] = $srcset;
      }
 
      $array['width'] = $meta['_wp_attachment_metadata']['width'];
      $array['height'] = $meta['_wp_attachment_metadata']['height'];
    }

    if(isset($meta['_wp_attachment_image_alt'])) {
      $array['alt'] = $meta['_wp_attachment_image_alt'];
    }

    if($image_count > 5) {
      $array['lazyLoad'] = true;
    } else {
      $array['lazyLoad'] = false;
    }

    $image_count++;
  }

  return $array;
}
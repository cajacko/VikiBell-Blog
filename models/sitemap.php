<?php

function return_sitemap_item($title, $url, $lastmod, $changefreq, $priority) {
  global $config;

  $url = $config['environment']['url'] . '/' . $url;

  return array(
      'title' => $title,
      'url' => $url,
      'lastmod' => $lastmod,
      'changefreq' => $changefreq,
      'priority' => $priority,
  );
}

function return_sitemap() {
  global $db, $config;

  $last_updated_post_date = '';
  $last_updated = '';

  $sitemap = array();

  $query = '
    SELECT * 
    FROM  wp_posts
    WHERE post_type =  "post"
    AND post_status =  "publish"
  ';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->execute();
  $res = $stmt->get_result();
  $posts = array();

  while($post = $res->fetch_assoc()) {
    $sitemap[] = return_sitemap_item($post['post_title'], 'posts/' . $post['post_name'], $post['post_date'], 'weekly', 0.75);
  }

  $query = '
    SELECT * 
    FROM  wp_posts
    WHERE post_type =  "page"
    AND post_status =  "publish"
  ';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->execute();
  $res = $stmt->get_result();
  $posts = array();

  while($post = $res->fetch_assoc()) {
    // TODO: check for parent
    $sitemap[] = return_sitemap_item($post['post_title'], $post['post_name'], $post['post_date'], 'weekly', 0.75);
  }

  $query = '
    SELECT wp_terms.name, wp_terms.slug, wp_term_taxonomy.taxonomy, wp_term_taxonomy.count, wp_term_taxonomy.parent
    FROM wp_term_taxonomy
    INNER JOIN wp_terms
      ON wp_terms.term_id = wp_term_taxonomy.term_id
    WHERE wp_term_taxonomy.count > 0
    ORDER BY wp_term_taxonomy.taxonomy ASC
  ';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->execute();
  $res = $stmt->get_result();
  $posts = array();

  while($post = $res->fetch_assoc()) {
    if('category' == $post['taxonomy']) {
      $url = 'categories/';
    } elseif('post_tag' == $post['taxonomy']) {
      $url = 'tags/';
    } else {
      $url = false;
    }

    if($url) {
      $sitemap[] = return_sitemap_item($post['name'], $url . $post['slug'], $last_updated_post_date, 'weekly', 0.5);
    }
  }

  $sitemap[] = return_sitemap_item('Home', '', $last_updated_post_date, 'daily', 1);
  $sitemap[] = return_sitemap_item('Search', 'search', $last_updated_post_date, 'daily', 0.25);
  $sitemap[] = return_sitemap_item('Sitemap', 'sitemap', $last_updated, 'daily', 0.5);
  $sitemap[] = return_sitemap_item('Posts', 'posts', $last_updated, 'daily', 0.5);
  $sitemap[] = return_sitemap_item('Categories', 'categories', $last_updated, 'daily', 0.5);
  $sitemap[] = return_sitemap_item('Tags', 'tags', $last_updated, 'daily', 0.5);

  return $sitemap;
}

function create_xml_sitemap() {
  $sitemap = return_sitemap();

  $content = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

  foreach($sitemap as $item) {
    $content .= '<url><loc>' . $item['url'] . '</loc><lastmod>' . $item['lastmod'] . '</lastmod><changefreq>' . $item['changefreq'] . '</changefreq><priority>' . $item['priority'] . '</priority></url>';
  }

  $content .= '</urlset> ';

  file_put_contents('../public/sitemap.xml', $content);
}
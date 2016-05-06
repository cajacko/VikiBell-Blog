<?php

function return_sitemap_item($title, $url, $lastmod, $changefreq, $priority) {
  global $config;

  $url = $config['environment']['url'] . '/' . $url;
  $lastmod = date(DATE_W3C, strtotime($lastmod));

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
    SELECT wp_terms.name, wp_terms.slug, wp_term_taxonomy.count, wp_term_taxonomy.parent
    FROM wp_term_taxonomy
    INNER JOIN wp_terms
      ON wp_terms.term_id = wp_term_taxonomy.term_id
    WHERE wp_term_taxonomy.count > 0 AND wp_term_taxonomy.taxonomy = "category"
    ORDER BY wp_term_taxonomy.taxonomy ASC
  ';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->execute();
  $res = $stmt->get_result();
  $posts = array();

  while($post = $res->fetch_assoc()) {
    $sitemap[] = return_sitemap_item($post['name'], 'categories/' . $post['slug'], $last_updated_post_date, 'weekly', 0.5);
  }

  $sitemap[] = return_sitemap_item('Home', '', $last_updated_post_date, 'daily', 1);
  $sitemap[] = return_sitemap_item('Search', 'search', $last_updated_post_date, 'daily', 0.25);
  $sitemap[] = return_sitemap_item('Sitemap', 'sitemap', $last_updated, 'daily', 0.5);
  $sitemap[] = return_sitemap_item('Posts', 'posts', $last_updated, 'daily', 0.5);
  $sitemap[] = return_sitemap_item('Categories', 'categories', $last_updated, 'daily', 0.5);

  return $sitemap;
}

function sitemap_page() {
  $sitenav = return_sitemap();
  $return = array();

  foreach($sitenav as $item) {
    $url = $item['url'];

    $explode = explode('/', $url);

    if(isset($explode[4])) {
      $return[$explode[3]]['subLevel'][] = array('link' => '/' . $explode[3] . '/' . $explode[4] . '/', 'title' => $item['title']);
    } else {
      $return[$explode[3]]['title'] = $item['title'];
      $return[$explode[3]]['link'] = '/' . $explode[3] . '/';
    }
  }

  return $return;
}

function create_robots_file() {
  global $config;

  $content = 'Sitemap: ' . $config['environment']['url'] . '/sitemap.xml
User-agent: *
Disallow:';

  file_put_contents('../public/robots.txt', $content);
}

function create_xml_sitemap() {
  $sitemap = return_sitemap();

  $content = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

  foreach($sitemap as $item) {
    $content .= '<url><loc>' . $item['url'] . '</loc><lastmod>' . $item['lastmod'] . '</lastmod><changefreq>' . $item['changefreq'] . '</changefreq><priority>' . $item['priority'] . '</priority></url>';
  }

  $content .= '</urlset> ';

  file_put_contents('../public/sitemap.xml', $content);
  create_robots_file();
}
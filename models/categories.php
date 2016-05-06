<?php

require_once('../helpers/post_meta.php');

function get_categories() {
  global $db;

  $query = '
    SELECT wp_terms.name, wp_terms.slug, wp_term_taxonomy.count, wp_term_taxonomy.parent
    FROM wp_term_taxonomy
    INNER JOIN wp_terms
      ON wp_terms.term_id = wp_term_taxonomy.term_id
    WHERE wp_term_taxonomy.count > 0 && wp_term_taxonomy.taxonomy = "category"
    ORDER BY wp_term_taxonomy.taxonomy ASC
  ';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->execute();
  $res = $stmt->get_result();
  $categories = array();

  while($category = $res->fetch_assoc()) {
    $categories[] = $category;
  }

  return $categories;
}

function get_category($request) {
  global $db;

  $query = '
    SELECT wp_terms.name, wp_terms.slug, wp_term_taxonomy.count, wp_term_taxonomy.parent, wp_term_taxonomy.term_taxonomy_id
    FROM wp_term_taxonomy
    INNER JOIN wp_terms
      ON wp_terms.term_id = wp_term_taxonomy.term_id
    WHERE wp_term_taxonomy.count > 0 && wp_term_taxonomy.taxonomy = "category" AND wp_terms.slug = ?
    ORDER BY wp_term_taxonomy.taxonomy ASC
  ';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $request[1]);
  $stmt->execute();
  $res = $stmt->get_result();
  $category = $res->fetch_assoc();

  return $category;
}

function get_posts_by_category($category_id, $pagination) {
  global $db, $global_queries;

  $query = '
    SELECT * 
    FROM wp_posts
    INNER JOIN wp_term_relationships
      ON wp_posts.ID = wp_term_relationships.object_id
    WHERE ' . $global_queries['post_where'] . ' AND wp_term_relationships.term_taxonomy_id = ?
    ' . $global_queries['post_order'] . '
    ' . $global_queries['post_limit'] . '
  ';

  // prepare and bind
  $stmt = $db->prepare($query);
  $stmt->bind_param("ii", $category_id, $pagination);
  $stmt->execute();
  $res = $stmt->get_result();
  $posts = post_meta($res);

  return $posts;
}

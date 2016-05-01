<?php

$bunting = array();
    
$count = 1;

while($count < 100) {
  $bunting_item = array('colour' => '', 'dots' => array());
    
  if( $count % 5 == 0 ) {
    $colour = 1;
  } elseif( $count % 4 == 0 ) {
    $colour = 2;
  } elseif( $count % 3 == 0 ) {
    $colour = 3;
  } elseif( $count % 2 == 0 ) {
    $colour = 4;
  } else {
    $colour = 5;
  }

  $bunting_item['colour'] = $colour;
    
  for($i = 1; $i < 9; $i++) {
    $bunting_item['dots'][] = $i;
  }

  $bunting[] = $bunting_item;
  
  $count++;
}

$nav = array(
  array(
    'url' => '/',
    'title' => 'Viki Bell',
    'internal' => true,
  ),
  array(
    'url' => '/about',
    'title' => 'About',
    'icon' => 'female',
    'internal' => true,
    'subNav' => array(
      array(
        'url' => '/about',
        'title' => 'About',
        'internal' => true,
      ),
      array(
        'url' => '/contact',
        'title' => 'Contact',
        'internal' => true,
      ),
    ),
  ),
  array(
    'url' => '/category/life',
    'title' => 'Life',
    'icon' => 'female',
    'internal' => true,
  ),
  array(
    'url' => '/category/life',
    'title' => 'Events',
    'icon' => 'female',
    'internal' => true,
  ),
  array(
    'url' => '/category/life',
    'title' => 'Travel',
    'icon' => 'female',
    'internal' => true,
  ),
  array(
    'url' => '/about',
    'title' => 'Food',
    'icon' => 'female',
    'internal' => true,
    'subNav' => array(
      array(
        'url' => '/about',
        'title' => 'Recipes',
        'internal' => true,
      ),
      array(
        'url' => '/contact',
        'title' => 'Resturants',
        'internal' => true,
      ),
    ),
  ),
);

$social_nav = array(
  array(
    'url' => '/',
    'icon' => 'instagram',
  ),
  array(
    'url' => '/',
    'icon' => 'twitter',
  ),
);

$global_vars = array(
  'config' => $config,
  'nav' => $nav,
  'socialNav' => $social_nav,
  'bunting' => $bunting,
);

$posts = array(
  array(
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
  ),
  array(
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
  ),
  array(
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
  ),
  array(
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
  ),
);


$template = $twig->loadTemplate('templates/home.twig');

// gzip compress content
ob_start("ob_gzhandler");
  echo $template->render(array('global' => $global_vars, 'posts' => $posts));

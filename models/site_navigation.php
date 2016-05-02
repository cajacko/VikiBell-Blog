<?php

$site_navigation = array(
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
    'icon' => 'leaf',
    'internal' => true,
  ),
  array(
    'url' => '/category/life',
    'title' => 'Events',
    'icon' => 'calendar',
    'internal' => true,
  ),
  array(
    'url' => '/category/life',
    'title' => 'Travel',
    'icon' => 'plane',
    'internal' => true,
  ),
  array(
    'url' => '/about',
    'title' => 'Food',
    'icon' => 'cutlery',
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

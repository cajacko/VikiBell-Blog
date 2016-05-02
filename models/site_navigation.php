<?php

$site_navigation = array(
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

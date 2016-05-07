<?php

$site_navigation = array(
  array(
    'url' => '',
    'title' => 'About',
    'icon' => 'female',
    'internal' => true,
    'subNav' => array(
       'id' => 'SiteNav-subNav--1',
      'items' => array(
        array(
          'url' => '/about-me',
          'title' => 'About',
          'internal' => true,
        ),
        array(
          'url' => '/contact',
          'title' => 'Contact',
          'internal' => true,
        ),
        array(
          'url' => '/sitemap',
          'title' => 'Sitemap',
          'internal' => true,
        ),
      ),
    ),
  ),
  array(
    'url' => '/categories/life',
    'title' => 'Life',
    'icon' => 'leaf',
    'internal' => true,
  ),
  array(
    'url' => '/categories/events',
    'title' => 'Events',
    'icon' => 'calendar',
    'internal' => true,
  ),
  array(
    'url' => '/categories/travel',
    'title' => 'Travel',
    'icon' => 'flight',
    'internal' => true,
  ),
  array(
    'url' => '',
    'title' => 'Food',
    'icon' => 'food',
    'internal' => true,
    'subNav' => array(
      'id' => 'SiteNav-subNav--2',
      'items' => array(
        array(
          'url' => '/categories/recipes',
          'title' => 'Recipes',
          'internal' => true,
        ),
        array(
          'url' => '/categories/restaurants',
          'title' => 'Restaurants',
          'internal' => true,
        ),
      ),
    ),
  ),
);

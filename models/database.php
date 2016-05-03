<?php

global $db;

$db = new mysqli(
  $config['database']['host'], 
  $config['database']['user'], 
  $config['database']['password'], 
  $config['database']['database']
);

$db->set_charset('utf8mb4'); 

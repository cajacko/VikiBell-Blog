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

<?php

// Can save around 12% by doing this, accross html, css and js;

// This function provides a difference in page size of 19.1kb -> 18.1kb, around 25%;
function minify_html($content) {
    $content = preg_replace_callback(
      '/(?<= class=").+?(?=")/', 
      function($matches) {
        $string = $matches[0];
        $explode = explode(' ', $string);
        $string = '';

        foreach($explode as $class) {
          $string .= 'ab ';
        }

        $string = trim($string);

        return $string;
      }, $content
    );

    return $content;
}

// First minify all css classes. Store each unique in an array with the minified name
// Then do js, add any additional unique names to the array
// Finally do html, if a class comes up that isn't in the array then warn the user that there is an unused class, and dleete it. Option not to delete

// Size difference: 6761 > 5724
function minify_css($file = '../public/styles/style.min.css') {
    $css = file_get_contents($file);

    $css = preg_replace_callback(
      '/(?<=\.)([a-zA-Z].+?)(?=\,|\{|:|\>| )/', 
      function($matches) {
        return 'ab';
      }, $css
    );

    file_put_contents($file, $css);
}

// Size difference 89,432 > 89,332 .min.js
// 267,449 > 266,868 .js
function minify_js($file = '../public/js/script.min.js') {
    $js = file_get_contents($file);

    $js = preg_replace_callback(
      '/(?<=\(\"\.)[a-zA-Z].+?(?=\"\))/', 
      function($matches) {
        return 'ab';
      }, $js
    );

    file_put_contents($file, $js);
}

function print_difference() {
    $before = array(
        'html' => 19100,
        'css' => 6761,
        'js' => 89432
    );

    $after = array(
        'html' => 18100,
        'css' => 5724,
        'js' => 89332
    );

    $total_before = 0;

    foreach($before as $value) {
        $total_before = $total_before + $value;
    }

    $total_after = 0;

    foreach($after as $value) {
        $total_after = $total_after + $value;
    }

    $total_savings = $total_before - $total_after;
    $percentage = round(1 - ($total_after / $total_before), 2) * 100;

    echo 'Total before: ' . $total_before . ' Total after: ' . $total_after . ' Savings: ' . $total_savings . ' % Savings: ' . $percentage .'%';
}
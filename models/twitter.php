<?php 

require_once('../helpers/get_date_diff.php');  
use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth(
  $config['twitter']['consumerKey'], 
  $config['twitter']['consumerSecret'], 
  $config['twitter']['accessToken'], 
  $config['twitter']['accessTokenSecret']
);
        
$tweet_response = $connection->get( "statuses/user_timeline", array( "user_id" => $config['twitter']['userId'], "count" => 100, 'exclude_replies' => TRUE ) );   

$count = 0;
$tweets = array();

foreach($tweet_response as $tweet) {
  $text = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $tweet->text);
  $text = preg_replace('/(?<=^|(?<=[^a-zA-Z0-9-_\.]))@([A-Za-z]+[A-Za-z0-9]+)/', '<a href="http://twitter.com/$1" target="_blank">@$1</a>', $text);
  $text = preg_replace('/(?<=^|(?<=[^a-zA-Z0-9-_\.]))#([A-Za-z]+[A-Za-z0-9]+)/', '<a href="http://twitter.com/hashtag/$1" target="_blank">#$1</a>', $text); 

  $tweet_array = array(
    'date' => array(
      'text' => get_date_diff(strtotime($tweet->created_at)), 
      'dateTime' => $tweet->created_at,
    ),
    'tweetLink' => 'http://twitter.com/Vikiibell',
    'profile' => array(
      'link' => 'http://twitter.com/' . $tweet->user->screen_name,
      'name' => $tweet->user->name,
      'handle' => '@' . $tweet->user->screen_name,
      'image' => array(
        'src' => $tweet->user->profile_image_url,
        'alt' => 'Viki bell twitter image',
      )
    ),
    'content' => $text,
  );

  if(isset($tweet->entities->media[0]->media_url)) {
    $tweet_array['featuredImage'] = array(
      'src' => $tweet->entities->media[0]->media_url,
      'alt' => 'Featured image',
    );
  }

  $tweets[] = $tweet_array;
  $count++;

  if($count >= 3) {
    break;
  }
}

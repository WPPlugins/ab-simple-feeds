<?php
/*
Plugin name: AB Simple Feeds
Author: Aboobacker P Ummer
Plugin URI: http://aboobacker.com
Description: A simple plugin help you to embed feeds from other websites on your website. Use the shortcode <code>[feed url="http://your_feed_url.com"]</code>. More parameters are here: <code>url</code> - feed URL, <code>show</code> - number of posts to pull from the feed (default:10), <code>scroll</code> - number of items to be shown in the scroll (default:5), <code>speed</code> - scrolling speed (default:1000), <code>pause</code> - time between each scroll (default: 3000). To turn off scrolling effect and just to display the list of posts, use this parameter: <code>static = true</code>
Version: 1.0
Author URI: http://aboobacker.com
*/
function display_ab_feed($atts){
	extract( shortcode_atts( array (
        'show' => 10,
		'url' => '',
		'scroll' => 5,
		'speed' => 1000,
		'pause'=> 3000,
		'static' => false
	), $atts ) );
	$data = '';
	if($url){
	include_once( ABSPATH . WPINC . '/feed.php' );
	$rss = fetch_feed( esc_url($url) );
	$maxitems = 0;
	if ( ! is_wp_error( $rss ) ) :
		$maxitems = $rss->get_item_quantity( $show ); 
		$rss_items = $rss->get_items( 0, $maxitems );
	endif;
	if ( $maxitems != 0 ) {
	if(!$static)wp_enqueue_script( 'vticker',  plugin_dir_url( __FILE__ ) . 'js/jquery.vticker.min.js', array(), '1.0.0', true );
	$data .= '<div id="ab_feed_container"><input type="hidden" id="ab_feeds_num" value="'.$show.'" /><input type="hidden" id="ab_feeds_scroll" value="'.$scroll.'" /><input type="hidden" id="ab_feeds_speed" value="'.$speed.'" /><input type="hidden" id="ab_feeds_pause" value="'.$pause.'" /><ul id="ab_custom_feeds" class="ab_custom_feeds">';
		 foreach ( $rss_items as $item ) {
			$data .= '<li> <a target="_blank" rel="nofollow" href="'.esc_url( $item->get_permalink() ).'" title="Published on '.$item->get_date("j F Y | g:i a").'">'.esc_html( $item->get_title() ).'</a></li>';
		 } 
	$data .= '</ul></div>'; 
	}
	else{
		$data = "<p><code>No posts found in the specified feed. Please check the feed URL.</code></p>";
	}
	}
	else { $data = "<p><code>Please enter the correct feed URL.</code> Eg:- <strong><code>".site_url()."/feed/</code></strong></p>";}
	return $data;
}
add_shortcode('feed','display_ab_feed');
?>
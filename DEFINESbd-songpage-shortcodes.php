<?php
/*
Plugin Name: Ben's Song-Page Shortcodes
Plugin URI: http://bendeschamps.com
Description: Plugin to enable Album & Song shortcodes in WordPress without Woo stupidity
Version: 0.2
Author: Ben Deschamps
Author URI: http://bendeschamps.com
License: GPLv2
*/

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

function bd_songpage_scripts() {
	wp_enqueue_style( 'bd-songpage-css', plugins_url( 'bd-songpage-shortcodes/css/style.css' ) );
}

add_action( 'wp_enqueue_scripts', 'bd_songpage_scripts' );

// This sets up the icons in a convenient place!

define ( 'CART_ICON', 'ic_shopping_cart_black_25dp.png' );
define ( 'INFO_ICON', 'ic_info_outline_black_25dp.png' );
define ( 'BUY_CD_ICON', 'BUTTON-BuyCD-80x27.png');
define ( 'BUY_MP3_ICON', 'BUTTON-BuyAllMP3-129x27.png');
define ( 'ICON_PATH', plugins_url( 'images/', __FILE__ ));

/*
$cart_icon = 'ic_shopping_cart_black_25dp.png';
$mag_icon = 'ic_info_outline_black_25dp.png' ;
$buy_CD_icon = 'BUTTON-BuyCD-80x27.png';
$buy_MP3_icon = 'BUTTON-BuyAllMP3-129x27.png';
$icon_path = plugins_url( 'images/', __FILE__ );
*/
/************************************************************************/

function bdAlbumShortcode( $slug, $enclosure = null ) {

/************************************************************************/
  //global $icon_path, $buy_CD_icon, $buy_MP3_icon, $mag_icon;
/************************************************************************/

  $albumslug = $slug[0]; // because $slug is an array not an object
  $albuminfo = get_term_by( 'slug', $albumslug, 'product_cat' ); 
 
// These extract the IDs of the products in question & construct buy-now links for them.
  $getCD = get_page_by_title( $albuminfo->name . " (physical CD)", OBJECT, 'product' );

/************************************************************************/
  $CDbuylink = '<a href="' . get_site_url() . '?add-to-cart=' . $getCD->ID . '"> <img src="' . ICON_PATH . BUY_CD_ICON . '" /></a>';
/************************************************************************/

  $getMP3 = get_page_by_title( $albuminfo->name . " (Full Album Download)", OBJECT, 'product' );

/************************************************************************/
  $MP3buylink = '<a href="' . get_site_url() . '?add-to-cart=' . $getMP3->ID . '"> <img src="' . ICON_PATH . BUY_MP3_ICON . '" /></a>';
/************************************************************************/

// This sets up the thumbnail. I can change the size of it as desired.
  $thumbnail_id = get_woocommerce_term_meta( $albuminfo->term_id, 'thumbnail_id', true );
  $image = wp_get_attachment_url( $thumbnail_id );
  $album_img = '<img src="' . $image . '" width="140px" height="140px" />';

// These are the various bits of the return string, chopped into variables.
  $album_opening = '<div class="hd-album-container"><div class="hd-album">'; 
  $album_thumbnail = '<div class="hd-album-thumbnail">';
  $album_title = '</div><div class="hd-album-title">';
  $album_description = '</div><div class="hd-album-text">';
  $album_buy_alls = '</div><div class="hd-album-buy-alls"><div class="hd-album-buy-all-mp3s">';
  $album_buy_cd = '</div><div class="hd-album-buy-CD">';
  $album_more_info = '</div><div class="hd-album-moreinfo"><a href="' . get_site_url() . '/album_wiki/#' . $albuminfo->slug . '"><img src="' . plugins_url( 'images/', __FILE__ ) . '/ic_info_outline_black_25dp.png' . '" /></a>';
  $album_end = '</div></div></div><ul class="songlist">';
  $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';

// This does the real work of returning the shortcode.
  return plugins_url( 'images/', __FILE__ );

//return 
$album_opening 
. $album_thumbnail
. $album_img 
. $album_title 
. $albuminfo->name 
. $album_description 
. $lorem  

//. $albuminfo->description 
. $album_buy_alls 
. $album_more_info
. $MP3buylink 
. $album_buy_cd 
. $CDbuylink
. $album_end 
. do_shortcode ( $enclosure ) 
. "</ul></div>";
 
}

add_shortcode( 'Album', 'bdAlbumShortcode' );


function bdSongShortcode( $songID ) {

/************************************************************************/
  //global $icon_path, $cart_icon, $mag_icon;
/************************************************************************/

  // Again, extracting the ID from the array returned by $songID
  $song = $songID[0];
  // This gets all the product info from WooCommerce. And it's amazeballs!
  $product = new WC_product( $song ); 

  // These are the various bits of the return string, chopped into variables.
  $mp3j_info = '<li class="song"><div class="hd-album-individual-song"><div class="hd-album-song-mp3j">'; 
  $song_title = '</div><div class="hd-album-song-title">';  
  $price = '</div><div class="hd-album-song-price"> $';
  $buy_now = '</div><div class="hd-album-song-buynow">';
  $more_info = '</div><div class="hd-album-song-moreinfo"><a href="';

/************************************************************************/
  $buylink = '<a href="' . get_site_url() . '?add-to-cart=' . $product->id . '"><img src="' . ICON_PATH . CART_ICON . '" /></a>';
  $end_string = '<img src="' . ICON_PATH . INFO_ICON . '" /></a></div></div></li>';  
/************************************************************************/

// This does the real work of returning the shortcode.

return
$mp3j_info
. do_shortcode('[mp3j track="' . get_post_meta( $song, "mp3ee", true ) . '" title=""  ]' )
. $song_title
. get_the_title( $song )
. $price
. $product->regular_price
. $buy_now   
. $buylink
. $more_info . get_site_url() . "/song_wiki/#" . get_the_title( $song ) . '">'
. $end_string;

}

add_shortcode( 'Song', 'bdSongShortcode' );


?>
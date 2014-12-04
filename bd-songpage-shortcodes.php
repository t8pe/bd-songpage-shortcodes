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
	wp_enqueue_script( 'bd-hidey', plugins_url( 'bd-songpage-shortcodes/js/hidey.js' ) );
}

add_action( 'wp_enqueue_scripts', 'bd_songpage_scripts' );

// This sets up the icons in a convenient place!

define ( 'CART_ICON', 'ic_shopping_cart_black_25dp.png' );
define ( 'INFO_ICON', 'ic_info_outline_black_25dp.png' );
define ( 'BUY_CD_ICON', 'BUTTON-BuyCD-80x27.png');
define ( 'BUY_MP3_ICON', 'BUTTON-BuyAllMP3-129x27.png');
define ( 'ICON_PATH', plugins_url( 'images/', __FILE__ ));


function bdAlbumShortcode( $slug, $enclosure = null ) {

  $albumslug = $slug[0]; // because $slug is an array not an object
  $albuminfo = get_term_by( 'slug', $albumslug, 'product_cat' ); 
 
// These extract the IDs of the products in question & construct buy-now links for them.
  $getCD = get_page_by_title( $albuminfo->name . " (physical CD)", OBJECT, 'product' );
  $CDbuylink = '<a href="' . get_site_url() . '?add-to-cart=' . $getCD->ID . '"><button>Buy CD - $20</button></a>';

  $getMP3 = get_page_by_title( $albuminfo->name . " (Full Album Download)", OBJECT, 'product' );
  $MP3buylink = '<a href="' . get_site_url() . '?add-to-cart=' . $getMP3->ID . '" ><button>Buy All MP3s - $10</button></a>';

// This sets up the thumbnail. I can change the size of it as desired.
  $thumbnail_id = get_woocommerce_term_meta( $albuminfo->term_id, 'thumbnail_id', true );
  $image = wp_get_attachment_url( $thumbnail_id );
  $album_img = '<img src="' . $image . '" width="140px" height="140px" />';

// These are the various bits of the return string, chopped into variables.
  $album_opening = '<div class="hd-album-container"><div class="hd-album">'; 
  $album_thumbnail = '<div class="hd-album-thumbnail">';
  $album_title = '</div><div class="hd-album-title"><a href="javascript:hideshow(document.getElementById(\'hidey-' . $albuminfo->name . '\'))">' . $albuminfo->name . '</a>';
  $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';  
$album_description = '<div id="hidey-' . $albuminfo->name . '" style="display: none">' . $lorem  . $lorem . $lorem . '</div></div><div class="hd-album-text">'; 
  $album_buy_alls = '</div><div class="hd-album-buy-alls"><div class="hd-album-buy-all-mp3s">';
  $album_buy_cd = '</div><div class="hd-album-buy-CD">';
  $album_more_info = '</div><div class="hd-album-moreinfo"><a href="' . get_site_url() . '/album_wiki/#' . $albuminfo->slug . '"><img src="' . ICON_PATH . INFO_ICON . '" /></a>';
  $album_end = '</div></div></div><ul class="songlist">';


// This does the real work of returning the shortcode.
return 
$album_opening 
. $album_thumbnail
. $album_img 
. $album_title 
. $album_description 
  //. $lorem   
. $album_buy_alls 
. $MP3buylink 
. $album_buy_cd 
. $CDbuylink
. $album_end 
. do_shortcode ( $enclosure ) 
. "</ul></div>";
 
}

add_shortcode( 'Album', 'bdAlbumShortcode' );


function bdSongShortcode( $songID ) {

  // Again, extracting the ID from the array returned by $songID
  $song = $songID[0];
  $product = new WC_product( $song ); 

  // These are the various bits of the return string, chopped into variables.
  $mp3j_info = '<li class="song"><div class="hd-album-individual-song"><div class="hd-album-song-mp3j">'; 
  $song_title = '</div><div class="hd-album-song-title">';  
  $price = '</div><div class="hd-album-song-price"> $';
  $buy_now = '</div><div class="hd-album-song-buynow">';
  $more_info = '</div><div class="hd-album-song-moreinfo"><a href="';
  $buylink = '<a href="' . get_site_url() . '?add-to-cart=' . $product->id . '"><img src="' . ICON_PATH . CART_ICON . '" /></a>';
  $end_string = '<img src="' . ICON_PATH . INFO_ICON . '" /></a></div></div></li>';  
 
  if ( get_post_meta( $product->id, 'lyrics', true ) ) {
    $lyrics = '  <a href="javascript:hideshow(document.getElementById(\'hidey-' . $product->id . '\'))">(lyrics)</a><div class="hd-album-song-lyrics" style="display:none" id="hidey-' . $product->id . '">' . get_post_meta( $product->id, 'lyrics', true ) . '</div>';
    } else {
      $lyrics = '';
    }
   
// This does the real work of returning the shortcode.

return
$mp3j_info
. do_shortcode('[mp3j track="' . get_post_meta( $song, "mp3ee", true ) . '" title=""  ]' )
. $song_title 
. get_the_title( $song )  
. $lyrics
. $price
. $product->regular_price
. $buy_now   
. $buylink
. '</div></div></li>';
}

add_shortcode( 'Song', 'bdSongShortcode' );


?>
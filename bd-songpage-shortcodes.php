<?php
/*
Plugin Name: Ben's Song-Page Shortcodes
Plugin URI: http://bendeschamps.com
Description: Plugin to enable Album & Song shortcodes in WordPress without Woo stupidity
Version: 0.1
Author: Ben Deschamps
Author URI: http://bendeschamps.com
License: GPLv2
*/

function bd_songpage_scripts() {
	wp_enqueue_style( 'bd-songpage-css', plugins_url( 'bd-songpage-shortcodes/css/style.css' ) );
}

add_action( 'wp_enqueue_scripts', 'bd_songpage_scripts' );



function bdAlbumShortcode( $slug, $enclosure = null ) {

  $albumslug = $slug[0]; // because $slug is an array not an object
$albuminfo = get_term_by( 'slug', $albumslug, 'product_cat' ); 
 
// These extract the IDs of the products in question & construct buy-now links for them.
$getCD = get_page_by_title( $albuminfo->name . " (physical CD)", OBJECT, 'product' );
$CDbuylink = '<a href="' . get_site_url() . '?add-to-cart=' . $getCD->ID . '">Buy CD</a>';

$getMP3 = get_page_by_title( $albuminfo->name . " (Full Album Download)", OBJECT, 'product' );
$MP3buylink = '<a href="' . get_site_url() . '?add-to-cart=' . $getMP3->ID . '">Buy All MP3s</a>';

// This sets up the thumbnail. I can change the size of it as desired.
$thumbnail_id = get_woocommerce_term_meta( $albuminfo->term_id, 'thumbnail_id', true );
$image = wp_get_attachment_url( $thumbnail_id );
$album_img = '<img src="' . $image . '" width="100px" height="100px" />';

// These are the various bits of the return string, chopped into variables. I can do this more elegantly later I think.
$album_opening = '<div class="hd-album-container woocommerce woocommerce-page"><div class="hd-album">'; 
$album_thumbnail = '<div class="hd-album-thumbnail">';
$album_title = '</div><div class="hd-album-title">';
$album_description = '</div><div class="hd-album-text">';
$album_buy_alls = '</div><div class="hd-album-buy-alls"><div class="hd-album-buy-all-mp3s">';
$album_buy_cd = '</div><div class="hd-album-buy-CD">';
$album_end = '</div></div></div>';

// This does the real work of returning the shortcode.
return 
$album_opening 
. $album_thumbnail
. $album_img 
. $album_title 
. $albuminfo->name 
. $album_description 
. $albuminfo->description 
. $album_buy_alls 
. $MP3buylink 
. $album_buy_cd 
. $CDbuylink
. $album_end 
. do_shortcode ( $enclosure ) 
. "</div>";
}

add_shortcode( 'Album', 'bdAlbumShortcode' );


function bdSongShortcode( $songID ) {
  // Again, extracting the ID from the array returned by $songID
  $song = $songID[0];

  // This gets all the product info from WooCommerce. And it's amazeballs!
  $product = new WC_product( $song ); 
  // These are the various bits of the return string, chopped into variables.
  $mp3j_info = '<div class="hd-album-individual-song"><div class="hd-album-song-mp3j">'; 
  $song_title = '</div><div class="hd-album-song-title">';  
  $price = '</div><div class="hd-album-song-price"> $';
  $buy_now = '</div><div class="hd-album-song-buynow">';
  $more_info = '</div><div class="hd-album-song-moreinfo"><a href="';
  $end_string = 'More Info </a></div></div>';  // Was missing a </div> here - which cascaded on HD.com
  $buylink = '<a href="' . get_site_url() . '?add-to-cart=' . $product->id . '">Buy Me</a>';
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
. $more_info . get_site_url() . "/song_wiki/" . get_the_title( $song ) . '">'
. $end_string;
}

add_shortcode( 'Song', 'bdSongShortcode' );

?>
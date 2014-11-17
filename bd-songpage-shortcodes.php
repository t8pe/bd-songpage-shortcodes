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
$album_opening = '<div class="hd-album">';
$album_thumbnail = '<div class="hd-album-thumbnail">';
$album_title = '</div><div class="hd-album-title"></div>';
$album_description = '<div class="hd-album-text">';
$album_buy_alls = '</div><div class="hd-buy-alls"><div class="hd-buy-all-mp3s">';
$album_buy_cd = '</div><div class="hd-buy-CD">';
$album_end = '</div></div>';

// This does the real work of returning the shortcode.
return $album_opening . $album_thumbnail . $album_img . $album_title . $albuminfo->name . $album_description . $albuminfo->description . $album_buy_alls . $MP3buylink . $album_buy_cd . $CDbuylink . $album_end . do_shortcode ( $enclosure ) . "</div>";
}

add_shortcode( 'Album', 'bdAlbumShortcode' );


function bdSongShortcode( $songID ) {
  // Again, extracting the ID from the array returned by $songID
  $song = $songID[0];

  // This gets all the product info from WooCommerce. And it's amazeballs!
  $product = new WC_product( $song ); 

// These are the various bits of the return string, chopped into variables. I can do this more elegantly later I think.
  $mp3j_info = '<div class="hd-album-individual-song"><div class="album-song-mp3j">';
  $song_title = '</div><div class="album-song-title">';
  $price = '</div><div class="album-song-price"> $';
  $buy_now = '</div><div class="album-song-buynow">';
  $more_info = '>Buy Now Link</a></div><div class="album-song-moreinfo"><a href="';
  $end_string = 'More Info</a></div></div>';
  
  // Still having trouble with the MP3Jplayer appearing at the top of the page for whatever fucking reason.

// This does the real work of returning the shortcode.
  return $mp3j_info . mp3j_put( '[mp3j track="' . get_post_meta( $song, "mp3ee", true ) . '"]' ) . $song_title . get_the_title( $song ) . $price . $product->regular_price . $buy_now . '<a href=' . get_site_url() . '?add-to-cart=' .  $song . $more_info . get_site_url() . "/song_wiki/" . get_the_title( $song ) . '">' . $end_string;
}

add_shortcode( 'Song', 'bdSongShortcode' );
?>
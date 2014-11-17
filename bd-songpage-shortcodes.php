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

$albumslug = $slug[0];

$albuminfo = get_term_by( 'slug', $albumslug, 'product_cat' ); 
//$term_id = $title->term_id; // THAT RIGHT THERE. That's the ID for the term.
//$album_info = get_term_by( 'term_taxonomy_id', $term_id, 'product_cat' );
//echo $tittles->name; // What I want is the thumbnail though.
//echo $tittles->description;

$thumbnail_id = get_woocommerce_term_meta( $albuminfo->term_id, 'thumbnail_id', true );
$image = wp_get_attachment_url( $thumbnail_id );
$album_img = '<img src="' . $image . '" width="200px" height="200px" />';

$album_opening = '<div class="hd-album">';
$album_thumbnail = '<div class="hd-album-thumbnail">';
$album_title = '</div><div class="hd-album-title"></div>';
$album_description = '<div class="hd-album-text">';
$album_buy_alls = '</div><div class="hd-buy-alls">';
$album_buy_alls_2 = '<div class="hd-buy-all-mp3s">BUY ALL MP3s';
$album_buy_cd = '</div><div class="hd-buy-CD">BUY CD';
$album_end = '</div></div>';

return $album_opening . $album_thumbnail . $album_img . $album_title . $albuminfo->name . $album_description . $albuminfo->description . $album_buy_alls . $album_buy_alls_2 . $album_buy_cd . $album_end . do_shortcode ( $enclosure ) . "</div>";
}

add_shortcode( 'Album', 'bdAlbumShortcode' );

function bdSongShortcode( $songID ) {
  $song = $songID[0];
  $product = new WC_product( $song ); // THIS! This is amazeballs!
  $mp3j_info = '<div class="hd-album-individual-song"><div class="album-song-mp3j">';
  $song_title = '</div><div class="album-song-title">';
  $price = '</div><div class="album-song-price"> $';
  //echo get_the_price( $songID );
  $buy_now = '</div><div class="album-song-buynow">';
  $more_info = '>Buy Now Link</a></div><div class="album-song-moreinfo"><a href="';
  //echo "http://heatherdale.com/song_wiki" . get_the_title( $songID );
  $end_string = 'More Info</a></div></div>';
  
  // having trouble with the MP3Jplayer appearing at the top of the page for whatever fucking reason.

  return $mp3j_info . mp3j_put( '[mp3j track="' . get_post_meta( $song, "mp3ee", true ) . '"]' ) . $song_title . get_the_title( $song ) . $price . $product->regular_price . $buy_now . '<a href=' . get_site_url() . '?add-to-cart=' .  $song . $more_info . get_site_url() . "/song_wiki/" . get_the_title( $song ) . '">' . $end_string;
}

add_shortcode( 'Song', 'bdSongShortcode' );
?>
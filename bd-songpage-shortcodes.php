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
  // This structure allows me to insert the PHP bits without my brains oozing out my ears.
  // Now to figure out how the smeg to get the stuff from the fucking Taxonomy.
  // Changed from ID to slug b/c that should be easier with the taxonomy.
$albumslug = $slug[0];

$title = get_term_by( 'slug', $albumslug, 'product_cat' ); 
$term_id = $title->term_id; // THAT RIGHT THERE. That's the ID for the term.
$tittles = get_term_by( 'term_taxonomy_id', $term_id, 'product_cat' );
echo $tittles->name; // What I want is the thumbnail though.
echo $tittles->description;

$thumbnail_id = get_woocommerce_term_meta( $term_id, 'thumbnail_id', true );
$image = wp_get_attachment_url( $thumbnail_id );
echo '<img src="' . $image . '" width="200px" height="200px" />';

// echo var_dump( $tittles );
$value .= '<div class="hd-album">';
$value .= '<div class="hd-album-thumbnail">THUMBNAIL';
$value .= '</div><div class="hd-album-title">TITLE</div>';
$value .= '<div class="hd-album-text">TEXT';
$value .= '</div><div class="hd-buy-alls">';
$value .= '<div class="hd-buy-all-mp3s">BUY ALL MP3s';
$value .= '</div><div class="hd-buy-CD">BUY CD';
$value .= '</div></div>';

return $value . do_shortcode ( $enclosure ) . "</div>";
}

add_shortcode( 'Album', 'bdAlbumShortcode' );

function bdSongShortcode( $songID ) {
  $song = $songID[0];
  $mp3j_link = '<div class="hd-album-individual-song"><div class="album-song-mp3j">MP3J LINK';
  $song_title = '</div><div class="album-song-title">';
  $price = '</div><div class="album-song-price">PRICE';
  //echo get_the_price( $songID );
  $buy_now = '</div><div class="album-song-buynow">ADD TO CART';
  $more_info = '</div><div class="album-song-moreinfo"><a href="';
  //echo "http://heatherdale.com/song_wiki" . get_the_title( $songID );
  $end_string = 'More Info</a></div></div>';

  return $mp3j_link . $song_title . get_the_title( $song ) . $price . $buy_now . $more_info . "http://heatherdale.com/song_wiki/" . get_the_title( $song ) . '">' . $end_string;
}

add_shortcode( 'Song', 'bdSongShortcode' );
?>
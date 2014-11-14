<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 22/10/14
 * Time: 1:04 PM
 */

$post_link       = urlencode( get_permalink() );
$post_thumb_url  = has_post_thumbnail() ? urlencode( wp_get_attachment_url( get_post_thumbnail_id() ) ) : '';
$post_title      = urlencode( get_the_title() );
$post_summary    = urlencode( get_the_excerpt() );
$twitter_account = urlencode( preg_replace( "/https?\:\/\/twitter\.com\//", "", get_option( 'twitter-url' ) ) );
$tweet_text      = sprintf( __( 'Check out %1$s on %2$s', 'zingdesign' ), $post_title, get_bloginfo( 'name' ) );

$share_facebook = 'http://www.facebook.com/sharer/sharer.php?s=100&amp;p[url]=' . $post_link;
if ( has_post_thumbnail() ) {
	$share_facebook .= '&amp;p[images][0]=' . $post_thumb_url;
}
$share_facebook .= '&amp;p[title]=' . $post_title;
$share_facebook .= '&amp;p[summary]=' . $post_summary;

$share_twitter = 'https://twitter.com/intent/tweet?source=' . $post_link . '&text=' . $tweet_text . '&via=' . $twitter_account;
$share_google  = 'https://plus.google.com/share?url=' . $post_link;
?>

<div class="post-share">
	<h4><?php printf( __( 'Share this %s', 'zingdesign' ), str_replace("_", " ", get_post_type() ) ); ?></h4>

	<div class="row">
		<div class="medium-4 columns">
			<a href="<?php echo esc_url( $share_facebook ); ?>" class="button facebook" target="_blank"
			   title="Share on Facebook"><i class="fa fa-facebook fa-right-margin"></i> Facebook</a>
		</div>
		<div class="medium-4 columns">
			<a href="<?php echo esc_url( $share_twitter ); ?>" class="button twitter" target="_blank"
			   title="Tweet about this post"><i class="fa fa-twitter fa-right-margin"></i> Twitter</a>
		</div>
		<div class="medium-4 columns">
			<a href="<?php echo esc_url( $share_google ); ?>" class="button google-plus" target="_blank"
			   title="Share on Google+"><i class="fa fa-google-plus fa-right-margin"></i> Google+</a>
		</div>
	</div>

</div>
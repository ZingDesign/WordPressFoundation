<?php
global $is_large;
//global $resource_index, $element_index;
//$display_large = array( 0,3,7,10,13 );
//$clear_elements = array( 2,4,9,12,15 );
//$clear = in_array(++$resource_index, array_map('increment_value', $display_large)) ? ' clear' : '';
$class = 'resources-card columns';
$thumb_size = 'resource-thumb';

//if( zd_metabox::zd_get_custom_meta(get_the_ID(), 'is_large_post') ) {
//if( is_large_resource($resource_index) ) {

//_d( $resource_index );
//$is_large = (6 * $resource_index + 1 === $element_index);
//_d( $is_large );

//_d($element_index);

if( $is_large ) {
	$class .= ' large large-8';
	$thumb_size = 'resource-large';
}
else {
	$class .= ' medium-6 large-4';
}
?>
<div class="<?php echo $class; ?>">
	<div class="resource-card-inner">

		<div class="card-image resources-card-image"><?php zd_the_post_thumbnail( $thumb_size ); ?></div>

		<div class="resources-card-content">
			<div class="resources-card-meta entry-meta">
				<?php get_category_icons( get_the_ID() ); ?>

			</div>
			<h3><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>

			<div class="entry-content excerpt-wrapper">
				<?php the_excerpt();?>
			</div>

			<?php edit_post_link( __( 'Edit', 'zingdesign' ), '<span class="edit-link">', '</span>' );?>
		</div>

	</div><!-- .resource-card-inner-->

</div><!--.resources-card-->
<?php
/**
 * The Sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */
?>
<div id="secondary">


	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
        <?php
        //list_services();
        if(!is_page(21) ) {
            list_services();
        };?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div><!-- #primary-sidebar -->
	<?php endif; ?>
</div><!-- #secondary -->

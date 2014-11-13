<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 23/10/14
 * Time: 11:15 AM
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
		<input type="search" class="search-field input-search" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>"
		       value="<?php echo get_search_query() ?>" name="s"
		       title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" required />
	</label>
	<button type="submit" class="button-search search-submit"><span class="screen-reader-text"><?php echo esc_attr_x( 'Search', 'submit button' ) ?></span></button>
</form>
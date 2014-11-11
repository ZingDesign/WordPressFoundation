<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */
?>
		</div><!-- #main-wrapper -->

		<!-- footer starts here -->
		<footer id="colophon" class="footer">

			<div class="footer-content">

				<?php if(has_nav_menu('footer')) :

				wp_nav_menu(array(
					'menu_location'     => 'footer',
					'container'         => false,
					'menu_class'        => 'footer-links'
				));

				else : ?>
				<ul class="footer-links">
					<li><a href="https://raygun.io/about" target="_blank">About</a></li>
					<li><a href="<?php get_permalink( get_page_by_title('resources') ); ?>">Resources</a></li>
					<li><a href="https://raygun.io/faq" target="_blank">Support</a></li>
					<li><a href="https://raygun.io/forums" target="_blank">Community</a></li>
					<li><a href="https://raygun.io/about/contact" target="_blank">Contact</a></li>
					<li><a href="<?php echo site_url('/'); ?>">Blog</a></li>
				</ul>

				<?php endif; ?>

				<div class="footer-icons footer-right">
					<a class="icon-footer colour-github" href="<?php echo get_option('github-url'); ?>" target="_blank"><i class="fa fa-github fa-fw"></i></a>
					<a class="icon-footer colour-facebook" href="<?php echo get_option('facebook-url'); ?>" target="_blank"><i class="fa fa-facebook fa-fw"></i></a>
					<a class="icon-footer colour-twitter" href="<?php echo get_option('twitter-url'); ?>" target="_blank"><i class="fa fa-twitter fa-fw"></i></a>
					<a class="icon-footer colour-google-plus" href="<?php echo get_option('google-plus-url'); ?>" target="_blank"><i class="fa fa-google-plus fa-fw"></i></a>
				</div>

			</div>

			<div class="footer-content">

				<div class="footer-credit">
					<a href="http://www.mindscapehq.com/" target="_blank"><?php _e('Made by', 'zingdesign'); ?> Mindscape &copy; <?php echo date('Y'); ?></a>
				</div>

				<div class="link-terms footer-right">
					<a href="https://raygun.io/privacy" class="link blue" target="_blank"><?php _e('Privacy policy', 'zingdesign'); ?></a>
					<a href="https://raygun.io/terms" class="link blue" target="_blank"><?php _e('Terms and conditions', 'zingdesign'); ?></a>
				</div>

			</div>

		</footer>
		<!-- footer ends here -->
	</div><!-- #page -->

	<?php wp_footer(); ?>

<!--    <script>jQuery(document).foundation();</script>-->

</body>
</html>
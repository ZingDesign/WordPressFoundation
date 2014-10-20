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
<!--            </div><!-- .small-12 columns -->
		</div><!-- #main -->

		<footer id="colophon" class="site-footer" role="contentinfo">

			<a class="footer-logo"><?php bloginfo('name'); ?></a>

            <p>Powered by WordPress. Designed by <a href="http://zingdesign.com" target="_blank">Zing Design</a></p>

		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>

    <script>jQuery(document).foundation();</script>

</body>
</html>
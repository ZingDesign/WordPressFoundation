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

            <p>&copy; <?php echo date('Y'); ?> Zing Design</p>

		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>

    <script>jQuery(document).foundation();</script>

</body>
</html>
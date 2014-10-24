<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 23/10/14
 * Time: 4:27 PM
 */

/**
 * Featured_Posts widget class
 *
 * @since 2.8.0
 */
class ZD_Widget_Featured_Posts extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'sidebar-features', 'description' => __( "Your site&#8217;s featured Posts.") );
		parent::__construct('featured-posts', __('Featured Posts'), $widget_ops);
		$this->alt_option_name = 'widget_featured_entries';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );
	}

	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'widget_featured_posts', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Featured Posts' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		/**
		 * Filter the arguments for the Featured Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the featured posts.
		 */

		// Count posts with "Featured" category

		$featured_cat_id = get_cat_ID('Featured');

		$featured_posts = get_posts(array('category' => $featured_cat_id));

		$post_args = array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'post_type'           => array('post', 'resource')
		);

		// Check if there are any posts with the Featured category before adding it as an arg
		// By default, just show the most recent
		if( !empty($featured_posts) )
			$post_args['cat'] = $featured_cat_id;

		$r = new WP_Query( apply_filters( 'widget_posts_args', $post_args) );



		if ($r->have_posts()) :
			?>
			<?php echo $args['before_widget']; ?>
			<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
			<div>
				<?php while ( $r->have_posts() ) : $r->the_post(); ?>
					<div class="sidebar-features-item">
						<h4>
							<a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
						</h4>

						<?php if ( $show_date ) : ?>
							<span class="post-date"><?php echo get_the_date(); ?></span>
						<?php endif; ?>

						<?php
						get_category_icons($r->post->ID, "Featured");?>

						<i class="fa fa-user"></i><?php echo get_the_author();  ?>
					</div>
				<?php //_d($r->post);
				endwhile; ?>
			</div>
			<?php echo $args['after_widget']; ?>
			<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'widget_featured_posts', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_featured_entries']) )
			delete_option('widget_featured_entries');

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete('widget_featured_posts', 'widget');
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
	<?php
	}
}


/**
 * Newsletter_Subscribe widget class
 *
 * @since 2.8.0
 */
class ZD_Widget_Newsletter_Subscribe extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'sidebar-newsletter', 'description' => __( "A newsletter subscription form for Mailchimp") );
		parent::__construct('newsletter-subscribe', __('Newsletter Subscribe'), $widget_ops);
		$this->alt_option_name = 'widget_newsletter_subscribe';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );
	}

	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'widget_newsletter_subscribe', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Newsletter Subscribe' );
		$text = ( ! empty( $instance['text'] ) ) ? $instance['text'] : '';
		$mailchimp_url = ( ! empty( $instance['mailchimp_url'] ) ) ? $instance['mailchimp_url'] : '';

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$text = apply_filters( 'widget_text', $text, $instance, $this->id_base );
		$mailchimp_url = apply_filters( 'esc_url', $mailchimp_url, $instance, $this->id_base );

		/**
		 * Filter the arguments for the Newsletter Subscribe widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the featured posts.
		 */
		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if( $text ) {
			echo wpautop($text);
		}


		if($mailchimp_url) :
		?>
		<!-- Begin MailChimp Signup Form -->
		<div id="mc_embed_signup">
			<form action="<?php echo $mailchimp_url; ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div id="mc_embed_signup_scroll">
<!--					<h2>Subscribe to our mailing list</h2>-->
<!--					<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>-->
<!--					<div class="mc-field-group">-->
<!--						<label for="mce-FNAME">First Name  <span class="asterisk">*</span>-->
<!--						</label>-->
<!--						<input type="text" value="" name="FNAME" class="required" id="mce-FNAME">-->
<!--					</div>-->
					<div class="mc-field-group">
						<label for="mce-EMAIL" class="screen-reader-text">Email Address</label>
						<input type="email" value="" name="EMAIL" class="required email input-email full-width" id="mce-EMAIL" placeholder="<?php _e('Your email address', 'zingdesign'); ?>">
					</div>
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
					<div style="position: absolute; left: -5000px;"><input type="text" name="b_9f1df0038e4038c9935374bf4_f6a9b177f5" tabindex="-1" value=""></div>
					<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button green full-width"></div>
				</div>
			</form>
		</div>

		<!--End mc_embed_signup-->
		<?php
		else:
			_e('Please enter your MailChimp signup form URL to activate the newsletter subscription form. This can be found in MailChimp, under Lists &gt; Signup forms.', 'zingdesign');
		endif;

		echo $args['after_widget'];

			// Count posts with "Featured" category



		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'widget_newsletter_subscribe', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = strip_tags($new_instance['text']);
		$instance['mailchimp_url'] = strip_tags($new_instance['mailchimp_url']);

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_newsletter_subscribe']) )
			delete_option('widget_newsletter_subscribe');

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete('widget_newsletter_subscribe', 'widget');
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$text = isset( $instance['text'] ) ? esc_attr( $instance['text'] ) : '';
		$mailchimp_url = isset( $instance['mailchimp_url'] ) ? esc_attr( $instance['mailchimp_url'] ) : '';

		$form_helper = new FormHelper();

		$html = '';

		$html .= $form_helper->zd_setting_input(array(
			'label'     => __('Title', 'zingdesign'),
			'id'        => $this->get_field_id('title'),
			'name'      => $this->get_field_name('title'),
			'value'     => $title,
			'class'     => 'widefat'
		));

		$html .= $form_helper->zd_setting_input(array(
			'label'     => __('Text', 'zingdesign'),
			'id'        => $this->get_field_id('text'),
			'name'      => $this->get_field_name('text'),
			'value'     => $text,
			'type'      => 'textarea',
			'default'   => __('Sign up to our newsletter', 'zingdesign'),
			'class'     => 'widefat',
			'rows'      => '5'
		));

		$html .= $form_helper->zd_setting_input(array(
			'label'     => __('Mailchimp Form URL', 'zingdesign'),
			'id'        => $this->get_field_id('mailchimp_url'),
			'name'      => $this->get_field_name('mailchimp_url'),
			'value'     => $mailchimp_url,
			'class'     => 'widefat',
			'help'      => __('Find this in MailChimp, under Lists &gt; Signup forms', 'zingdesign')
		));

		echo $html;
		?>
	<?php
	}
}
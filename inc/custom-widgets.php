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

						<div class="sidebar-features-meta">
							<?php if ( $show_date ) : ?>
								<span class="post-date"><?php echo get_the_date(); ?></span>
							<?php endif; ?>

							<?php
//							get_category_icons($r->post->ID, "Featured");?>

<!--							<span class="author vcard">-->
								<?php //echo get_the_author();  ?>
<!--							</span>-->
						</div>

					</div>
				<?php //_d($r->post);
				endwhile; ?>
			</div>
			<?php echo $args['after_widget']; ?>
			<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
			wp_reset_query();

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
		$mailchimp_url = ( ! empty( $instance['mailchimp_url'] ) ) ? $instance['mailchimp_url'] : false;

		if( $mailchimp_url === false && get_option('mail-chimp-endpoint-url') ) {
			$mailchimp_url = get_option('mail-chimp-endpoint-url');
		}

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
			$unique_id = $this->get_unique_mailchimp_id($mailchimp_url);
		?>
		<!-- Begin MailChimp Signup Form -->

			<div id="mc_embed_signup">
				<form action="<?php echo $mailchimp_url; ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<div id="mc_embed_signup_scroll">
						<div class="mc-field-group">
							<label for="mce-EMAIL" class="screen-reader-text">Subscribe to our mailing list</label>
							<input type="email" value="" name="EMAIL" class="required email input-email full-width" id="mce-EMAIL" placeholder="<?php _e('Your email address', 'zingdesign'); ?>" required>
						</div>
						<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
						<div style="position: absolute; left: -5000px;"><input type="text" name="<?php echo $unique_id; ?>" tabindex="-1" value=""></div>
						<div class="clear">
							<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button green full-width"></div>
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

//		$html .= $form_helper->zd_setting_input(array(
//			'label'     => __('Mailchimp Unique ID', 'zingdesign'),
//			'id'        => $this->get_field_id('mailchimp_unique_id'),
//			'name'      => $this->get_field_name('mailchimp_unique_id'),
//			'value'     => $mailchimp_unique_id,
//			'class'     => 'widefat',
//			'help'      => __('Find this in MailChimp, under Lists &gt; Signup forms', 'zingdesign')
//		));

		echo $html;
		?>
	<?php
	}

	private function get_unique_mailchimp_id($url) {

		$query_str = parse_url($url, PHP_URL_QUERY);

		parse_str($query_str, $output);

		unset($query_str);


		if( isset( $output['u'] ) ) {
			return 'b_' . $output['u'] . '_' . $output['id'];
		}

		return false;
	}
}

/**
 * Sticky Content widget class
 *
 * @since 2.8.0
 */
class ZD_Widget_Sticky_Content extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'zd-sticky-content', 'description' => __('Content to stay fixed in place when the user scrolls down the page'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('zd_sticky_content', __('Sticky Content'), $widget_ops, $control_ops);
	}

	public function widget( $args, $instance ) {

		$before_image = '';
		$after_image = '';

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );


		/**
		 * Filter the content of the Text widget.
		 *
		 * @since 2.3.0
		 *
		 * @param string    $widget_text The widget content.
		 * @param WP_Widget $instance    WP_Widget instance.
		 */
		$image_id = apply_filters(
			'widget_text',
			empty( $instance['sticky_content_image']) ? '' : $instance['sticky_content_image'],
			$instance
		);

		$image_link = apply_filters(
			'widget_text',
			empty( $instance['image_link']) ? '' : $instance['image_link'],
			$instance
		);

		$link_target = apply_filters(
			'widget_text',
			empty( $instance['link_target']) ? '_self' : $instance['link_target'],
			$instance
		);

		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if( ! empty($image_link ) ) {
			$before_image = '<a href="'.esc_url($image_link).'" target="'.$link_target.'">'."\n";
			$after_image = '</a>'."\n";
		}


		if( ! empty($image_id) ) {
			$image_full_data = wp_get_attachment_image_src( $image_id, 'full' );
			$image_full_src = $image_full_data[0];
			$image_full_width = $image_full_data[1];
			$image_full_height = $image_full_data[2];

			$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

			echo $before_image;

			echo "<img src=\"{$image_full_src}\" alt=\"{$image_alt}\" width=\"{$image_full_width}\" height=\"{$image_full_height}\" />\n";

			echo $after_image;
		}
		?>


		<?php if( ! empty($text) ): ?>

			<div class="textwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
		<?php
		endif;

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		$instance['sticky_content_image'] = strip_tags($new_instance['sticky_content_image']);
		$instance['image_link'] = strip_tags($new_instance['image_link']);
		$instance['link_target'] = strip_tags($new_instance['link_target']);

		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'text' => '',
			'sticky_content_image' => ''
		) );

		$title = strip_tags($instance['title']);
		$text = esc_textarea($instance['text']);
		$sticky_content_image = strip_tags($instance['sticky_content_image']);
		$image_link = strip_tags($instance['image_link']);
		$link_target = strip_tags($instance['link_target']);

		$html_allowed = ( current_user_can('unfiltered_html') ) ? '<span>' . __('HTML allowed', 'zingdesign') . '</span>' : '';

		$form_helper = new FormHelper();
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<?php echo $form_helper->zd_setting_input(array(
			'label'             => __('Image', 'zingdesign'),
			'id'                => $this->get_field_id('sticky_content_image'),
			'name'              => $this->get_field_name('sticky_content_image'),
			'value'             => $sticky_content_image,
			'type'              => 'image',
			'wrapper_class'     => 'zd-widget-input',
			'wrapper_element'   => 'p'
		));

		echo $form_helper->zd_setting_input(array(
			'label'             => __('Image link', 'zingdesign'),
			'id'                => $this->get_field_id('image_link'),
			'name'              => $this->get_field_name('image_link'),
			'value'             => $image_link,
			'type'              => 'url',
			'wrapper_class'     => 'zd-widget-input',
			'wrapper_element'   => 'p'
		));

		echo $form_helper->zd_setting_input(array(
			'label'             => __('Link target', 'zingdesign'),
			'id'                => $this->get_field_id('link_target'),
			'name'              => $this->get_field_name('link_target'),
			'value'             => $link_target,
			'type'              => 'select',
			'dropdown'          => array(
				'_self'     => __('Same window', 'zingdesign'),
				'_blank'    => __('New tab', 'zingdesign')
			),
			'wrapper_class'     => 'zd-widget-input',
			'wrapper_element'   => 'p'
		));
		?>


		<p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text content', 'zingdesign'); ?></label><?php echo $html_allowed; ?></p>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

		<p>
			<input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
	<?php
	}
}
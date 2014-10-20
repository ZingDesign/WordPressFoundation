<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 16/06/14
 * Time: 10:49 AM
 */

// debug

//add_action('wp_footer', 'debug');
//
//function debug(){
//    echo "<p>Custom Shorcodes loaded</p>";
//}

/*
 * Custom buttons
 */

remove_shortcode('btn');

function zd_custom_button_shortcode($atts) {
    $url = $page = $wrapper = $wrapper_class = $text = $url = $target = $class = $title = null;
    $_wrapper_class = $before = $after = $_target = $display = $_display = '';

    extract(shortcode_atts(array(
        'class'         => '',
        'url'           => '#',
        'text'          => '',
        'display'       => false,
        'title'         => false,
        'target'        => false,
        'page'          => false,
        'wrapper'       => false,
        'wrapper_class' => false,
        'icon'          => false
    ), $atts));

    if (is_string($page)) {
        $url = get_permalink(get_page_by_title($page));
    } else if (is_int($page)) {
        $url = get_permalink($page);
    }

    if ($wrapper) {
        $wrapper = preg_replace('/[^\da-z]/i', '', $wrapper);
        if ($wrapper_class) {
            $_wrapper_class = " class=\"{$wrapper_class}\"";
        }
        $before = "<{$wrapper}{$_wrapper_class}>\n";

        $after = "</{$wrapper}>\n";
    }

    if ($target) {
        $_target = " target=\"{$target}\"";
    }

    if (!$title) {
        $title = $text;
    }

    if ($display) {
        $_display = ' style="display:' . $display . ';"';
    }

    return "{$before}<a class=\"zd-button zd-shortcode-btn {$class}\" title=\"{$title}\" href=\"{$url}\"{$_target}{$_display}>{$text}</a>{$after}";
}

add_shortcode('btn', 'zd_custom_button_shortcode');

/*
 * Custom links
 */

remove_shortcode('link');

add_shortcode('link', 'zd_custom_link_callback');

function zd_custom_link_callback($atts) {
    $url = $page = $wrapper = $wrapper_class = $text = $url = $target = $class = $title = null;
    $_wrapper_class = $before = $after = $_target = $display = $_display = '';

    extract(shortcode_atts(array(
        'class'   => '',
        'url'     => '#',
        'text'    => '',
        'display' => false,
        'title'   => '',
        'target'  => false,
        'page'    => false
    ), $atts));

    if (is_string($page)) {
        $url = get_permalink(get_page_by_title($page));
    } else if (is_int($page)) {
        $url = get_permalink($page);
    }

    if ($target) {
        $_target = " target=\"{$target}\"";
    }

//    if (!$title) {
    //        $title = $text;
    //    }

    if ($display) {
        $_display = ' style="display:' . $display . ';"';
    }

    return "<a class=\"{$class}\" title=\"{$title}\" href=\"{$url}\"{$_target}{$_display}>{$text}</a>\n";
}

/*
 * Mailchimp subscription form
 */

remove_shortcode('mailchimp_form');

add_shortcode('mailchimp_form', 'mailchimp_form_callback');

function mailchimp_form_callback( $atts ) {

    wp_register_script( 'mailchimp-subscribe-script', get_template_directory_uri() . '/js/mailchimp-subscribe.js', array('jquery'), 1, true);

    $js = true;
    $class = $_class = $html = '';

    extract(shortcode_atts(array(
        'class' => '',
        'js'    => true
    ), $atts));

    if( $js ) {
        wp_enqueue_script( 'mailchimp-subscribe-script' );
    }

    if( $class ) {
        $_class = ' class="'.$class.'"';
    }


//    $html = '';
    $html .= "<!-- Begin MailChimp Signup Form -->\n";
    $html .= "<div id=\"mc_embed_signup\"{$_class}>\n";
    $html .= "<form action=\"http://leda.us3.list-manage.com/subscribe/post?u=9e1a186caa4966a553bf6132d&amp;id=d13f0199f8\" method=\"post\" id=\"mc-embedded-subscribe-form\" name=\"mc-embedded-subscribe-form\" class=\"validate\" target=\"_blank\" novalidate>\n";

//    $html .= "<h2>Subscribe to our mailing list</h2>\n";

    $html .= "<div class=\"indicates-required\"><span class=\"asterisk\">*</span> indicates required</div>\n";
    $html .= "<div class=\"mc-field-group\">\n";
    $html .= "<label for=\"mce-FNAME\">First Name  <span class=\"asterisk\">*</span>\n";
    $html .= "</label>\n";
    $html .= "<input type=\"text\" value=\"\" name=\"FNAME\" class=\"required\" id=\"mce-FNAME\">\n";
    $html .= "</div>\n";
    $html .= "<div class=\"mc-field-group\">\n";
    $html .= "<label for=\"mce-EMAIL\">Email Address  <span class=\"asterisk\">*</span>\n";
    $html .= "</label>\n";
    $html .= "<input type=\"email\" value=\"\" name=\"EMAIL\" class=\"required email\" id=\"mce-EMAIL\">\n";
    $html .= "</div>\n";
    $html .= "<div id=\"mce-responses\" class=\"clear\">\n";
    $html .= "<div class=\"response\" id=\"mce-error-response\" style=\"display:none\"></div>\n";
    $html .= "<div class=\"response\" id=\"mce-success-response\" style=\"display:none\"></div>\n";
    $html .= "</div>\n";
    $html .= "<div style=\"position: absolute; left: -5000px;\"><input type=\"text\" name=\"b_9e1a186caa4966a553bf6132d_d13f0199f8\" tabindex=\"-1\" value=\"\"></div>\n";
    $html .= "<div class=\"clear\"><input type=\"submit\" value=\"Subscribe\" name=\"subscribe\" id=\"mc-embedded-subscribe\" class=\"button\"></div>\n";
    $html .= "</form>\n";
    $html .= "</div>\n";

    $html .= "<!--End mc_embed_signup-->\n";

    return $html;
}
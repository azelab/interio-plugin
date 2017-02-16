<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/*-----------------------------------------------------------------------------------*/
/* tt_fblike facebook Shortcode - twitter
/*-----------------------------------------------------------------------------------*/
/*

Source: http://developers.facebook.com/docs/reference/plugins/like

Optional arguments:
 - float: none (default), left, right
 - url: link you want to share (default: current post ID)
 - style: standard (default), button
 - showfaces: true or false (default)
 - width: 450
 - verb: like (default) or recommend
 - colorscheme: light (default), dark
 - font: arial (default), lucida grande, segoe ui, tahoma, trebuchet ms, verdana

*/
if( !function_exists( 'interio_rs_sc_fblike_fn' )) {
    function interio_rs_sc_fblike_fn( $atts, $content = null ) {
        extract( shortcode_atts( array(
            'float'       => 'none',
            'url'         => '',
            'style'       => 'standard',
            'showfaces'   => 'false',
            'width'       => '450',
            'verb'        => 'like',
            'colorscheme' => 'light',
            'font'        => 'arial',
            'locale'      => 'en_US',
            'class'        => '',
        ), $atts ) );

        global $post;

        if ( ! $post ) {
            $post     = new stdClass();
            $post->ID = 0;
        }

        $allowed_styles = array( 'standard', 'button_count', 'box_count' );

        if ( ! in_array( $style, $allowed_styles ) ) {
            $style = 'standard';
        } // End IF Statement

        if ( ! $url ) {
            $url = get_permalink( $post->ID );
        }

        $height = '65';
        if ( $showfaces == 'true' ) {
            $height = '100';
        }

        if ( ! $width || ! is_numeric( $width ) ) {
            $width = 450;
        } // End IF Statement

        // Set the width to "auto" if "showfaces" is off and the default width is still set.
        $widthpx = $width . 'px';
        if ( $width == 450 && $showfaces == 'false' ) {
            $widthpx = 'auto';
        }

        // Set the height to 20 if "showfaces" is disabled and the style is either "standard" or "button_count".
        if ( $showfaces == 'false' && ( $style != 'box_count' ) ) {
            $height = 25;
        }

        switch ( $float ) {
            case 'left':
                $float = 'fl';
                break;

            case 'right':
                $float = 'fr';
                break;

            default:
                break;
        }

        $src_url = 'http://www.facebook.com/plugins/like.php?href=' . esc_url( $url ) . '&amp;layout=' . urlencode( $style ) . '&amp;show_faces=' . urlencode( $showfaces ) . '&amp;width=' . urlencode( $width ) . '&amp;action=' . urlencode( $verb ) . '&amp;colorscheme=' . urlencode( $colorscheme ) . '&amp;font=' . urlencode( $font ) . '&locale=' . urlencode( $locale ) . '';
        $output  = '
<div class="tt-fblike ' . esc_attr( $float )  .' '.  esc_attr( $class ) .'">
<iframe src="' . esc_url( $src_url ) . '" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:' . esc_attr( $widthpx ) . '; height:' . esc_attr( $height ) . 'px;"></iframe>
</div>
	';

        return $output;
    } // End tt_shortcode_fblike()

    add_shortcode( 'templatation_fblike', 'interio_rs_sc_fblike_fn' );
}



/*-----------------------------------------------------------------------------------*/
/* Twitter Shortcode - twitter
/*-----------------------------------------------------------------------------------*/
/*

Source: http://twitter.com/goodies/tweetbutton

Optional arguments:
 - style: vertical, horizontal, none ( default: vertical )
 - url: specify URL directly
 - source: username to mention in tweet
 - related: related account
 - text: optional tweet text (default: title of page)
 - float: none, left, right (default: left)
 - lang: fr, de, es, js (default: english)
 - use_post_url: automatically retrieve the URL to the specific post (useful on archive screens)
*/
if( !function_exists( 'interio_rs_sc_twitter_fn' )) {
    function interio_rs_sc_twitter_fn( $atts, $content = null ) {
        global $post;
        extract( shortcode_atts( array(
            'url'          => '',
            'style'        => '',
            'source'       => '',
            'text'         => '',
            'related'      => '',
            'lang'         => '',
            'float'        => 'none',
            'use_post_url' => 'false',
            'recommend'    => '',
            'hashtag'      => '',
            'size'         => '',
            'class'        => '',
        ), $atts ) );
        $output = '';

        if ( $url ) {
            $output .= ' data-url="' . esc_url( $url ) . '"';
        }

        if ( $source ) {
            $output .= ' data-via="' . esc_attr( $source ) . '"';
        }

        if ( $text ) {
            $output .= ' data-text="' . esc_attr( $text ) . '"';
        }

        if ( $related ) {
            $output .= ' data-related="' . esc_attr( $related ) . '"';
        }

        if ( $hashtag ) {
            $output .= ' data-hashtags="' . esc_attr( $hashtag ) . '"';
        }

        if ( $size ) {
            $output .= ' data-size="' . esc_attr( $size ) . '"';
        }

        if ( $lang ) {
            $output .= ' data-lang="' . esc_attr( $lang ) . '"';
        }

        if ( $style != '' ) {
            $output .= 'data-count="' . esc_attr( $style ) . '"';
        }

        if ( $use_post_url == 'true' && $url == '' ) {
            $output .= ' data-url="' . get_permalink( $post->ID ) . '"';
        }

        $output = '<div class="tt-sc-twitter ' . esc_attr( $float )  .' '. esc_attr( $class ) .'"><a href="' . esc_url( 'http://twitter.com/share' ) . '" class="twitter-share-button"' . $output . '>' . __( 'Tweet', 'templatation' ) . '</a><script type="text/javascript" src="' . esc_url( 'http://platform.twitter.com/widgets.js' ) . '"></script></div>';

        return $output;

    } // End tt_shortcode_twitter()

    add_shortcode( 'templatation_twitter', 'interio_rs_sc_twitter_fn' );
}


if ( !function_exists( 'interio_rs_sc_pin_fn' ) ) {
    function interio_rs_sc_pin_fn( $atts, $content = null ) {
        global $post;
        $float = 'fl';

        extract( shortcode_atts( array(
            'title'          => '',
            'image'        => '',
            'float'        => 'none',
            'class'        => '',
        ), $atts ) );


        $title = ( isset( $atts['title'] ) ) ? $atts['title'] : __( 'Pin This Post', 'templatation' );
        $data = ( isset( $atts['data'] ) ) ? $atts['data'] : '<img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" />';
        switch ( $float ) {
            case 'left':
                $float = 'fl';
                break;

            case 'right':
                $float = 'fr';
                break;

            default:
                break;
        }

        $postpermalink = urlencode( get_permalink() );

        $imageurl = urlencode(
            wp_get_attachment_url(
                get_post_thumbnail_id( $post->ID )
            )
        );

        $html =
            '<div class="' .esc_attr($float) .' '. esc_attr( $class ) .' tt-pinterest"><a data-pin-do="buttonBookmark"  target="blank" href="http://pinterest.com/pin/create/button/?url='
            . $postpermalink
            . '&media='
            . $imageurl
            . '" title="'
            . $title
            . '">'
            . $data
            . '</a></div>';

        return $html;
    }

    add_shortcode( 'templatation_pinterest', 'interio_rs_sc_pin_fn' );
}
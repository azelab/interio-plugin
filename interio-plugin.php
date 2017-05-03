<?php
/*
Plugin Name: Interio Plugin
Plugin URI: http://azelab.com
Author: Azelab
Author URI: http://azelab.com
Version: 1.2
Description: Plugin needed for theme to work smoothly.
Text Domain: interio
*/


/*PLUGIN UPDATE*/
$theme = wp_get_theme();
if ('Interio' == $theme->name || 'Interio (Child Theme)' == $theme->name) {

  define('RS_DIR', plugin_dir_url( __FILE__ ));

  add_action( 'init', 'github_plugin_updater_test_init' );
  function github_plugin_updater_test_init() {
    include_once 'updater.php';
    define( 'WP_GITHUB_FORCE_UPDATE', true );
    if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin
      $config = array(
        'slug' => plugin_basename( __FILE__ ),
        'proper_folder_name' => 'interio-plugin',
        'api_url' => 'https://api.github.com/repos/azelab/interio-plugin',
        'raw_url' => 'https://raw.github.com/azelab/interio-plugin/master/',
        'github_url' => 'https://github.com/azelab/interio-plugin/',
        'zip_url' => 'https://github.com/azelab/interio-plugin/archive/master.zip',
        'sslverify' => true,
        'requires' => '4.0',
        'tested' => '4.7',
        'readme' => 'README.md',
        'access_token' => '',
      );
    }
  }


  // Define Constants
  define('TT_FW_ROOT', dirname(__FILE__));
  define('TT_FW_VERSION', '1.4');

  // Fetch the options set from theme, which we use to decide which features to turn on from this plugin.
  $defaults = array(
  		'portfolio_cpt'             => '1',
  		'team_cpt'                  => '0',
  		'client_cpt'                => '0',
  		'testimonial_cpt'           => '0',
  		'project_cpt'               => '0',
  		'metaboxes'                 => '1',
  		'theme_options'             => '1',
  		'common_shortcodes'         => '1',
  		'integrate_VC'              => '1',
  		'tt_widget_instagram'       => '0',
  		'tt_widget_twitter'         => '0',
  );
  $interio_rs_components = wp_parse_args( get_option('interio_rs_components_user'), $defaults ); // Replace defaults with values set in Theme.


  //Include Portfolio CPT
  if ( ! empty( $interio_rs_components['portfolio_cpt'] ) ) {
  	include TT_FW_ROOT . '/inc/CPT/tt-portfolio.php';
  }

  //Include Clients CPT
  if ( ! empty( $interio_rs_components['client_cpt'] ) ) {
  	include TT_FW_ROOT . '/inc/CPT/tt-client.php';
  }

  //Include Projects CPT
  if ( ! empty( $interio_rs_components['project_cpt'] ) ) {
  	include TT_FW_ROOT . '/inc/CPT/tt-project.php';
  }

  //Include Team CPT
  if ( ! empty( $interio_rs_components['team_cpt'] ) ) {
  	include TT_FW_ROOT . '/inc/CPT/tt-team.php';
  }

  //Include Testimonial CPT
  if ( ! empty( $interio_rs_components['testimonial_cpt'] ) ) {
  	include TT_FW_ROOT . '/inc/CPT/tt-testimonial.php';
  }

  //Include redux framework
  if ( ! class_exists( 'Redux' && ! empty( $interio_rs_components['theme_options'] ) ) ) {
  	include TT_FW_ROOT . '/inc/redux/admin-init.php';
  }

  //Include CS framework
  if ( ! class_exists( 'CSFramework' && ! empty( $interio_rs_components['metaboxes'] ) ) ) {
  	include TT_FW_ROOT . '/inc/cs-framework/cs-framework.php';
  }

  //Include Shortcodes
  if ( ! empty( $interio_rs_components['common_shortcodes'] ) ) {
  	include TT_FW_ROOT . '/inc/shortcodes/init.php';
  }

  //Include VC stuff
  if ( ! empty( $interio_rs_components['integrate_VC'] ) ) {
  	if ( function_exists( 'vc_set_as_theme' ) ) {
  		include TT_FW_ROOT . '/inc/vc/vc-init.php';
  	}
  }

  //Include twitter
  if ( ! empty( $interio_rs_components['tt_widget_twitter'] ) ) {
  	wp_enqueue_script( 'temptt-twitterFetcher', plugin_dir_url( __FILE__ ) . 'inc/assets/js/tt-twitterFetcher.js', array( 'jquery' ), null, true );
  	include TT_FW_ROOT . '/inc/widgets/tt-widget_twitter.php';
  }

  //Include instagram(tm)
  if ( ! empty( $interio_rs_components['tt_widget_instagram'] ) ) {
  	include TT_FW_ROOT . '/inc/plugins/wp-instagram-widget/wp-instagram-widget.php';
  }



  /*-----------------------------------------------------------------------------------*/
  /* Remove no-ttfmwrk class from body, when this plugin is active. */
  /*-----------------------------------------------------------------------------------*/
  add_filter( 'body_class','interio_rs_ttfmwrk_yes', 11 );
  if ( ! function_exists( 'interio_rs_ttfmwrk_yes' ) ) {
  function interio_rs_ttfmwrk_yes( $classes ) {

  	if (($key = array_search('no-ttfmwrk', $classes)) !== false) {
      unset($classes[$key]);
  	}
  	return $classes;
    }
  }
  include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  if(is_plugin_active('js_composer/js_composer.php')){
    function your_name_integrateWithVC() {
      function my_param($settings, $value) {
          $css_option = vc_get_dropdown_option( $settings, $value );
          $value1 = explode( ',', $value );
          $output  = '<select name="'. $settings['param_name'] .'" data-placeholder="'. $settings['placeholder'] .'" multiple="multiple" class="wpb_vc_param_value wpb_chosen chosen wpb-input wpb-efa-select '. $settings['param_name'] .' '. $settings['type'] .' '. $css_option .'" data-option="'. $css_option .'">';
          foreach ( $settings['value'] as $values => $option ) {
              $selected = ( in_array( $option, $value1 ) ) ? ' selected="selected"' : '';
              $output .= '<option value="'. $option .'"'. $selected .'>'.htmlspecialchars( $values ).'</option>';
          }
          $output .= '</select>' . "\n";      
          return $output;  
        }
      vc_add_shortcode_param('vc_efa_chosen', 'my_param');
    }
    add_action( 'vc_before_init', 'your_name_integrateWithVC' );
  }
  //interio_content
  function interio_content( $content, $ignore_html = false ) {
      global $shortcode_tags;

      if ( false === strpos( $content, '[' ) ) {
          print $content;
      }

      if (empty($shortcode_tags) || !is_array($shortcode_tags))
          print $content;

      // Find all registered tag names in $content.
      preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
      $tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );

      if ( empty( $tagnames ) ) {
          print $content;
      }

      $content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );

      $pattern = get_shortcode_regex( $tagnames );
      $content = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $content );

      // Always restore square braces so we don't break things like <!--[if IE ]>
      $content = unescape_invalid_shortcodes( $content );

      print $content;
  }

  add_action('wp_footer','floris_products_ajaxurl');
      function floris_products_ajaxurl() {
      ?>
      <script type="text/javascript">
          var ajaxurl = '<?php print admin_url('admin-ajax.php'); ?>';
      </script>
      <?php
  }

  function interio_woof_additional(){
    $output = '';
    $output .='<form class="woocommerce-ordering top-shop-item drop-down type-1" method="get"><h4 class="title">'.__('Sort by', 'interio').'</h4>';
    $output .='<select name="orderby" class="orderby drop-down-text"> ';
    $output .='<option value="menu_order" selected="selected">Default sorting</option>';
    $output .='<option value="popularity">Sort by popularity</option>';
    $output .='<option value="rating">Sort by average rating</option>';
    $output .='<option value="date">Sort by newness</option>';
    $output .='<option value="price">Sort by price: low to high</option>';
    $output .='<option value="price-desc">Sort by price: high to low</option>';
    $output .='</select>';
    $output .='</form>';
    $output .= do_shortcode('[woof]');
    return $output;
  }
  add_shortcode('interio_woof','interio_woof_additional');



  //Add admin css.
  add_action('admin_head', 'interio_admin_css');
  function interio_admin_css() {
      wp_enqueue_style( 'super_admin', INTERIO_THEME_DIRURI . 'assets/css/admin.css', '', null );
      wp_enqueue_style( 'super_admin_remodal', INTERIO_THEME_DIRURI . 'assets/css/remodal.css', '', null );
      wp_enqueue_style( 'super_admin_remodal_def', INTERIO_THEME_DIRURI . 'assets/css/remodal-default-theme.css', '', null );
  }

  //Add admin js.
  add_action('admin_footer', 'floris_admin_js');
  function floris_admin_js() {
      wp_enqueue_script( 'admin-js', INTERIO_THEME_DIRURI . 'assets/js/admin.js', array( 'jquery' ), null, true );
  }

  /*-------------------------------------*/
  /* Contact form
  /*-------------------------------------*/
  add_action('wp_ajax_contact_btn_send', 'interio_ajax_contact_btn_send');
  add_action('wp_ajax_nopriv_contact_btn_send', 'interio_ajax_contact_btn_send');

  function interio_ajax_contact_btn_send(){
      if (!empty($_POST) and !empty($_POST['qemail']) and !empty($_POST['rec_email'])) {
          $message = '';
          $send_email = sanitize_email($_POST['rec_email']);
          $from = sanitize_email($_POST['qemail']);


          $subject = __('Message from ','interio') . $from;
          $sender = 'From: ' . $from . ' <' . $from . '>' . "\n\r";
          for($i=0;$i<10;$i++){
              if (isset($_POST['q'.$i])){
                  $message.=$_POST['q'.$i.'_title'].' : '.$_POST['q'.$i].'<br>';
              }
          }
          $headers[] = $sender;
          $headers[] = 'Content-type: text/html; charset=utf-8' . "\n\r";
          $mail = wp_mail($send_email, $subject, $message, $headers);
          if (!$mail) {
               _e('Mail was not sent!','interio');
              die();
          }
      }else{
          _e('Missing data!','interio');
      }
  }


  /*-------------------------------------*/
  /* Contact Section contact form
  /*-------------------------------------*/
  add_action('wp_ajax_contact_section_send', 'interio_ajax_contact_section_send');
  add_action('wp_ajax_nopriv_contact_section_send', 'interio_ajax_contact_section_send');

  function interio_ajax_contact_section_send(){
      if (!empty($_POST) and !empty($_POST['q1']) and !empty($_POST['rec_email'])) {
          $message = '';
          $send_email = sanitize_email($_POST['rec_email']);
          $from = sanitize_email($_POST['q1']);

          $subject = __('Message from ','interio') . $from;
          $sender = 'From: ' . $from . ' <' . $from . '>' . "\n\r";
          
          if (isset($_POST['q1'])){
              $message.=$_POST['q1'].' : '.$_POST['q1'].'<br>';
          }
          if (isset($_POST['q2'])){
              $message.=$_POST['q2'].' : '.$_POST['q2'].'<br>';
          }
          if (isset($_POST['q3'])){
              $message.=$_POST['q3'].' : '.$_POST['q3'].'<br>';
          }


          $headers[] = 'Content-type: text/html; charset=utf-8' . "\n\r";
          $headers[] = $sender;

          $mail = wp_mail($send_email, $subject, $message, $headers);
          if (!$mail) {
               _e('Mail was not sent!','interio');
              die();
          }
      }else{
          _e('Missing data!','interio');
      }
  }


  /*==================================*/
  /*       ONEPAGE VC STYLING         */
  /*==================================*/
  function interio_load_onepage_styles () {
    global $wp_query;
    global $interio_rs_opt;
    if( interio_rs_get_option('tr_page_template') == true ) {

      $query = new WP_Query( array( 'post_type' => 'page' ) );
      if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
          $query->the_post();
          $cur_ID = get_the_ID();
    

        $post_custom_css = get_post_meta( $cur_ID, '_wpb_post_custom_css', true );
          if ( ! empty( $post_custom_css ) ) {
            $post_custom_css = strip_tags( $post_custom_css );
            echo '<style type="text/css" data-type="vc_custom-css">';
            echo $post_custom_css;
            echo '</style>';
          }


          $shortcodes_custom_css = get_post_meta( $cur_ID, '_wpb_shortcodes_custom_css', true );
          if ( ! empty( $shortcodes_custom_css ) ) {
            $shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
            echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
            echo $shortcodes_custom_css;
            echo '</style>';
          }
        }
      }

      wp_reset_postdata();

    }

  }
  add_action('wp_head', 'interio_load_onepage_styles');


  //--share scripts--//
  function interio_share_scripts(){
      if( !empty( $_SERVER['HTTPS'] ) ){ print '<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>'; }
      else{ print '<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>'; }
      print '<script type="text/javascript">stLight.options({publisher: "44ee8e50-33f1-4ef6-bd66-5c6bec0df4fa", doNotHash: true, doNotCopy: true, hashAddressBar: false});</script>';
  }

  /*WP Cost Estimation Plugin*/
  if (is_plugin_active( 'WP_Estimation_Form/estimation-form.php' ) ) {
      add_action('init', 'lfb_setThemeMode');
  }

  include_once 'inc/redux/theme-validation.php';
}
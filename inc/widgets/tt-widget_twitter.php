<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------*/
/*	TWITTER WIDGET                                                                   */
/*-----------------------------------------------------------------------------------*/
if(!( class_exists('TT_Temptt_Twitter_Widget') )){
	class TT_Temptt_Twitter_Widget extends WP_Widget {

		/**
		 * Sets up the widgets name etc
		 */
		public function __construct(){
			parent::__construct(
				'temptt-twitter-widget', // Base ID
				__('Theme: Twitter Widget', 'templatation'), // Name
				array( 'description' => __( 'Add a Twitter feed widget', 'templatation' ), ) // Args
			);
		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) )
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];

			if ( isset( $instance['username'] ) )
				echo '<div class="tt-tw-feed"><div id="tt-show-tweets" class="tt-tweets" data-widget-id="'. $instance['username'] .'"></div>';

			echo $args['after_widget'];
		}

		/**
		 * Outputs the options form on admin
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {

			$defaults = array(
				'title' => 'Twitter Feed',
				'username' => ''
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			extract($instance);
		?>

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title:' ); ?></label>
				<input class="widgettitle" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'username' ); ?>">Twitter Widget ID <code>e.g: 123456789123456789</code>
				<p class="description">
				<strong>Note!</strong> Generate ID by: 1) Go to your twitter account. 2) Click 'Settings' then 'Widgets'. 3) Click 'Create New' and then 'Create Widget'. 4) After widget creation, go back to the 'Widgets' page and click 'Edit' on your newly created widget. Copy the widget id ( the long numerical string after /widgets/ and before /edit.) out of the url bar. </p></label>
				<input class="widgettitle" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>">
			</p>

		<?php
		}

		/**
		 * Processing widget options on save
		 */
		public function update( $new_instance, $old_instance ) {
			return $new_instance;
		}
	}
	function interio_rs_register_twitter_widget(){
	     register_widget( 'TT_Temptt_Twitter_Widget' );
	}
	add_action( 'widgets_init', 'interio_rs_register_twitter_widget');
}

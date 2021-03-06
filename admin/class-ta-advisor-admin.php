<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class TA_Advisor_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'normalize', plugin_dir_url( __FILE__ ) . 'css/normalize.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ta-advisor-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'vue', plugin_dir_url( __FILE__ ) . 'js/vue.js', array(), $this->version, false );
		wp_enqueue_script( 'vue-router', plugin_dir_url( __FILE__ ) . 'js/vue-router.js', array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ta-advisor-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 
			$this->plugin_name, 
			'ajax_object', 
			[ 
				'ajax_url' => admin_url('admin-ajax.php'), 
				'ta_advisor_nonce' =>  wp_create_nonce('ta_advisor_nonce')
			] );

	}


	public function register_tc_pages(){
		add_menu_page( 'TA Advisor', 'TA Advisor', 'manage_options', 'ta-advisor', [$this, 'ta_advisor_main_page_callback'], plugin_dir_url( __FILE__ ) . '/images/icon.png', 6 );
		add_submenu_page( 'ta-advisor', 'TA Advisor Dashboard','TA Advisor Dashboard', 'manage_options', 'ta-advisor-dashboard', [$this, 'ta_advisor_sub_page_callback']);
	}

	public function ta_advisor_main_page_callback(){
		ob_start();
		require_once( plugin_dir_path( __FILE__ ) . 'partials/ta-advisor-admin-display.php' );
		$html = ob_get_contents();
		ob_end_clean();
		echo $html;
	}
	public function ta_advisor_sub_page_callback(){
		ob_start();
		require_once( plugin_dir_path( __FILE__ ) . 'partials/ta-advisor-admin-display.php' );
		$html = ob_get_contents();
		ob_end_clean();
		echo $html;
	}

	// get all quizes
	public function ta_get_quizes(){
		global $wpdb;
		$table_name = "ta_quiz";
		$result = $wpdb->get_results( "SELECT * FROM {$table_name}", ARRAY_A );

		echo wp_json_encode($result);
		wp_die();
	}

	// get one quiz
	public function ta_get_quiz(){
		
		global $wpdb;
		$table_name = "ta_quiz";
		$quiz_id = $_POST['id'];

		$result = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE id={$quiz_id}", ARRAY_A );

		echo wp_json_encode($result);
		wp_die();
	}

	// add new quiz
	public function ta_add_quiz(){
		global $wpdb;
		$table_name = "ta_quiz";
		
		$quiz_name = $_POST['quiz_name'];

		$results = $wpdb->insert(
			$table_name,
			[
				'id' => null,
				'quiz_name' => $quiz_name
			]
		);
		echo json_encode($results);
	}
	// edit quiz
	public function ta_edit_quiz(){
		global $wpdb;
		$table_name = "ta_quiz";
		
		$quiz_id = $_POST['quiz_id'];
		$quiz_name = $_POST['quiz_name'];

		$results = $wpdb->update(
			$table_name,
			[
				'id' => $quiz_id,
				'quiz_name' => $quiz_name
			],
			['id' => $quiz_id],
			[ '%d', '%s'],
			['%d']
		);
		echo json_encode($results);
	}

	public function get_svg( $name ){
		$svg_path = dirname(__FILE__) . '/images/' . $name . '.svg';

		ob_start();
		require_once ($svg_path);
		$svg = ob_get_contents();
		ob_end_clean();
		return $svg;
		
	}
}	

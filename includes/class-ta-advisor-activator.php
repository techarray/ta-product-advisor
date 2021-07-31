<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class TA_Advisor_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		// global variable of wordpress database
		global $wpdb;
		//global variable of our plugin database table
		global $ta_db_version;
		global $ta_prefix;
		
		//database collate
		$charset_collate = $wpdb->get_charset_collate();

		$tca_db_version = '1.0';
		$tc_prefix = "ta_";

		/**
		 * quiz table
		 */
		$table_quiz = $tc_prefix . 'quiz';
		$quiz_sql = "CREATE TABLE $table_quiz ( 
		id int(11) NOT NULL AUTO_INCREMENT, 
		quiz_name tinytext NOT NULL, 
		PRIMARY KEY  (id) 
		) $charset_collate;";


		/**
		 * question table
		 */
		$table_question = $tc_prefix . 'question';
		$question_sql = "CREATE TABLE $table_question (
		id int(11) NOT NULL AUTO_INCREMENT,
		question text NOT NULL,
		question_type text NOT NULL,
		quiz_id int NOT NULL,
		PRIMARY KEY  (id),
		FOREIGN KEY (quiz_id) REFERENCES $table_quiz(id)
		) $charset_collate;";		
		

		/**
		 * Answer table
		 */
		$answer_table = $tc_prefix . 'answer';
		$answer_sql = "CREATE TABLE $answer_table (
		id int(11) NOT NULL AUTO_INCREMENT,
		answer text NOT NULL,
		question_id int NOT NULL,
		PRIMARY KEY  (id),
		FOREIGN KEY (question_id) REFERENCES $table_question(id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $quiz_sql );
		dbDelta( $question_sql );
		dbDelta( $answer_sql );

		add_option( 'ta_db_version', $ta_db_version );
	}

}

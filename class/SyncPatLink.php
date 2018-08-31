<?php

class SyncPatLink {

	public function __construct()
	{
		add_action('wp_loaded', array($this, 'check'));
		add_action('admin_menu', array($this, 'add_admin_menu'), 20);
	}

	public function add_admin_menu(){
		add_submenu_page('sync','Synchronisation du sommaire', 'Synchronisation du sommaire', 'manage_options', 'syncPat', array($this, 'display_home'));
	}

	public function display_home(){
		echo "<h1>Synchronisation des Liens du sommaire : </h1>";
		include_once plugin_dir_path( __FILE__ ).'../views/SyncPatLink.php';
	}

	public function check(){

		if (isset($_GET['actionPLUGIN']) && $_GET['actionPLUGIN'] == 'syncPat') {

			global $wpdb;
			$results = $wpdb->get_results("SELECT a.*, b.guid FROM {$wpdb->prefix}titles_and_links as a , {$wpdb->prefix}posts as b WHERE a.idPost = b.id");

			foreach ($results as $result) {

                $wpdb->query("UPDATE {$wpdb->prefix}tocs SET content = REPLACE(content, 'html\\\\".$result->file."', '".$result->guid."')");

			}

		}

	}

}
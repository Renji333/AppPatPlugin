<?php

class logs
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    }

    public function add_admin_menu()
    {
        add_menu_page('Historique', 'Historique', 'manage_options', 'logs', array($this, 'display_home_link'), 'dashicons-archive');
    }

    public function insertLogs($txt){
        global $wpdb;
        $txt = htmlspecialchars(str_replace("/","_",$txt));
        $wpdb->insert("{$wpdb->prefix}logs", array('file' => $txt));
    }

    public function DeleteLogs($txt){
        global $wpdb;
        $txt = htmlspecialchars($txt);
        $wpdb->delete( "{$wpdb->prefix}logs" , array( 'file' => $txt ) );
    }

    public function display_home_link()
    {
        global $wpdb;

        $nbDisplayPages = 30;
        $ret = $wpdb->get_results("SELECT COUNT(*) AS total FROM {$wpdb->prefix}logs");
        $nbPages = ceil($ret[0]->total / $nbDisplayPages );

        if(isset($_GET['p'])){

            $current = intval($_GET['p']);

            if($current > $nbPages){
                $current = $nbPages;
            }

        }else{

            $current = 1;

        }

        $first = ($current-1)*$nbDisplayPages;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}logs ORDER BY id DESC LIMIT ".$first.", ".$nbDisplayPages);

        include_once plugin_dir_path(__FILE__) . '../views/logs.php';

    }

}

new logs();
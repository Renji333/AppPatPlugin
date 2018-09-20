<?php

add_action('wp_ajax_getLinks', 'getLinks');
add_action('wp_ajax_AddLinks', 'AddLinks');
add_action('wp_ajax_DelLinks', 'DelLinks');

function getLinks(){

    global $wpdb;

    $nb = htmlspecialchars($_REQUEST['id']);
    $type = htmlspecialchars($_REQUEST['type']);

    if($type == "PAT")  {

        $results = $wpdb->get_results("SELECT p.`ID` as id,p.`post_title`,p.`post_date`, p.`guid`, n.`title`, n.`id` as idDel FROM {$wpdb->prefix}posts p, {$wpdb->prefix}post_to_pat_links n WHERE p.`ID` = n.`idPostInLink` and `idPost` = ".$nb);

    }   elseif($type == "NLP") {

        $results = $wpdb->get_results("SELECT p.`ID` as id,p.`post_title`,p.`post_date`, p.`guid`, n.`title`, n.`id` as idDel FROM {$wpdb->prefix}posts p, {$wpdb->prefix}post_to_nlp_links n WHERE p.`ID` = n.`idPostInLink` and `idPost` = ".$nb);

    }

    wp_send_json($results);
    wp_die();

}

function AddLinks(){

    global $wpdb;
    $type = htmlspecialchars($_REQUEST['type']);

    if($type == "PAT"){

        $id = htmlspecialchars($_REQUEST['idPost']);
        $idPostInLink = htmlspecialchars($_REQUEST['idPostInLink']);

        $wpdb->insert("{$wpdb->prefix}post_to_pat_links", array('idPost' => $id ,'idPostInLink' => $idPostInLink));

    } else {

        $id = htmlspecialchars($_REQUEST['idPost']);
        $idPostInLink = htmlspecialchars($_REQUEST['idPostInLink']);

        $wpdb->insert("{$wpdb->prefix}post_to_nlp_links", array('idPost' => $id ,'idPostInLink' => $idPostInLink));

    }

    echo 'ok';
    wp_die();

}

function DelLinks(){

    global $wpdb;

    $id = htmlspecialchars($_REQUEST['id']);
    $type = htmlspecialchars($_REQUEST['type']);

    if($type == "PAT")  {

        $wpdb->delete( "{$wpdb->prefix}post_to_pat_links" , array( 'id' => $id ) );

    }   elseif($type == "NLP") {

        $wpdb->delete( "{$wpdb->prefix}post_to_nlp_links" , array( 'id' => $id ) );

    }

    wp_die();

}

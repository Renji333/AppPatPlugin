<?php

class AjoutNlp {

	public $results;
	public $Logs;

	public function __construct()
	{
		add_action('wp_loaded', array($this, 'checkForm'));
		add_action('admin_menu', array($this, 'add_admin_menu'), 20);
	}

	public function add_admin_menu()
	{
		add_menu_page( 'Ajout NLP', 'Ajout NLP (JSON)', 'manage_options', 'addNlp', array($this, 'display_home'));
	}

	public function display_home(){
		echo '<h1>Ajout des newsletters manuellement : </h1>';
		include_once plugin_dir_path( __FILE__ ).'../views/AjoutNlp.php';
	}

	public function makeDate($e,$i){

		$tableHeure = ['00:00:00','00:00:01','00:00:02','00:00:03','00:00:04','00:00:05','00:00:06','00:00:07','00:00:08','00:00:09','00:00:10','00:00:11','00:00:12'];

		$e = str_replace(' ', '-', $e);
		$e = html_entity_decode($e);
		$e = str_replace('janvier', '01', $e);
		$e = str_replace('février', '02', $e);
		$e = str_replace('mars', '03', $e);
		$e = str_replace('avril', '04', $e);
		$e = str_replace('mai', '05', $e);
		$e = str_replace('juin', '06', $e);
		$e = str_replace('juillet', '07', $e);
		$e = str_replace('août', '08', $e);
		$e = str_replace('septembre', '09', $e);
		$e = str_replace('octobre', '10', $e);
		$e = str_replace('novembre', '11', $e);
		$e = str_replace('décembre', '12', $e);

		$e = date('Y-m-d H:i:s', strtotime($e.' '.$tableHeure[12 - $i]));

		return $e;

	}

	public function utf8_fopen_read($fileName) {
		$fc = iconv('windows-1250', 'utf-8', file_get_contents($fileName));
		$handle=fopen("php://memory", "rw");
		fwrite($handle, $fc);
		fseek($handle, 0);
		return $handle;
	}

	public function checkForm(){

		if( isset($_FILES['rea']) && $_FILES['rea'] != ''){
			$this->check_dir_rea($_FILES['rea']);
		}

	}

	public function check_dir_rea($ret){

		$parts = pathinfo(plugin_dir_path( __FILE__ ).'../nlp_json/recup_nlp/'.$ret['name']);
		$file = $parts['filename'];
		$string = file_get_contents(plugin_dir_path( __FILE__ ).'../nlp_json/recup_nlp/'.$ret['name']);
		$jsonTab = json_decode($string);
		$iH = 0 ;

		if($jsonTab != null){
			foreach ($jsonTab as $json) {

				$CatId = [];

                $corpsFile = $this->utf8_fopen_read( plugin_dir_path( __FILE__ ).'../nlp_json/'.str_replace("archive","",$json->corps), "r");
                $UploadPath = wp_upload_dir();
                $corps  = (string) fread($corpsFile,9999999);
                $corps = preg_replace_callback('/src="..\/..\/..\/images\/(.*?)"/',"makeRegexSpaceSrcImg",$corps);
                $corps = preg_replace('/<img src="..\/..\/..\/images\//','<img src="'.$UploadPath['url'].'/',$corps);
                $corps = html_entity_decode($corps);

                $corps = preg_replace('/<span(.*?)class="titre_1">(.*?)<\/span>/mi', '<h1>$2</h1>', $corps);
                $corps = preg_replace('/<span(.*?)class="titre_2">(.*?)<\/span>/mi', '<h2>$2</h2>', $corps);
                $corps = preg_replace('/<span(.*?)class="titre_3">(.*?)<\/span>/mi', '<h3>$2</h3>', $corps);
                $corps = preg_replace('/<span(.*?)class="titre_4">(.*?)<\/span>/mi', '<h4>$2</h4>', $corps);
                $corps = preg_replace('/<span(.*?)class="titre_5">(.*?)<\/span>/mi', '<h5>$2</h5>', $corps);
                $corps = preg_replace('/<span(.*?)class="titre_6">(.*?)<\/span>/mi', '<h6>$2</h6>', $corps);

                fclose($corpsFile);

                $nlpCat = term_exists( 'NLP', 'category' );
                if (is_wp_error($nlpCat) || $nlpCat == NULL ) {
                    $nlpCat = wp_insert_term('NLP', 'category', [ 'slug' => 'NLP']);
                }
                array_push($CatId, $nlpCat['term_id']);

				$new_post = array(
					'post_title' => html_entity_decode($json->title),
					'post_content' => $corps,
					'post_date' => $this->makeDate($json->date,$iH),
					'post_status' => 'publish',
					'post_author' => 1,
					'post_category' => $CatId
				);

				$postsId = wp_insert_post( $new_post );
				$iH = $iH + 1;

			}
		}
	}
}
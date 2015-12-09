<?php
/*
Plugin Name: FBR Image Marker
Plugin URI: http://fabrictheme.com
Description: Wordpress Image text marker
Version: 1.1
Author: Inef
Author URI: http://inef.web.id
*/

include_once('controller/fabric-marker.php');
$marker = new FabricImageMarker();
register_activation_hook(__FILE__,'fabric_image_marker');
function fabric_image_marker(){

}


/**
  * @function 	: just_testi_menu
  *	@desc 		: This function will create menu in admin wordpress
  */
add_action("admin_menu", "fabim_menu");
function fabim_menu() {
	add_menu_page( 'Image Marker', 'Image Marker', 'add_users', 
		'fabric-marker', 'fabim_init', 'dashicons-media-document', 13);
}

function fabim_init(){
	global $marker;
	$marker->view('admin');
}

add_action('admin_head', 'fabim_head');
function fabim_head(){
	if(function_exists( 'wp_enqueue_media' )){
		wp_enqueue_media();
	}
	wp_enqueue_script('jquery');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
	wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker');
    wp_enqueue_script( 'fabim-jquery-modal', plugin_dir_url( __FILE__ ).'assets/js/jquery.modal.js');
    wp_enqueue_script( 'fabim-html2canvas', plugin_dir_url( __FILE__ ).'assets/js/html2canvas.js');
    wp_enqueue_script( 'fabim-jquery.plugin.html2canvas', plugin_dir_url( __FILE__ ).'assets/js/jquery.plugin.html2canvas.js');
	wp_enqueue_script('farbtastic');
	wp_enqueue_style('fabim-style', plugin_dir_url( __FILE__ ).'assets/css/style.css');
	wp_enqueue_style('fabim-jquery-modal', plugin_dir_url( __FILE__ ).'assets/css/jquery.modal.css');
}

add_action( 'wp_ajax_fabim_upload', 'fabim_upload_action' );
function fabim_upload_action() {
	$image = $_POST['image'];
	//Get the base-64 string from data
	$filteredData=substr($_POST['image'], strpos($_POST['image'], ",")+1);
	 
	//Decode the string
	$unencodedData=base64_decode($filteredData);
	$originalName = basename(parse_url('image_marker.png', PHP_URL_PATH));
	$tempName = tempnam('/tmp', 'php_files');
	file_put_contents($tempName, $unencodedData);
	$data = array();
	$data['name'] = $originalName;
	$data['type'] = 'image/png';
	$data['tmp_name'] = $tempName;
	$data['error'] = 0;
	$data['size'] = strlen($unencodedData);

	$_FILES['image'] = array(
        'name' => $originalName,
        'type' => 'image/png',
        'tmp_name' => $tempName,
        'error' => 0,
        'size' => strlen($unencodedData),
    );
	$movefile = wp_handle_upload($_FILES['image'], array( 'test_form' => false ));

	if ($movefile) 
    {
        echo "a File is valid, and was successfully uploaded.\n";
        var_dump( $movefile);
    } 
    else 
    {
        echo "Possible file upload attack!\n";
    }
}
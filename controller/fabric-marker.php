<?php

class FabricImageMarker{
	
	public function __construct(){

	}

	public function view($file){
		$file = plugin_dir_path( __FILE__ ) .'../views/'.$file.'.php';
		if(file_exists($file))
			include_once($file);
	}

	public function colorField($name,$default,$current,$data_change){
		$style = $current ? 'background-color:'.$current : 'background-color:'.$default;
		$value = $current ? $current : $default;
		echo '<input name="general['.$name.']" size="8" id="'.$name.'" value="'.$value.'"/>';
		echo '<button type="button" style="'.$style.'" id="'.$name.'Target">&nbsp;</button>';
		echo '<div data-change="'.$data_change.'" class="colorpick" id="'.$name.'Picker" target-id="'.$name.'"></div>';
	}
}
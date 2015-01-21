<?php
/*
Plugin Name: AddAuthorsBase
Plugin URI: http://github.com
Description: Add Base of Authors
Version: 1.0.0
Author: Kolesov Alexey
Author URI: http://
*/
header('Content-Type: text/html; charset=UTF-8');
add_action('admin_menu', 'CreateMyPluginMenu');
	function CreateMyPluginMenu()
	{
		if (function_exists('add_options_page'))
		{
			add_options_page('Настройки Authors.', 'AuthorsSetting', 'manage_options', 'Myplugin', 'MyPluginPageOptions');
		}
	}
	function MyPluginPageOptions()
	{
		echo "<h2>Настройки Authors.</h2>";
?>
	<form method = "POST">
	Добавить в базу:
		<input type = "text" value = "Ссылка на CSV" name = "ssilka">
		<input type = "submit" name = "Enter">
	</form>
<?php
	}

if(isset($_POST['Enter']))
{
	if ( ($handle_o = fopen($_POST['ssilka'] , "r") ) !== FALSE ) 
	{
		$title_column = fgetcsv($handle_o, 1000, ";"); //закоментить если названия столбцов не в первой строке
		while ( ($data_o = fgetcsv($handle_o, 1000, ";")) !== FALSE) 
		{
		  $insertValues = array();
		  foreach( $data_o as $v ) {
			 $insertValues[]=addslashes(trim($v));
		  }
		  $wpdb->insert( 
				$wpdb->prefix . 'posts',
				array(
					'post_title' => $insertValues[1]." ".$insertValues[2]." ".$insertValues[3],
					'post_type' => 'author',
					'post_content' => $insertValues[4],
					'post_date' => date('Y-m-d H:i:s')
				), 
				array( 
					//'%d',  - число
					'%s', // %s -  строка
					'%s',
					'%s'	
				));
			$author_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title ='".$insertValues[1]." ".$insertValues[2]." ".$insertValues[3]."'");
			$year = substr("'".$insertValues[0]."'", 1, 2);
			$str_year = "'".$year."'";
			$str_month = "'".$month."'";
			$str_page = "'".$page."'";			
			$month = substr("'".$insertValues[0]."'", 3, 2);
			$page = substr("'".$insertValues[0]."'", 5,-1);
			add_post_meta($author_id, 'name', $insertValues[2]);
			add_post_meta($author_id, 'father_name', $insertValues[3]);
			add_post_meta($author_id, 'live_data', $insertValues[5]);
				$args = array(
					'alias_of'=>$year
					,'description'=>$year
					,'parent'=>0
					);
				$term_add = wp_insert_term( $year, 'year', $args );
				//wp_set_post_terms( $author_id, $term_add, 'year');					
		}			
		
	}
fclose($handle_o);
}

add_action('init', 'cptui_register_my_cpt_author'); // Регистрируем тип поста - Автор
add_action('init', 'cptui_register_my_taxes_names');
function cptui_register_my_taxes_names() {

register_taxonomy( 'year',array (
  0 => 'author',
),
array( 'hierarchical' => false,
	'label' => 'Years',
	'show_ui' => true,
	'query_var' => true,
	'show_admin_column' => true,
	'labels' => array (
  'search_items' => 'year'
)
) ); 
register_taxonomy( 'month',array (
  0 => 'author',
),
array( 'hierarchical' => false,
	'label' => 'Months',
	'show_ui' => true,
	'query_var' => true,
	'show_admin_column' => true,
	'labels' => array (
  'search_items' => 'month'
)
) ); 
register_taxonomy( 'page',array (
  0 => 'author',
),
array( 'hierarchical' => false,
	'label' => 'Pages',
	'show_ui' => true,
	'query_var' => true,
	'show_admin_column' => true,
	'labels' => array (
  'search_items' => 'page'
)
) ); 

}
function cptui_register_my_cpt_author() 
{
register_post_type('author', array(
		'label' => 'Authors',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array('slug' => 'author', 'with_front' => true),
		'query_var' => true,
		'has_archive' => true,
		'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes','post-formats'),
		'taxonomies' => array('year','month','page'),
				'labels' => array (
					  'name' => 'Authors',
					  'singular_name' => 'Author',
					  'menu_name' => 'Authors',
					  'add_new' => 'Add Author',
					  'add_new_item' => 'Add New Author',
					  'edit' => 'Edit',
					  'edit_item' => 'Edit Author',
					  'new_item' => 'New Author',
					  'view' => 'View Author',
					  'view_item' => 'View Author',
					  'search_items' => 'Search Authors',
					  'not_found' => 'No Authors Found',
					  'not_found_in_trash' => 'No Authors Found in Trash',
					  'parent' => 'Parent Author',
						)	
		) ); 
}
include_once("content_view.php");
?>

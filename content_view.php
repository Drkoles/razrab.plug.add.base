<?php
add_filter('the_content', 'view_taxs');
function view_taxs($content)
	{
	if((is_archive()) && (get_post_type() == 'author'))
		{
		$title_post = get_the_title(get_the_ID());
		$name = get_post_meta(get_the_ID(), 'name');
		$father_name = get_post_meta(get_the_ID(), 'father_name');
		$live_data = get_post_meta(get_the_ID(), 'live_data');
		$year = get_the_term_list(get_the_ID(), 'year','','');
		$month = get_the_term_list(get_the_ID(), 'month','','');
		$page = get_the_term_list(get_the_ID(), 'page','','');
					$content = '<table border = 1><tr><td>'.$title_post.'</td>';
					$content .= '<td>'.$name[0].'</td>';
					$content .= '<td>'.$father_name[0].'</td>';
					$content .= '<td>'.$live_data[0].'</td>';
					$content .= '<td>'.get_the_content().'</td>';
					$content .= '<td>'.$year."/";
					$content .= $month."/";
					$content .= $page.'</td></tr></table>';
					return $content;
		}
	}
 ?>

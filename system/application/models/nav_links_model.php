<?php

//handles nav bar
class Nav_links_model extends Model
{

		function Nav_links_model()
		{
			parent::Model();
		}
		
		function getNavBarLinks()
		{
			$this->db->select('name, url, link_order')->from('nav_links')->order_by('link_order', 'asc');
			
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$nav_titles = array();
				foreach($query->result_array() as $row)
				{
					$nav_titles[] = array(
						'name' => $row['name'],
						'url' => $row['url']
					);
				}
				return $nav_titles;
			}
		}
}
		
?>
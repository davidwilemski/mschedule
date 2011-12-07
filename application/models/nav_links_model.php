<?php
	/*
		Model for creating the nav_bar. 
		getNavBarLinks() - Retrieves the navigation links from nav_links and returns them as a table to the caller
	*/
?>
<?php

//handles nav bar
class Nav_links_model extends CI_Model
{

		function __construct()
		{
			parent::__construct();
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
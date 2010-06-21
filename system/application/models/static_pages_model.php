<?php 
	/*
		This model is meant to contain functions that will work with the static_pages table. 
		It will allow getting page titles for the navigation bar and loading page content as
		well as the ability to update static pages.
	*/
	class Static_pages_model extends Model
	{
		function Static_pages_model()
		{
			parent::Model();
		}
				
		
		function getPageContent($page)
		{
			$this->db->select('title, content')->where('URL_name', $page)->from('static_pages');
			
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$page_content = $query->row_array();
				return $page_content;
			}
			
		}

	}
?>
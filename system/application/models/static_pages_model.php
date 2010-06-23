<?php
	/*
		This model is meant to contain functions that will work with the static_pages table. 
		getPageContent() - allows getting page titles for the navigation bar and loading page content.
		Will be able to update static pages.
	*/
?>
<?php 
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
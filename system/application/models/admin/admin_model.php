<?php

class Admin_model extends Model
{
	function Admin_model()
	{
		parent::Model();
		
		//Needed to convert timestamps
		$this->load->helper('date');

	}
	
	//Returns a list of the 10 most recently updated rooms in JSON format
	function updatedPages()
	{
		$this->db->select('id, date_modified, title')->from('static_pages')->order_by('date_modified', 'desc')->limit(10);
		
		$query = $this->db->get();
		
		if($query->num_rows > 0)
		{
			$count = 1;
			$output = '{ "pages": [';
			foreach($query->result_array() as $result)
			{
				if($count < $query->num_rows())
				{
					$output .= '{"id": ' . $result['id'] . ', "title": "' . $result['title'] . '" , "date_modified": ' . mysql_to_unix($result['date_modified']) . '},';				
				}
				else
				{
					$output .= '{"id": ' . $result['id'] . ', "title": "' . $result['title'] . '" , "date_modified": ' . mysql_to_unix($result['date_modified']) . '}';
				}
				$count++;
			}
			
			$output .= '] }';
		}
		
		return $output;
	}
	
	//Returns JSON for the most recently registered users
	function newUsers()
	{
		$this->db->select('userID, first_name, last_name')->from('users')->order_by('userID', 'DESC')->limit(10);
		
		$query = $this->db->get();
		
		if($query->num_rows > 0)
		{
			$count = 1;
			$output = '{ "new_users": [';
			foreach($query->result_array() as $result)
			{
				if($count < $query->num_rows())
				{
					$output .= '{"userID": "' . $result['userID'] . '" , "first_name": "' . $result['first_name'] . '" , "last_name": "' . $result['last_name'] . '" },';				
				}
				else
				{
					$output .= '{"userID": "' . $result['userID'] . '" , "first_name": "' . $result['first_name'] . '" , "last_name": "' . $result['last_name'] . '" }';
				}
				$count++;
			}
			
			$output .= '] }';
		}
		
		return $output;

	}
}
?>
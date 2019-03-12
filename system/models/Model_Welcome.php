<?php
namespace Models;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Model;

class Model_Welcome extends NSY_Model
{

	public function yourFunction()
	{
		$query = "SELECT * FROM tbl_users";
		$data = $this->mysql()->query($query)->style(FETCH_ASSOC)->fetch_all();
		$json_data = $this->fetch_json([
			"data" => $data
		]);

		return $json_data;
	}

}

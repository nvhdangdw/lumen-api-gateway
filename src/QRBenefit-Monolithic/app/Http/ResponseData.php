<?php

namespace App\Http;

class ResponseData
{

	public function __construct()
	{
		$this->time = round(microtime(true) * 1000);
	}

	public function success($data)
	{
		$result = array(
			"time" => $this->time,
			"status" => true,
			"message" => "",
			"data" => $data
		);
		return $result;
	}

	public function error($error, $statusCode = 400)
	{
		$result = array(
			"time" => $this->time,
			"status" => false,
			"message" => $error,
			"data" => []
		);
		return $result;
	}
}

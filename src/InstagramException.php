<?php
namespace Haridarshan\Instagram;

class InstagramException extends \Exception {
	/*
	* Get Exception type
	* @return string | nul;
	*/
	public function getType() {	
		$message = json_decode($this->message);
		return isset($message->Type) ? $message->Type : null;
	}
}

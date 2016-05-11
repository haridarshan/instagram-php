<?php
namespace Haridarshan\Instagram\Test;

use Haridarshan\Instagram\Instagram;

class InstagramTest extends \PHPUnit_Framework_TestCase {
	protected $instagram;
	
	protected function setup() {
		$this->instagram = new Instagram(CLIENT_ID, CLIENT_SECRET, CALLBACK_URL);	
	}
	
	public function testBuildClient(){
		$this->assertObjectHasAttribute('client_id', $this->instagram);		
		$this->assertObjectHasAttribute('client_secret', $this->instagram);		
		$this->assertObjectHasAttribute('callback_url', $this->instagram);
	}
	
	
}

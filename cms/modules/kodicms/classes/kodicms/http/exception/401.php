<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class KodiCMS_HTTP_Exception_401 extends Kohana_HTTP_Exception_401 
{

	/**
	* Generate a Response for the 401 Exception.
	* 
	* The user should be redirect to a login page.
	* 
	* @return Response
	*/
	public function get_response()
	{
		Flash::set('redirect', Request::current()->uri());
	
		$response = Response::factory()
			->status(401)
			->headers('Location', Route::url( 'user', array(
				'action' => 'login'
			)));
	
		return $response;
	}
}
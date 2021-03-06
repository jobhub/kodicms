<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_Scheduler extends Controller_System_Backend {

	public function before()
	{
		parent::before();
		
		$this->template->title = __('Scheduler');
		$this->breadcrumbs
			->add($this->template->title, $this->request->controller());
	}
	
	public function action_index()
	{
		Assets::css('fullcalendar', ADMIN_RESOURCES . 'libs/fullcalendar/fullcalendar.css', 'global');
		Assets::js('fullcalendar', ADMIN_RESOURCES . 'libs/fullcalendar/fullcalendar.min.js', 'jquery');
		
		$this->template->content = View::factory( 'scheduler/index' );
	}
}
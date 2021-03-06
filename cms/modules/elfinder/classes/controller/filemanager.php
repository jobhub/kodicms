<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_FileManager extends Controller_System_Backend {
	
	public function action_index()
	{
		$this->template->title = __('File manager');
		$this->breadcrumbs
			->add($this->template->title, $this->request->controller());

		Assets::css('elfinder', ADMIN_RESOURCES . 'libs/elfinder/css/elfinder.min.css');
		Assets::js('elfinder', ADMIN_RESOURCES . 'libs/elfinder/js/elfinder.min.js', 'global');
		Assets::js('elfinder.ru', ADMIN_RESOURCES . 'libs/elfinder/js/i18n/elfinder.ru.js', 'global');

		$this->template->content = View::factory('elfinder/filemanager');
	}
}
<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class KodiCMS_Controller_Front extends Controller_System_Controller
{
	/**
	 *
	 * @var Context 
	 */
	protected $_ctx = NULL;

	public function before()
	{
		parent::before();
		
		$this->_ctx =& Context::instance();

		$this->_ctx
			->request( $this->request )
			->response( $this->response );
		
		Assets::remove_js();
		Assets::remove_css();
	}

	public function action_index()
	{
		Observer::notify('frontpage_requested', array($this->request->uri()));
		
		$page = Model_Page_Front::find($this->request->uri());

		if ($page instanceof Model_Page_Front)
		{
			return $this->_render($page);
		}
		else
		{
			// Если включен поиск похожей страницы и она найдена, производим
			// редирект на найденую страницу
			if(Setting::get('find_similar') == 'yes')
			{
				$uri = Model_Page_Front::find_similar($this->request->uri());
				
				if($uri !== FALSE)
				{
					HTTP::redirect($uri, 301);
				}
			}
			
			Model_Page_Front::not_found();
		}
	}
	
	private function _render($page)
	{
		$this->_ctx->set_page($page);
		
		Observer::notify('frontpage_found', $page);

		// If page needs login, redirect to login
		if ($page->needs_login() == Model_Page::LOGIN_REQUIRED)
		{
			Observer::notify('frontpage_login_required', $page);

			if ( ! AuthUser::isLoggedIn())
			{
				Flash::set('redirect', $page->url());

				$this->redirect(Route::get('user')->uri(array( 
					'action' => 'login'
				) ));
			}
		}
		
		Block::run('PRE');
		
		$this->_ctx->build_crumbs();
		
		// Если установлен статус 404, то выводим страницу 404
		// Страницу 404 могут выкидывать также Виджеты
		if( Request::current()->is_initial() AND $this->response->status() == 404)
		{
			$this->_ctx = NULL;
			Model_Page_Front::not_found();
		}

		$html = (string) $page->render_layout();

		// Если пользователь Администраторо или девелопер, в конец шаблона 
		// добавляем View 'system/blocks/toolbar', в котором можно добавлять 
		// собственный HTML, например панель администратора
		if ( AuthUser::isLoggedIn() AND AuthUser::hasPermission(array(
			'administrator', 'developer'
		)))
		{
			$inject_html = (string) View::factory( 'system/blocks/toolbar' );
			
			// Insert system HTML before closed tag body
			$matches = preg_split('/(<\/body>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE); 
			
			if(count($matches) > 1)
			{
				/* assemble the HTML output back with the iframe code in it */
				$html = $matches[0] . $inject_html . $matches[1] . $matches[2];
			}
		}
		
		// Если окружение - PRODUCTION, то включить etag кеширование
		if( Kohana::$environment === Kohana::PRODUCTION )
		{
			$this->check_cache(sha1($html));
		}
		
		if($mime = $page->mime())
		{
			$this->response->headers('Content-Type',  $mime );
		}
		
		$this->response
			->body($html)
			->headers('last-modified', date('r', strtotime($page->updated_on)));			
			
	}
} // end class FrontController
<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Page_Pages extends Model_Widget_Decorator {
	
	protected $_data = array(
		'list_offset' => 0,
		'list_size' => 10
	);
	
	public $cache_tags = array('pages', 'page_parts', 'page_tags');
	
	public function on_page_load() 
	{
		$page = Model_Page_Front::findById($this->get_page_id());
		
		if( ! ($page instanceof Model_Page_Front) )
		{
			$this->_ctx->throw_404();
		}
	}
	
	public function load_template_data()
	{
		$pages = Model_Page_Sitemap::get();
		
		$select = array('-');
		foreach($pages->flatten() as $page)
		{
			$uri = !empty($page['uri']) ? $page['uri'] : '/';
			$select[$page['id']] = $page['title'] . ' (' . $uri . ')';
		}
		
		return array(
			'select' => $select
		);
	}
	
	public function set_values(array $data)
	{
		$data['list_offset'] = (int) $data['list_offset'];
		$data['list_size'] = (int) $data['list_size'];

		return parent::set_values($data);
	}
	
	public function get_page()
	{
		return $this->_ctx->get_page();
	}
	
	public function get_page_id()
	{
		if($this->page_id >= 1)
		{
			return $this->page_id;
		}
		else if($this->page_id == 0 AND ($page = $this->_ctx->get_page()) instanceof Model_Page_Front)
		{
			return $page->id;
		}

		return NULL;
	}
	
	public function fetch_data()
	{
		$page = Model_Page_Front::findById($this->get_page_id());
		
		$clause = array(
			'order_by' => array(array('page.created_on', 'desc'))
		);
		
		if($this->list_offset > 0)
		{
			$clause['offset'] = (int) $this->list_offset;
		}
		
		if($this->list_size > 0)
		{
			$clause['limit'] = (int) $this->list_size;
		}

		$pages = $page->children($clause);

		return array(
			'pages' => $pages
		);
	}
	
	public function get_cache_id()
	{
		return 'Widget::' . $this->id . '::' . $this->_ctx->get_page()->id;
	}
	
	public function clear_cache()
	{
		$this->clear_cache_by_tags();

		return $this;
	}
}
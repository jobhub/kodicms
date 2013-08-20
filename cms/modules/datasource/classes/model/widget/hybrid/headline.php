<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Hybrid_Headline extends Model_Widget_Hybrid {
	/**
	 *
	 * @var array 
	 */
	public $doc_fields = array();
	
	/**
	 *
	 * @var array 
	 */
	public $doc_fetched_widgets = array();
	
	/**
	 *
	 * @var array 
	 */
	public $doc_filter = array();
	
	/**
	 *
	 * @var array 
	 */
	public $doc_order = array();
	
	/**
	 *
	 * @var string 
	 */
	public $doc_uri = NULL;
	
	/**
	 *
	 * @var string 
	 */
	public $doc_id = 'id';

	/**
	 *
	 * @var integer 
	 */
	public $list_offset = 0;
	
	/**
	 *
	 * @var integer 
	 */
	public $list_size = 10;
	
	/**
	 *
	 * @var bool 
	 */
	public $only_published = TRUE;
	
	/**
	 *
	 * @var array 
	 */
	public $ids = array();
	
	/**
	 *
	 * @var array 
	 */
	protected $arrays = array();
	
	public $docs = NULL;

	/**
	 * 
	 * @param array $data
	 */
	public function set_values(array $data) 
	{
		$this->doc_fields = $this->doc_fetched_widgets = array();
		
		parent::set_values($data);

		$this->doc_order = Arr::get($data, 'doc_order', array());
		
		$this->list_offset = (int) Arr::get($data, 'list_offset');
		$this->list_size = (int) Arr::get($data, 'list_size');
		
		$this->only_sub = (bool) Arr::get($data, 'only_sub');
		$this->only_published = (bool) Arr::get($data, 'only_published');
		
		$this->doc_uri = Arr::get($data, 'doc_uri', $this->doc_uri);
		$this->doc_id = preg_replace('/[^A-Za-z,]+/', '', Arr::get($data, 'doc_id', $this->doc_id));
		
		$this->throw_404 = (bool) Arr::get($data, 'throw_404');
		$this->sort_by_rand = (bool) Arr::get($data, 'sort_by_rand');
		
		return $this;
	}
	
	public function set_ds_id($ds_id)
	{
		$this->ds_id = (int) $ds_id;
		return $this;
	}
	
	public function set_field($fields = array())
	{
		if(!is_array( $fields)) return;
		foreach($fields as $f)
		{
			if(isset($f['id']))
			{
				$this->doc_fields[] = (int) $f['id'];
			
				if(isset($f['fetcher']))
					$this->doc_fetched_widgets[(int) $f['id']] = (int) $f['fetcher'];
			}
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function options()
	{
		$datasources = Datasource_Data_Manager::get_all('hybrid');
		
		$options = array();
		foreach ($datasources as $value)
		{
			$options[$value['id']] = $value['name'];
		}
		
		return $options;
	}
	
	public function count_total()
	{
		return $this->get_total_documents();
	}

	/**
	 * 
	 * @return array
	 */
	public function fetch_data()
	{
		$this->get_documents();
		
		if(empty($this->docs) AND $this->throw_404)
		{
			$this->_ctx->throw_404();
		}
		
		return array(
			'docs' => $this->docs,
			'count' => count($this->docs)
		);
	}
	
	public function get_total_documents()
	{
		$agent = $this->get_agent();
		$query = $agent->get_query_props(array(), array(), array(), $this->doc_filter);
		
		if(is_array($this->ids) AND count($this->ids) > 0)
		{
			$query->where('d.id', 'in',  $this->ids);
		}
		
		if($this->only_published === TRUE)
		{
			$query->where('d.published', '=',  1);
		}
		
		return $query->select(array(DB::expr('COUNT(*)'),'total_docs'))
			->execute()
			->get('total_docs');
	}
	
	/**
	 * 
	 * @param integer $recurse
	 * @return array
	 */
	public function get_documents( $recurse = 3 )
	{
		if( $this->docs !== NULL ) return $this->docs;

		$result = array();
		
		$agent = $this->get_agent();

		if( ! $agent )
		{
			return $result;
		}
		
		$query = $this
			->_get_query();
		
		$ds_fields = $agent->get_fields();
		$fields = array();
		foreach ($this->doc_fields as $fid)
		{
			if(isset($ds_fields[$fid]))
			{
				$fields[$fid] = $ds_fields[$fid];
			}
		}

		$href_params = $this->_parse_doc_id();
		
		foreach ($query->execute() as $row)
		{
			$result[$row['id']] = array();
			$doc = & $result[$row['id']];
			
			$doc['id'] = $row['id'];
			$doc['header'] = $row['header'];
			
			foreach ($fields as $fid => $field)
			{
				$related_widget = NULL;
				
				$field_class = 'DataSource_Data_Hybrid_Field_' . $field['type'];
				$field_class_method = 'set_doc_field';
				if( class_exists($field_class) AND method_exists( $field_class, $field_class_method ))
				{
					$doc[$field['name']] = call_user_func_array($field_class.'::'.$field_class_method, array( $this, $field, $row, $fid, $recurse));
					continue;
				}
						
				switch($field['type']) {
					case DataSource_Data_Hybrid_Field::TYPE_DATASOURCE:
						array(
							'id' => $row[$fid]
						);
						break;
					default:
						$doc[$field['name']] = $row[$fid];
						
				}
			}
			
			$doc_params = array();
			foreach ($href_params as $field)
			{
				if(!isset($doc[$field]))
				{
					continue;
				}
				
				$doc_params[] = $doc[$field];
			}
			
			$doc['href'] = URL::site($this->doc_uri . implode( '/' , $doc_params ));
		}
		
		$this->docs = $result;
	}
	
	/**
	 * 
	 * @return array
	 */
	protected function _parse_doc_id()
	{
		return explode(',', $this->doc_id);
	}

	/**
	 * 
	 * @return Database_Query_Builder
	 */
	protected function _get_query()
	{
		$agent = $this->get_agent();
		$query = $agent->get_query_props($this->doc_fields, $this->doc_fetched_widgets, $this->doc_order, $this->doc_filter);
		
		if(is_array($this->ids) AND count($this->ids) > 0)
		{
			$query->where('d.id', 'in',  $this->ids);
		}
		
		if($this->only_published === TRUE)
		{
			$query->where('d.published', '=',  1);
		}

		$query->limit($this->list_size);
		$query->offset($this->list_offset);
		
		return $query;
	}
	
	public function get_cache_id()
	{
		return 'Widget::' 
			. $this->type . '::' 
			. $this->id . '::' 
			. $this->list_offset . '::' 
			. $this->list_size;
	}
}
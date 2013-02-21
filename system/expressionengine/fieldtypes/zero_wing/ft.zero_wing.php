<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2003 - 2012, EllisLab, Inc.
 * @license		http://ellislab.com/expressionengine/user-guide/license.html
 * @link		http://ellislab.com
 * @since		Version 2.0
 * @filesource
 */

// --------------------------------------------------------------------

/**
 * ExpressionEngine Relationship Fieldtype Class
 *
 * @package		ExpressionEngine
 * @subpackage	Fieldtypes
 * @category	Fieldtypes
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */
class Zero_wing_ft extends EE_Fieldtype {

	public $info = array(
		'name'		=> 'ZeroWing',
		'version'	=> '1.0'
	);
	
	public $has_array_data = FALSE;

	private $_table = 'zero_wing';

	/**
	 * Validate Field
	 *
	 * @todo TODO check if ids are valid according to the settings.
	 *
	 * @param	field data
	 * @return	bool valid
	 */
	public function validate($data)
	{
		return TRUE;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Save Field
	 *
	 * In our case the actual field entry will be blank, so we'll simply
	 * cache some data for the post_save method.
	 *
	 * @param	field data
	 * @return	column data
	 */
	public function save($data)
	{
		$this->EE->session->set_cache(__CLASS__, $this->field_name, $data);
		return '';
	}

	// --------------------------------------------------------------------
	
	/**
	 * Post field save is where we do the actual works since we store
	 * data in our own table based on the entry_id, which does not exist
	 * before saving.
	 *
	 * @param	the return value of save()
	 * @return	void
	 */
	public function post_save($data)
	{
		$field_id = $this->field_id;
		$entry_id = $this->settings['entry_id'];
		$data = $this->EE->session->cache(__CLASS__, $this->field_name);

		// clear old stuff
		$this->EE->db
			->where('parent_id', $entry_id)
			->where('field_id', $field_id)
			->delete($this->_table);

		// insert new stuff
		$ships = array();

		foreach ($data as $child_id)
		{
			$ships[] = array(
				'parent_id'	=> $entry_id,
				'child_id'	=> $child_id,
				'field_id'	=> $field_id
			);
		}

		if (count($ships))
		{
			$this->EE->db->insert_batch($this->_table, $ships);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Called when entries are deleted
	 *
	 * @access	public
	 * @param	array of entry ids to delete
	 */
	public function delete($ids)
	{
		$this->EE->db
			->where_in('parent_id', $ids)
			->or_where_in('child_id', $ids)
			->delete($this->_table);
	}

	// --------------------------------------------------------------------

	/**
	 * Display the field on the publish page
	 *
	 * Show the field to the user. In this case that means either
	 * showing a dropdown for single selects or our custom multiselect
	 * interface.
	 *
	 * @param	stored data
	 * @return	interface string
	 */
	public function display_field($data)
	{
		$field_name = $this->field_name;
		$entry_id = $this->EE->input->get('entry_id');

		$entries = array();
		$selected = array();

		if ($entry_id)
		{
			$related = $this->EE->db
				->select('child_id')
				->where('parent_id', $entry_id)
				->where('field_id', $this->field_id)
				->get($this->_table)
				->result_array();

			$selected = array_map('array_pop', $related);
		}

		$limit_channels = $this->settings['channels'];
		$limit_categories = $this->settings['categories'];
		$limit_statuses = $this->settings['statuses'];
		$limit_authors = $this->settings['authors'];
		$limit = $this->settings['limit'];

		// @todo - statuses, authors, categories, limit
		$this->EE->db
			->select('channel_titles.entry_id, channel_titles.title')
			->order_by($this->settings['order_field'], $this->settings['order_dir']);

		if ($limit)
		{
			$this->EE->db->limit($limit);
		}

		if (count($limit_channels))
		{
			$this->EE->db->where_in('channel_titles.channel_id', $limit_channels);
		}

		if (count($limit_categories))
		{
			$this->EE->db->from('category_posts');
			$this->EE->db->where('exp_channel_titles.entry_id = exp_category_posts.entry_id', NULL, FALSE); // todo ick
			$this->EE->db->where_in('category_posts.cat_id', $limit_categories);
		}

		if (count($limit_statuses))
		{
			// @todo TODO fix them
		}

		if (count($limit_authors))
		{
			// @todo TODO ick
			$groups = preg_filter('/^g_/', '', $limit_authors);
			$members = preg_filter('/^m_/', '', $limit_authors);

			$fn = 'where_in';

			if (count($members))
			{
				$this->EE->db->where_in('channel_titles.author_id', $members);	
				$fn = 'or_where_in';
			}

			if (count($groups))
			{
				$this->EE->db->join('members', 'members.member_id = channel_titles.author_id');
				$this->EE->db->$fn('members.group_id', $groups);
			}
		}

		if ($entry_id)
		{
			$this->EE->db->where('channel_titles.entry_id !=', $entry_id);
		}

		if (count($selected))
		{
			$this->EE->db->or_where_in('channel_titles.entry_id', $selected);
		}

		$entries = $this->EE->db->get('channel_titles')->result_array();

		if ($this->settings['allow_multiple'] == 0)
		{
			return form_dropdown($field_name, $entries, key($selected));
		}

//		$entries = array_merge($entries, $entries, $entries, $entries, $entries); // 90
//		$entries = array_merge($entries, $entries, $entries, $entries, $entries); // 450
//		$entries = array_merge($entries, $entries, $entries, $entries, $entries); // 2250

		$str = '';
		$str .= $this->_active_div($entries, $selected, $field_name);
		$str .= $this->_multi_div($entries, $selected, $field_name);

		// The active section

		if (count($entries))
		{
			$js = $this->_publish_js();
			$js .= "EE.setup_multi_field('#${field_name}');";
			$this->EE->javascript->output($js);
		}

		return $str;
	}

	public function _multi_div($entries, $selected, $field_name)
	{
		$class = 'class="multiselect ';
		$class .= count($entries) ? 'force-scroll' : 'empty';
		$class .= '"';

		$str = '<div class="multiselect-filter js_show">';
		$str .= form_input('', '', 'placeholder="'.lang('rel_ft_filter_by_title').'" id="'.$field_name.'-filter"');
		$str .= '</div>';

		$str .= '<div id="'.$field_name.'" '.$class.'>';

		$str .= '<ul>';

		foreach ($entries as $row)
		{
			$checked = in_array($row['entry_id'], $selected);

			$str .= '<li'.($checked ? ' class="selected"' : '').'><label>';
			$str .= form_checkbox($field_name.'[]', $row['entry_id'], $checked, 'class="js_hide"');
			$str .= $row['title'].'</label></li>';
		}

		if ( ! count($entries))
		{
			$str .= '<li>'.lang('rel_ft_no_entries').'</li>';
		}

		$str .= '</ul>';
		$str .= '</div>';

		return $str;
	}

	public function _active_div($entries, $selected, $field_name)
	{
		$class = 'class="multiselect-active ';
		$class .= count($entries) ? 'force-scroll' : 'empty';
		$class .= '"';

		// underscore.js template string
		$active_template = '<li><span class="reorder-handle">&nbsp;</span>';
		$active_template .= '<%= title %>';
		$active_template .= '<span class="remove-item">&times;</span></li>';

		$str = '<div id="'.$field_name.'-active" '.$class.' data-template="'.form_prep($active_template).'">';
		$str .= '<ul>';

		$str .= '</ul>';
		$str .= '</div>';

		return $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Show the tag on the frontend
	 *
	 * @param	column data
	 * @param	tag parameters
	 * @param	tag pair contents
	 * @return	parsed output
	 */	
	public function replace_tag($data, $params = '', $tagdata = '')
	{
		if ($tagdata)
		{
			return $tagdata;
		}

		return $data;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Display the settings page
	 *
	 * This basically just constructs a table of stuff. Pretty simple.
	 *
	 * @param	array of previously saved settings
	 * @return	string
	 */	
	public function display_settings($data)
	{
		$this->EE->lang->loadfile('fieldtypes');

		$form = $this->_form();
		$form->populate($data);

		$this->EE->table->set_heading(array(
			'data' => lang('rel_ft_options'),
			'colspan' => 2
		));

		$this->_row(
			'<strong>'.lang('rel_ft_configure').'</strong><br><i class="instruction_text">'.lang('rel_ft_configure_subtext').'</i>'
		);

		$this->_row(
			lang('rel_ft_channels'),
			$form->multiselect('channels', 'style="min-width: 225px; height: 140px;"'),
			'top'
		);
		
		$this->_row(
			lang('rel_ft_include'),
			'<label>'.$form->checkbox('expired').' '.lang('rel_ft_include_expired').'</label>'.
				NBS.NBS.' <label>'.$form->checkbox('future').' '.lang('rel_ft_include_future').'</label>'
		);
		$this->_row(
			lang('rel_ft_categories'),
			$form->multiselect('categories', 'style="min-width: 225px; height: 140px;"'),
			'top'
		);
		
		$this->_row(
			lang('rel_ft_authors'),
			$form->multiselect('authors', 'style="min-width: 225px; height: 57px;"'),
			'top'
		);
		$this->_row(
			lang('rel_ft_statuses'),
			$form->multiselect('statuses', 'style="min-width: 225px; height: 43px;"'),
			'top'
		);
		$this->_row(
			lang('rel_ft_limit_left'),
			$form->input('limit', 'class="center" style="width: 55px;"').NBS.
				' <strong>'.lang('rel_ft_limit_right').'</strong> <i class="instruction_text">('.lang('rel_ft_limit_subtext').')</i>'
		);
		$this->_row(
			lang('rel_ft_order'),
			$form->dropdown('order_field').' &nbsp; <strong>'.lang('rel_ft_order_in').'</strong> &nbsp; '.$form->dropdown('order_dir')
		);
		$this->_row(
			lang('rel_ft_allow_multi'),
			'<label>'.$form->checkbox('allow_multiple').' '.lang('yes').' </label> <i class="instruction_text">('.lang('rel_ft_allow_multi_subtext').')</i>'
		);

		return $this->EE->table->generate();
	}

	// --------------------------------------------------------------------

	/**
	 * Table row helper
	 *
	 * Help simplify the form building and enforces a strict layout. If
	 * you think this table needs to look different, go bug James.
	 *
	 * @param	left cell content
	 * @param	right cell content
	 * @param	vertical alignment of left column
	 *
	 * @return	void - adds a row to the EE table class
	 */	
	protected function _row($cell1, $cell2 = '', $valign = 'center')
	{
		if ( ! $cell2)
		{
			$this->EE->table->add_row(
				array('data' => $cell1, 'colspan' => 2)
			);
		}
		else
		{
			$this->EE->table->add_row(
				array('data' => '<strong>'.$cell1.'</strong>', 'width' => '170px', 'valign' => $valign),
				array('data' => $cell2, 'class' => 'id')
			);
		}
	}

	public function save_settings($data)
	{
		$form = $this->_form();
		$form->populate($data);

		$save = $form->values();

		foreach ($save as $field => $value)
		{
			if (is_array($value) && count($value))
			{
				if (isset($value['--']))
				{
					$save[$field] = array();
				}
			}
		}

		return $save;
	}

	// --------------------------------------------------------------------

	/**
	 * Setup the form helper
	 *
	 * Assigns blank data, default data, and all the form options.
	 *
	 * @param	form prefix
	 * @return	Object<Relationship_settings_form>
	 */	
	protected function _form($prefix = 'zero_wing')
	{
		$this->EE->load->library('Relationships_ft_cp');
		$util = $this->EE->relationships_ft_cp;

		$field_empty_values = array(
			'channels'		=> array(),
			'expired'		=> 0,
			'future'		=> 0,
			'categories'	=> array(),
			'authors'		=> array(),
			'statuses'		=> array(),
			'limit'			=> 100,
			'order_field'	=> 'title',
			'order_dir'		=> 'asc',
			'allow_multiple'	=> 0
		);

		$field_options = array(
			'channels' 	  => $util->all_channels(),
			'categories'  => $util->all_categories(),
			'authors'	  => $util->all_authors(),
			'statuses'	  => $util->all_statuses(),
			'order_field' => $util->all_order_options(),
			'order_dir'	  => $util->all_order_directions()
		);

		// any default values that are not the empty ones
		$default_values = array(
			'allow_multiple' => 1
		);

		$form = $util->form($field_empty_values, $prefix);
		$form->options($field_options);
		$form->populate($default_values);

		return $form;
	}

	// --------------------------------------------------------------------

	/**
	 * Javascript
	 *
	 * Create the required javascript
	 *
	 * @return	void
	 */	
	protected function _publish_js()
	{
		if ($this->EE->session->cache(__CLASS__, 'js_loaded') === TRUE)
		{
			return '';
		}

		$js = file_get_contents(PATH_FT.'zero_wing/javascript/cp.js');

		$this->EE->session->cache(__CLASS__, 'js_loaded', TRUE);
		return $js;
	}

	// --------------------------------------------------------------------

	/**
	 * Create our table on install
	 *
	 * @return	void
	 */	
	public function install()
	{
		$this->EE->load->dbforge();

		$fields = array(
			'relationship_id' => array(
				'type'				=> 'int',
				'constraint'		=> 10,
				'unsigned'			=> TRUE,
				'auto_increment'	=> TRUE
			),
			'parent_id'	=> array(
				'type'				=> 'int',
				'constraint'		=> 10,
				'unsigned'			=> TRUE
			),
			'child_id'  => array(
				'type'				=> 'int',
				'constraint'		=> 10,
				'unsigned'			=> TRUE
			),
			'field_id'  => array(
				'type'				=> 'int',
				'constraint'		=> 10,
				'unsigned'			=> TRUE
			)
		);

		$this->EE->dbforge->add_field($fields);

		// Worthless primary key
		$this->EE->dbforge->add_key('relationship_id', TRUE);

		// Keyed table is keyed
		$this->EE->dbforge->add_key('parent_id');
		$this->EE->dbforge->add_key('child_id');
		$this->EE->dbforge->add_key('field_id');

		$this->EE->dbforge->create_table($this->_table);
	}

	// --------------------------------------------------------------------

	/**
	 * Drop the table
	 *
	 * @return	void
	 */	
	public function uninstall()
	{
		$this->EE->load->dbforge();
		$this->EE->dbforge->drop_table($this->_table);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Settings Modify Column
	 *
	 * @param	array
	 *		field_id
	 *		ee_action - delete OR add
	 * @return	array
	 */
	public function settings_modify_column($data)
	{
		if ($data['ee_action'] == 'delete')
		{
			// remove relationships
			$this->EE->db
				->where('field_id', $data['field_id'])
				->delete($this->_table);
		}

		// pretty much a dummy field. Here just for consistency's sake
		// and in case we decide to store something in it.

		$fields['field_id_'.$data['field_id']] = array(
			'type' => 'VARCHAR',
			'constraint' => 8
		);

		return $fields;
	}
}

// END Zero_wing_ft class

/* End of file ft.zero_wing.php */
/* Location: ./system/expressionengine/fieldtypes/ft.zero_wing.php */
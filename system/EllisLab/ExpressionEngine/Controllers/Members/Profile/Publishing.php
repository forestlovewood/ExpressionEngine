<?php

namespace EllisLab\ExpressionEngine\Controllers\Members\Profile;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use CP_Controller;

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2003 - 2014, EllisLab, Inc.
 * @license		http://ellislab.com/expressionengine/user-guide/license.html
 * @link		http://ellislab.com
 * @since		Version 3.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine CP Member Profile Publishing Settings Class
 *
 * @package		ExpressionEngine
 * @subpackage	Control Panel
 * @category	Control Panel
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */
class Publishing extends Profile {

	private $base_url = 'members/profile/publishing';

	/**
	 * Publishing Settings
	 */
	public function index()
	{
		$this->base_url = cp_url($this->base_url, $this->query_string);


		$vars['sections'] = array(
			array(
				array(
					'title' => 'in_authorlist',
					'desc' => 'in_authorlist_desc',
					'fields' => array(
						'in_authorlist' => array(
							'type' => 'inline_radio',
							'choices' => array(
								'y' => 'yes',
								'n' => 'no'
							),
							'value' => $this->member->in_authorlist
						)
					)
				)
			),
			'rte_settings' => array(
				array(
					'title' => 'rte_enabled',
					'desc' => 'rte_enabled_desc',
					'fields' => array(
						'rte_enabled' => array(
							'type' => 'inline_radio',
							'choices' => array(
								'y' => 'enable',
								'n' => 'disable'
							),
							'value' => $this->member->rte_enabled
						)
					)
				),
				array(
					'title' => 'rte_toolset',
					'desc' => 'rte_toolset_desc',
					'fields' => array(
						'rte_toolset_id' => array(
							'type' => 'dropdown',
							'choices' => array(
								0 => 'default'
							),
							'value' => $this->member->rte_toolset_id
						),
					)
				)
			)
		);

		if( ! empty($_POST))
		{
			if ($this->saveSettings($vars['sections']))
			{
				ee()->view->set_message('success', lang('member_updated'), lang('member_updated_desc'), TRUE);
				ee()->functions->redirect($base_url);
			}
		}

		ee()->view->base_url = $this->base_url;
		ee()->view->cp_page_title = lang('email_settings');
		ee()->view->save_btn_text = 'btn_save_settings';
		ee()->view->save_btn_text_working = 'btn_saving';
		ee()->cp->render('settings/form', $vars);
	}
}
// END CLASS

/* End of file Publishing.php */
/* Location: ./system/expressionengine/controllers/cp/Members/Profile/Publishing.php */
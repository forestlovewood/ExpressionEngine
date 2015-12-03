<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2003 - 2015, EllisLab, Inc.
 * @license		https://ellislab.com/expressionengine/user-guide/license.html
 * @link		http://ellislab.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine New Relic Class
 *
 * @package		ExpressionEngine
 * @subpackage	Core
 * @category	Core
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */

class Newrelic {

	/**
	 * Set the application name
	 *
	 * @access	public
	 * @return	void
	 */
	public function set_appname()
	{
		$appname = (string) ee()->config->item('newrelic_app_name');

		// -------------------------------------------
		//	Hidden Configuration Variable
		//	- newrelic_app_name => Change application name that appears in
		//	  the New Relic dashboard
		// -------------------------------------------*/
		if ( ! empty($appname))
		{
			$appname .= ' - ';
		}

		// -------------------------------------------
		//	Hidden Configuration Variable
		//	- newrelic_include_version_number => Whether or not to include the version
		//    number with the application name
		// -------------------------------------------*/
		$version = (ee()->config->item('newrelic_include_version_number') == 'y') ? ' v'.APP_VER : '';
		newrelic_set_appname($appname.APP_NAME.$version);
	}

	// --------------------------------------------------------------------

	/**
	 * Give New Relic a name for this transaction
	 *
	 * @access	public
	 * @return	void
	 */
	public function name_transaction($template_group, $template_name)
	{
		$transaction_name = $template_group.'/'.$template_name;

		try {
			if (ee()->template_router->match(ee()->uri))
			{
				newrelic_add_custom_parameter('route', ee()->uri->uri_string);
			}
		} catch (Exception $e) {
			// No template route
		}

		// Append site label if MSM is enabled to easily differentiate
		// between similar requests
		if (ee()->config->item('multiple_sites_enabled') == 'y')
		{
			$transaction_name .= ' - ' . ee()->config->item('site_label');
		}

		newrelic_name_transaction($transaction_name);
	}

	// --------------------------------------------------------------------

	/**
	 * Prevent the New Relic PHP extension from inserting its JavaScript
	 * for this transaction
	 *
	 * @access	public
	 * @return	void
	 */
	public function disable_autorum()
	{
		newrelic_disable_autorum();
	}

	// --------------------------------------------------------------------
}
// END CLASS

/* End of file Newrelic.php */
/* Location: ./system/expressionengine/libraries/Newrelic.php */

<?php
namespace EllisLab\ExpressionEngine\Model\Status;

use EllisLab\ExpressionEngine\Service\Model\Model;

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
 * ExpressionEngine Status Model
 *
 * @package		ExpressionEngine
 * @subpackage	Status
 * @category	Model
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */
class Status extends Model {
	protected static $_primary_key = 'status_id';
	protected static $_gateway_names = array('StatusGateway');
	protected static $_key_map = array(
		'status_id' => 'StatusGateway',
		'site_id' => 'StatusGateway',
		'group_id' => 'StatusGateway'
	);

	protected static $_relationships = array(
		'StatusGroup' => array(
			'type' => 'many_to_one'
		)
	);

	public function getStatusGroup()
	{
		return $this->getRelated('StatusGroup');
	}

	public function setStatusGroup(StatusGroup $status_group)
	{
		return $this->setRelated('StatusGroup', $status_group);
	}


	public $status_id;
	public $site_id;
	public $group_id;
	public $status;
	public $status_order;
	public $highlight;
}
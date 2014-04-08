<?php
namespace EllisLab\ExpressionEngine\Model\Template;

use EllisLab\ExpressionEngine\Model\Model;

class GlobalVariable extends Model
{
	// Meta data
	protected static $_primary_key = 'variable_id';
	protected static $_gateway_names = array('GlobalVariableGateway');
	protected static $_key_map = array(
		'variable_id' => 'GlobalVariableGateway',
		'site_id' => 'GlobalVariableGateway'
	);

	// Properties
	protected $variable_id;
	protected $site_id;
	protected $variable_name;
	protected $variable_data;

	public function getSite()
	{
		return $this->manyToOne('Site', 'Site', 'site_id', 'site_id');
	}

	public function setSite(Site $site)
	{
		$this->setRelated('Site', $site);
		$this->site_id = $site->site_id;
		return $this;
	}

}
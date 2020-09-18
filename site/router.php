<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Dnabold
 * @author     Trey Richards <treyj45@gmail.com>
 * @copyright  2020 Trey Richards
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Categories\Categories;

/**
 * Class DnaboldRouter
 *
 */
class DnaboldRouter extends RouterView
{
	private $noIDs;
	public function __construct($app = null, $menu = null)
	{
		$params = Factory::getApplication()->getParams('com_dnabold');
		$this->noIDs = (bool) $params->get('sef_ids');
		
		$dnabolds = new RouterViewConfiguration('dnabolds');
		$this->registerView($dnabolds);
			$dnabold = new RouterViewConfiguration('dnabold');
			$dnabold->setKey('id')->setParent($dnabolds);
			$this->registerView($dnabold);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));

		if ($params->get('sef_advanced', 0))
		{
			$this->attachRule(new StandardRules($this));
			$this->attachRule(new NomenuRules($this));
		}
		else
		{
			JLoader::register('DnaboldRulesLegacy', __DIR__ . '/helpers/legacyrouter.php');
			JLoader::register('DnaboldHelpersDnabold', __DIR__ . '/helpers/dnabold.php');
			$this->attachRule(new DnaboldRulesLegacy($this));
		}
	}


	
		/**
		 * Method to get the segment(s) for an dnabold
		 *
		 * @param   string  $id     ID of the dnabold to retrieve the segments for
		 * @param   array   $query  The request that is built right now
		 *
		 * @return  array|string  The segments of this item
		 */
		public function getDnaboldSegment($id, $query)
		{
			return array((int) $id => $id);
		}

	
		/**
		 * Method to get the segment(s) for an dnabold
		 *
		 * @param   string  $segment  Segment of the dnabold to retrieve the ID for
		 * @param   array   $query    The request that is parsed right now
		 *
		 * @return  mixed   The id of this item or false
		 */
		public function getDnaboldId($segment, $query)
		{
			return (int) $segment;
		}
}

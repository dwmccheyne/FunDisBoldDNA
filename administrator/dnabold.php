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

use \Joomla\CMS\MVC\Controller\BaseController;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_dnabold'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Dnabold', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('DnaboldHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'dnabold.php');

$controller = BaseController::getInstance('Dnabold');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();

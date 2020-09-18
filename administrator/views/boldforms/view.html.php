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

jimport('joomla.application.component.view');

use \Joomla\CMS\Language\Text;

/**
 * View class for a list of Dnabold.
 *
 * @since  1.6
 */
class DnaboldViewBoldForms extends \Joomla\CMS\MVC\View\HtmlView
{
	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	function display($tpl = null)
	{
		if (!$this->form = $this->get('form'))
		{
			echo "Can't load form<br>";
			return;
		}
		// Get data from the model
		$this->result    = $this->get('Success');
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		DnaboldHelper::addSubmenu('boldforms');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();

		// Display the template
		parent::display($tpl);
	}
		protected function addToolBar()
	{
		JToolbarHelper::title(JText::_('COM_DNABOLD_TITLE_BOLDFORMS'));
		JToolbarHelper::addNew('helloworld.add');
		JToolbarHelper::editList('helloworld.edit');
		JToolbarHelper::deleteList('', 'helloworlds.delete');
	}
}

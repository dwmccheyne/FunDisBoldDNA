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


use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Language\Text;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'administrator/components/com_dnabold/assets/css/dnabold.css');
$document->addStyleSheet(Uri::root() . 'media/com_dnabold/css/list.css');

$user      = Factory::getUser();



?>

<form action="<?php echo Route::_('index.php?option=com_dnabold&view=dnabolds'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			
				<?php echo $this->result; ?>  
</div>

</form>

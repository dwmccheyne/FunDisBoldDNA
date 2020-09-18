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
use \Joomla\CMS\Language\Text;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('formbehavior.chosen', 'select');


$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');


// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_dnabold/css/list.css');
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
	<div id="sortables">
	Sort:
		<span class="sort-label"><?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_ID', 'a.id', $listDirn, $listOrder); ?> </span>
		<span class="sort-label"><?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_SCIENTIFIC_NAME', 'a.scientific_name', $listDirn, $listOrder); ?></span>
		<span class="sort-label"><?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_GROUP_ID', 'a.group_id', $listDirn, $listOrder); ?></span>
	</div>
        <div class="table-responsive">
	<table class="table table-striped" id="dnaboldList">
		<thead>
		<tr>


							<th class=''>
<!-- 				<?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_RECORD_ID', 'a.record_id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_SCIENTIFIC_NAME', 'a.scientific_name', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_BOLD_ID', 'a.bold_id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_GROUP_ID', 'a.group_id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_STEP', 'a.step', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_GENBANK_ID', 'a.genbank_id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_MYCOMAP_ID', 'a.mycomap_id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_FUNGARIUM_URL', 'a.fungarium_url', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_MYCOPORTAL_ID', 'a.mycoportal_id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DNABOLD_DNABOLDS_COVER_IMAGE', 'a.cover_image', $listDirn, $listOrder); ?> -->
				</th>


		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>


			
			<tr class="row<?php echo $i % 2; ?>">

		<td width="33%">
			<a href="<?php echo $item->cover_image; ?>"><img src="<?php echo $item->cover_image; ?>" alt="<?php echo $item->scientific_name; ?>"/></a>
		</td>
		<td>	
			<h3><?php echo $item->scientific_name; ?> -- <?php echo $item->record_id; ?> </h3>
				<br />
				 <b>Initial observation:</b>
				<?php if(stripos($item->record_id, "MO") !== false ){ ?>
				 <a href="https://mushroomobserver.org/<?php echo (int) filter_var($item->record_id, FILTER_SANITIZE_NUMBER_INT); ?>"><?php echo $item->record_id; ?></a>
			
				<?php } elseif(stripos($item->record_id, "iNat") !== false){ ?>
					 <a href="https://www.inaturalist.org/observations/<?php echo (int) filter_var($item->record_id, FILTER_SANITIZE_NUMBER_INT); ?>"><?php echo $item->record_id; ?></a>

				<?php } ?>
	 			<br />

				<?php if($item->bold_id): ?>
				 <b>Bold DNA:</b>
				 <a href="https://boldsystems.org/index.php/Public_RecordView?processid=<?php echo $item->bold_id; ?>"><?php echo $item->bold_id; ?></a>
				 <br />
				<?php endif; ?>


				<?php if($item->group_id): ?>
				 <b>Group Name:</b>
				 <?php echo $item->group_id; ?><br />
				<?php endif; ?>


				<?php if($item->genbank_id): ?>
				 <b>GenBank:</b>
				 <a href="https://www.ncbi.nlm.nih.gov/nuccore/<?php echo $item->genbank_id; ?>"><?php echo $item->genbank_id; ?></a>
				 <br />
				<?php endif; ?>


		</td>


			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
        </div>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

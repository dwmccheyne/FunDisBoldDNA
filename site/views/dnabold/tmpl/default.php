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


?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_DNABOLD_FORM_LBL_DNABOLD_RECORD_ID'); ?></th>
			<td><?php echo $this->item->record_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_DNABOLD_FORM_LBL_DNABOLD_SCIENTIFIC_NAME'); ?></th>
			<td><?php echo $this->item->scientific_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_DNABOLD_FORM_LBL_DNABOLD_BOLD_ID'); ?></th>
			<td><?php echo $this->item->bold_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_DNABOLD_FORM_LBL_DNABOLD_GROUP_ID'); ?></th>
			<td><?php echo $this->item->group_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_DNABOLD_FORM_LBL_DNABOLD_STEP'); ?></th>
			<td><?php echo $this->item->step; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_DNABOLD_FORM_LBL_DNABOLD_GENBANK_ID'); ?></th>
			<td><?php echo $this->item->genbank_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_DNABOLD_FORM_LBL_DNABOLD_MYCOMAP_ID'); ?></th>
			<td><?php echo $this->item->mycomap_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_DNABOLD_FORM_LBL_DNABOLD_FUNGARIUM_URL'); ?></th>
			<td><?php echo $this->item->fungarium_url; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_DNABOLD_FORM_LBL_DNABOLD_MYCOPORTAL_ID'); ?></th>
			<td><?php echo $this->item->mycoportal_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_DNABOLD_FORM_LBL_DNABOLD_COVER_IMAGE'); ?></th>
			<td><?php echo $this->item->cover_image; ?></td>
		</tr>

	</table>

</div>


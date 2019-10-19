<?php
/**
 * @version    CVS: 1.0.1
 * @package    Com_Foydalanuvchilar
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_foydalanuvchilar.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_foydalanuvchilar' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_FOYDALANUVCHILAR_FORM_LBL_FOYDALANUVCHI_USER_ID'); ?></th>
			<td><?php echo $this->item->user_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_FOYDALANUVCHILAR_FORM_LBL_FOYDALANUVCHI_MFO_FILIAL'); ?></th>
			<td><?php echo $this->item->mfo_filial; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_FOYDALANUVCHILAR_FORM_LBL_FOYDALANUVCHI_DEPARTAMENT'); ?></th>
			<td><?php echo $this->item->departament; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_FOYDALANUVCHILAR_FORM_LBL_FOYDALANUVCHI_MINTAQAVIY_FILIAL'); ?></th>
			<td><?php echo $this->item->mintaqaviy_filial; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_FOYDALANUVCHILAR_FORM_LBL_FOYDALANUVCHI_ICHKI_NAZORAT'); ?></th>
			<td><?php echo $this->item->ichki_nazorat; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_FOYDALANUVCHILAR_FORM_LBL_FOYDALANUVCHI_ICHKI_AUDIT'); ?></th>
			<td><?php echo $this->item->ichki_audit; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_FOYDALANUVCHILAR_FORM_LBL_FOYDALANUVCHI_FILIAL'); ?></th>
			<td><?php echo $this->item->filial; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_FOYDALANUVCHILAR_FORM_LBL_FOYDALANUVCHI_BARCHA_SOHA'); ?></th>
			<td><?php echo $this->item->barcha_soha; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_FOYDALANUVCHILAR_FORM_LBL_FOYDALANUVCHI_BOSH_BANK'); ?></th>
			<td><?php echo $this->item->bosh_bank; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_foydalanuvchilar&task=foydalanuvchi.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_FOYDALANUVCHILAR_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_foydalanuvchilar.foydalanuvchi.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_FOYDALANUVCHILAR_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_FOYDALANUVCHILAR_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_FOYDALANUVCHILAR_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_foydalanuvchilar&task=foydalanuvchi.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_FOYDALANUVCHILAR_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>
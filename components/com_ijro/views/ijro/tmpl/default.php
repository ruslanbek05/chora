<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Ijro
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_ijro.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_ijro' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_IJRO_FORM_LBL_IJRO_BAJARILGAN_ISH'); ?></th>
			<td><?php echo nl2br($this->item->bajarilgan_ish); ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_IJRO_FORM_LBL_IJRO_TUGRILANGANLIGI'); ?></th>
			<td><?php echo $this->item->tugrilanganligi; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_IJRO_FORM_LBL_IJRO_CHORA_ID'); ?></th>
			<td><?php echo $this->item->chora_id; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_ijro&task=ijro.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_IJRO_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_ijro.ijro.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_IJRO_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_IJRO_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_IJRO_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_ijro&task=ijro.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_IJRO_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>
<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Chora
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
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
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_chora') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'chora_tadbirlarform.xml');
$canEdit    = $user->authorise('core.edit', 'com_chora') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'chora_tadbirlarform.xml');
$canCheckin = $user->authorise('core.manage', 'com_chora');
$canChange  = $user->authorise('core.edit.state', 'com_chora');
$canDelete  = $user->authorise('core.delete', 'com_chora');
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
	<table class="table table-striped" id="chora_tadbirlarList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				<th width="5%">
	<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
</th>
			<?php endif; ?>

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CHORA_CHORA_TADBIRLARS_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CHORA_CHORA_TADBIRLARS_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CHORA_CHORA_TADBIRLARS_MAZMUNI', 'a.mazmuni', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CHORA_CHORA_TADBIRLARS_VAZIFA', 'a.vazifa', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CHORA_CHORA_TADBIRLARS_MUDDATI', 'a.muddati', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CHORA_CHORA_TADBIRLARS_MASUL', 'a.masul', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CHORA_CHORA_TADBIRLARS_NAZORAT', 'a.nazorat', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CHORA_CHORA_TADBIRLARS_TUGRILANDI_FILIAL', 'a.tugrilandi_filial', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CHORA_CHORA_TADBIRLARS_TUGRILANDI_BOSH_BANK', 'a.tugrilandi_bosh_bank', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CHORA_CHORA_TADBIRLARS_TUGRILANDI_ICHKI_NAZORAT', 'a.tugrilandi_ichki_nazorat', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CHORA_CHORA_TADBIRLARS_TUGRILANDI_ICHKI_AUDIT', 'a.tugrilandi_ichki_audit', $listDirn, $listOrder); ?>
				</th>


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_CHORA_CHORA_TADBIRLARS_ACTIONS'); ?>
				</th>
				<?php endif; ?>

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
		<?php foreach ($this->items as $i => $item) : 
		//print_r($item);
		
		/*
		if ($item->id == 1){
			continue;
		}*/
		
		
		
		
		
		
		?>
			<?php $canEdit = $user->authorise('core.edit', 'com_chora'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_chora')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					<td class="center">
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_chora&task=chora_tadbirlar.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
	<?php if ($item->state == 1): ?>
		<i class="icon-publish"></i>
	<?php else: ?>
		<i class="icon-unpublish"></i>
	<?php endif; ?>
	</a>
	</br>
	<a href="<?php echo JRoute::_('index.php?option=com_ijro&chora_id=' . $item->id, false, 2); ?>">Кўрилган чоралар</a>
</td>
				<?php endif; ?>

								<td>

					<?php echo $item->id; ?>
				</td>
				<td>

							<?php echo JFactory::getUser($item->created_by)->name; ?>				</td>
				<td>

					<?php echo $item->mazmuni; ?>
				</td>
				<td>

					<?php echo $item->vazifa; ?>
				</td>
				<td>

					<?php echo $item->muddati; ?>
				</td>
				<td>

					<?php echo $item->masul; ?>
				</td>
				<td>

					<?php echo $item->nazorat; ?>
				</td>
				<td>

					<?php 
					//echo $item->tugrilandi_filial; 
					echo $item->filial_tugrilagan; 
					?>
				</td>
				<td>

					<?php 
					//echo $item->tugrilandi_bosh_bank;
					echo $item->bosh_bank_tugrilagan;
					 ?>
				</td>
				<td>

					<?php 
					//echo $item->tugrilandi_ichki_nazorat; 
					echo $item->ichki_nazorat_tugrilagan; 
					?>
				</td>
				<td>

					<?php 
					//echo $item->tugrilandi_ichki_audit;
					echo $item->ichki_audit_tugrilagan;
					 ?>
				</td>


								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_chora&task=chora_tadbirlarform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_chora&task=chora_tadbirlarform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_chora&task=chora_tadbirlarform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_CHORA_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo Text::_('COM_CHORA_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>

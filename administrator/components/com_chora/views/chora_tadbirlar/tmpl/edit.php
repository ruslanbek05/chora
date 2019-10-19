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
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.keepalive');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_chora/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	js('input:hidden.masul').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('masulhidden')){
			js('#jform_masul option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_masul").trigger("liszt:updated");
	js('input:hidden.nazorat').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('nazorathidden')){
			js('#jform_nazorat option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_nazorat").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'chora_tadbirlar.cancel') {
			Joomla.submitform(task, document.getElementById('chora_tadbirlar-form'));
		}
		else {
			
			if (task != 'chora_tadbirlar.cancel' && document.formvalidator.isValid(document.id('chora_tadbirlar-form'))) {
				
				Joomla.submitform(task, document.getElementById('chora_tadbirlar-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_chora&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="chora_tadbirlar-form" class="form-validate form-horizontal">

	
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'chora_tadbirlar')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'chora_tadbirlar', JText::_('COM_CHORA_TAB_CHORA_TADBIRLAR', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_CHORA_FIELDSET_CHORA_TADBIRLAR'); ?></legend>
				<?php echo $this->form->renderField('mazmuni'); ?>
				<?php echo $this->form->renderField('vazifa'); ?>
				<?php echo $this->form->renderField('muddati'); ?>
				<?php echo $this->form->renderField('masul'); ?>
				<?php
				foreach((array)$this->item->masul as $value)
				{
					if(!is_array($value))
					{
						echo '<input type="hidden" class="masul" name="jform[masulhidden]['.$value.']" value="'.$value.'" />';
					}
				}
				?>
				<?php echo $this->form->renderField('nazorat'); ?>
				<?php
				foreach((array)$this->item->nazorat as $value)
				{
					if(!is_array($value))
					{
						echo '<input type="hidden" class="nazorat" name="jform[nazorathidden]['.$value.']" value="'.$value.'" />';
					}
				}
				?>
				<?php echo $this->form->renderField('tugrilandi_filial'); ?>
				<?php echo $this->form->renderField('tugrilandi_bosh_bank'); ?>
				<?php echo $this->form->renderField('tugrilandi_ichki_nazorat'); ?>
				<?php echo $this->form->renderField('tugrilandi_ichki_audit'); ?>
				<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
				<?php endif; ?>
			</fieldset>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php if (JFactory::getUser()->authorise('core.admin','chora')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>

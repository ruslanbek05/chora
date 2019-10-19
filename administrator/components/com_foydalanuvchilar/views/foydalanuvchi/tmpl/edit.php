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
$document->addStyleSheet(Uri::root() . 'media/com_foydalanuvchilar/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	js('input:hidden.user_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('user_idhidden')){
			js('#jform_user_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_user_id").trigger("liszt:updated");
	js('input:hidden.mfo_filial').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('mfo_filialhidden')){
			js('#jform_mfo_filial option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_mfo_filial").trigger("liszt:updated");
	js('input:hidden.departament').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('departamenthidden')){
			js('#jform_departament option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_departament").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'foydalanuvchi.cancel') {
			Joomla.submitform(task, document.getElementById('foydalanuvchi-form'));
		}
		else {
			
			if (task != 'foydalanuvchi.cancel' && document.formvalidator.isValid(document.id('foydalanuvchi-form'))) {
				
				Joomla.submitform(task, document.getElementById('foydalanuvchi-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_foydalanuvchilar&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="foydalanuvchi-form" class="form-validate form-horizontal">

	
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'foydalanuvchi')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'foydalanuvchi', JText::_('COM_FOYDALANUVCHILAR_TAB_FOYDALANUVCHI', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_FOYDALANUVCHILAR_FIELDSET_FOYDALANUVCHI'); ?></legend>
				<?php echo $this->form->renderField('user_id'); ?>
				<?php
				foreach((array)$this->item->user_id as $value)
				{
					if(!is_array($value))
					{
						echo '<input type="hidden" class="user_id" name="jform[user_idhidden]['.$value.']" value="'.$value.'" />';
					}
				}
				?>
				<?php echo $this->form->renderField('mfo_filial'); ?>
				<?php
				foreach((array)$this->item->mfo_filial as $value)
				{
					if(!is_array($value))
					{
						echo '<input type="hidden" class="mfo_filial" name="jform[mfo_filialhidden]['.$value.']" value="'.$value.'" />';
					}
				}
				?>
				<?php echo $this->form->renderField('departament'); ?>
				<?php
				foreach((array)$this->item->departament as $value)
				{
					if(!is_array($value))
					{
						echo '<input type="hidden" class="departament" name="jform[departamenthidden]['.$value.']" value="'.$value.'" />';
					}
				}
				?>
				<?php echo $this->form->renderField('mintaqaviy_filial'); ?>
				<?php echo $this->form->renderField('ichki_nazorat'); ?>
				<?php echo $this->form->renderField('ichki_audit'); ?>
				<?php echo $this->form->renderField('filial'); ?>
				<?php echo $this->form->renderField('barcha_soha'); ?>
				<?php echo $this->form->renderField('bosh_bank'); ?>
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

	<?php if (JFactory::getUser()->authorise('core.admin','foydalanuvchilar')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>

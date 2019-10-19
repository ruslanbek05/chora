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

jimport('joomla.application.component.view');

use \Joomla\CMS\Language\Text;

/**
 * View class for a list of Chora.
 *
 * @since  1.6
 */
class ChoraViewChora_tadbirlars extends \Joomla\CMS\MVC\View\HtmlView
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		ChoraHelper::addSubmenu('chora_tadbirlars');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = ChoraHelper::getActions();

		JToolBarHelper::title(Text::_('COM_CHORA_TITLE_CHORA_TADBIRLARS'), 'chora_tadbirlars.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/chora_tadbirlar';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('chora_tadbirlar.add', 'JTOOLBAR_NEW');

				if (isset($this->items[0]))
				{
					JToolbarHelper::custom('chora_tadbirlars.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
				}
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('chora_tadbirlar.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('chora_tadbirlars.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('chora_tadbirlars.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'chora_tadbirlars.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('chora_tadbirlars.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('chora_tadbirlars.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'chora_tadbirlars.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('chora_tadbirlars.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_chora');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_chora&view=chora_tadbirlars');
	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`created_by`' => JText::_('COM_CHORA_CHORA_TADBIRLARS_CREATED_BY'),
			'a.`mazmuni`' => JText::_('COM_CHORA_CHORA_TADBIRLARS_MAZMUNI'),
			'a.`vazifa`' => JText::_('COM_CHORA_CHORA_TADBIRLARS_VAZIFA'),
			'a.`muddati`' => JText::_('COM_CHORA_CHORA_TADBIRLARS_MUDDATI'),
			'a.`masul`' => JText::_('COM_CHORA_CHORA_TADBIRLARS_MASUL'),
			'a.`nazorat`' => JText::_('COM_CHORA_CHORA_TADBIRLARS_NAZORAT'),
			'a.`tugrilandi_filial`' => JText::_('COM_CHORA_CHORA_TADBIRLARS_TUGRILANDI_FILIAL'),
			'a.`tugrilandi_bosh_bank`' => JText::_('COM_CHORA_CHORA_TADBIRLARS_TUGRILANDI_BOSH_BANK'),
			'a.`tugrilandi_ichki_nazorat`' => JText::_('COM_CHORA_CHORA_TADBIRLARS_TUGRILANDI_ICHKI_NAZORAT'),
			'a.`tugrilandi_ichki_audit`' => JText::_('COM_CHORA_CHORA_TADBIRLARS_TUGRILANDI_ICHKI_AUDIT'),
		);
	}

    /**
     * Check if state is set
     *
     * @param   mixed  $state  State
     *
     * @return bool
     */
    public function getState($state)
    {
        return isset($this->state->{$state}) ? $this->state->{$state} : false;
    }
}

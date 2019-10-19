<?php

/**
 * @version    CVS: 1.0.1
 * @package    Com_Foydalanuvchilar
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Foydalanuvchilar records.
 *
 * @since  1.6
 */
class FoydalanuvchilarModelFoydalanuvchilar extends \Joomla\CMS\MVC\Model\ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'user_id', 'a.user_id',
				'mfo_filial', 'a.mfo_filial',
				'departament', 'a.departament',
				'mintaqaviy_filial', 'a.mintaqaviy_filial',
				'ichki_nazorat', 'a.ichki_nazorat',
				'ichki_audit', 'a.ichki_audit',
				'filial', 'a.filial',
				'barcha_soha', 'a.barcha_soha',
				'bosh_bank', 'a.bosh_bank',
			);
		}

		parent::__construct($config);
	}

        
        
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
            $app  = JFactory::getApplication();
		$list = $app->getUserState($this->context . '.list');

		$ordering  = isset($list['filter_order'])     ? $list['filter_order']     : null;
		$direction = isset($list['filter_order_Dir']) ? $list['filter_order_Dir'] : null;

		$list['limit']     = $app->input->getInt('limit', JFactory::getConfig()->get('list_limit', 20));
		$list['start']     = $app->input->getInt('start', 0);
		$list['ordering']  = $ordering;
		$list['direction'] = $direction;

		$app->setUserState($this->context . '.list', $list);
		$app->input->set('list', null);

        // List state information.
        parent::populateState('a.ordering', 'asc');

        $context = $this->getUserStateFromRequest($this->context . '.context', 'context', 'com_content.article', 'CMD');
        $this->setState('filter.context', $context);

        // Split context into component and optional section
        $parts = FieldsHelper::extract($context);

        if ($parts)
        {
            $this->setState('filter.component', $parts[0]);
            $this->setState('filter.section', $parts[1]);
        }
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
            // Create a new query object.
            $db    = $this->getDbo();
            $query = $db->getQuery(true);

            // Select the required fields from the table.
            $query->select(
                        $this->getState(
                                'list.select', 'DISTINCT a.*'
                        )
                );

            $query->from('`#__foydalanuvchilar_` AS a');
            
		// Join over the users for the checked out user.
		$query->select('uc.name AS uEditor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');
		// Join over the foreign key 'user_id'
		$query->select('`#__users_3266485`.`name` AS users_fk_value_3266485');
		$query->join('LEFT', '#__users AS #__users_3266485 ON #__users_3266485.`id` = a.`user_id`');
		// Join over the foreign key 'mfo_filial'
		$query->select('`#__filiallar_3266486`.`mfo` AS #__filiallar_fk_value_3266486');
		$query->join('LEFT', '#__filiallar AS #__filiallar_3266486 ON #__filiallar_3266486.`mfo` = a.`mfo_filial`');
		// Join over the foreign key 'departament'
		$query->select('`#__departamentlar_3266487`.`nomi` AS #__departamentlar_fk_value_3266487');
		$query->join('LEFT', '#__departamentlar AS #__departamentlar_3266487 ON #__departamentlar_3266487.`nomi` = a.`departament`');
            
		if (!Factory::getUser()->authorise('core.edit', 'com_foydalanuvchilar'))
		{
			$query->where('a.state = 1');
		}

            // Filter by search in title
            $search = $this->getState('filter.search');

            if (!empty($search))
            {
                if (stripos($search, 'id:') === 0)
                {
                    $query->where('a.id = ' . (int) substr($search, 3));
                }
                else
                {
                    $search = $db->Quote('%' . $db->escape($search, true) . '%');
					$query->where('(#__users_3266485.name LIKE ' . $search . '  OR #__filiallar_3266486.mfo LIKE ' . $search . '  OR #__departamentlar_3266487.nomi LIKE ' . $search . ' )');
                }
            }
            

		// Filtering user_id
		$filter_user_id = $this->state->get("filter.user_id");

		if ($filter_user_id)
		{
			$query->where("a.`user_id` = '".$db->escape($filter_user_id)."'");
		}

		// Filtering mfo_filial
		$filter_mfo_filial = $this->state->get("filter.mfo_filial");

		if ($filter_mfo_filial)
		{
			$query->where("a.`mfo_filial` = '".$db->escape($filter_mfo_filial)."'");
		}

		// Filtering departament
		$filter_departament = $this->state->get("filter.departament");

		if ($filter_departament)
		{
			$query->where("a.`departament` = '".$db->escape($filter_departament)."'");
		}

            // Add the list ordering clause.
            $orderCol  = $this->state->get('list.ordering', "a.id");
            $orderDirn = $this->state->get('list.direction', "ASC");

            if ($orderCol && $orderDirn)
            {
                $query->order($db->escape($orderCol . ' ' . $orderDirn));
            }

            return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		
		foreach ($items as $item)
		{

			if (isset($item->user_id))
			{

				$values    = explode(',', $item->user_id);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__users_3266485`.`name`')
						->from($db->quoteName('#__users', '#__users_3266485'))
						->where($db->quoteName('#__users_3266485.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->name;
					}
				}

				$item->user_id = !empty($textValue) ? implode(', ', $textValue) : $item->user_id;
			}


			if (isset($item->mfo_filial))
			{

				$values    = explode(',', $item->mfo_filial);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__filiallar_3266486`.`mfo`')
						->from($db->quoteName('#__filiallar', '#__filiallar_3266486'))
						->where($db->quoteName('#__filiallar_3266486.mfo') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->mfo;
					}
				}

				$item->mfo_filial = !empty($textValue) ? implode(', ', $textValue) : $item->mfo_filial;
			}


			if (isset($item->departament))
			{

				$values    = explode(',', $item->departament);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__departamentlar_3266487`.`nomi`')
						->from($db->quoteName('#__departamentlar', '#__departamentlar_3266487'))
						->where($db->quoteName('#__departamentlar_3266487.nomi') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->nomi;
					}
				}

				$item->departament = !empty($textValue) ? implode(', ', $textValue) : $item->departament;
			}

		}

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = Factory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(Text::_("COM_FOYDALANUVCHILAR_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? Factory::getDate($date)->format("Y-m-d") : null;
	}
}

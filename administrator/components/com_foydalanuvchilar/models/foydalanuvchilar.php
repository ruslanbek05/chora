<?php

/**
 * @version    CVS: 1.0.1
 * @package    Com_Foydalanuvchilar
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

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
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'user_id', 'a.`user_id`',
				'mfo_filial', 'a.`mfo_filial`',
				'departament', 'a.`departament`',
				'mintaqaviy_filial', 'a.`mintaqaviy_filial`',
				'ichki_nazorat', 'a.`ichki_nazorat`',
				'ichki_audit', 'a.`ichki_audit`',
				'filial', 'a.`filial`',
				'barcha_soha', 'a.`barcha_soha`',
				'bosh_bank', 'a.`bosh_bank`',
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
	 */
	protected function populateState($ordering = null, $direction = null)
	{
        // List state information.
        parent::populateState("a.id", "ASC");

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
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

                
                    return parent::getStoreId($id);
                
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
                
		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');
		// Join over the foreign key 'user_id'
		$query->select('`#__users_3266485`.`name` AS users_fk_value_3266485');
		$query->join('LEFT', '#__users AS #__users_3266485 ON #__users_3266485.`id` = a.`user_id`');
		// Join over the foreign key 'mfo_filial'
		$query->select('`#__filiallar_3266486`.`mfo` AS #__filiallar_fk_value_3266486');
		$query->join('LEFT', '#__filiallar AS #__filiallar_3266486 ON #__filiallar_3266486.`mfo` = a.`mfo_filial`');
		// Join over the foreign key 'departament'
		$query->select('`#__departamentlar_3266487`.`nomi` AS #__departamentlar_fk_value_3266487');
		$query->join('LEFT', '#__departamentlar AS #__departamentlar_3266487 ON #__departamentlar_3266487.`nomi` = a.`departament`');
                

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
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
				$query->where('(#__users_3266485.name LIKE ' . $search . '  OR #__filiallar_3266486.mfo LIKE ' . $search . '  OR #__departamentlar_3266487.nomi LIKE ' . $search . '  OR  a.mintaqaviy_filial LIKE ' . $search . '  OR  a.ichki_nazorat LIKE ' . $search . '  OR  a.ichki_audit LIKE ' . $search . '  OR  a.filial LIKE ' . $search . '  OR  a.barcha_soha LIKE ' . $search . '  OR  a.bosh_bank LIKE ' . $search . ' )');
			}
		}
                

		// Filtering user_id
		$filter_user_id = $this->state->get("filter.user_id");

		if ($filter_user_id !== null && !empty($filter_user_id))
		{
			$query->where("a.`user_id` = '".$db->escape($filter_user_id)."'");
		}

		// Filtering mfo_filial
		$filter_mfo_filial = $this->state->get("filter.mfo_filial");

		if ($filter_mfo_filial !== null && !empty($filter_mfo_filial))
		{
			$query->where("a.`mfo_filial` = '".$db->escape($filter_mfo_filial)."'");
		}

		// Filtering departament
		$filter_departament = $this->state->get("filter.departament");

		if ($filter_departament !== null && !empty($filter_departament))
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
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
                
		foreach ($items as $oneItem)
		{

			if (isset($oneItem->user_id))
			{
				$values    = explode(',', $oneItem->user_id);
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

				$oneItem->user_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->user_id;
			}

			if (isset($oneItem->mfo_filial))
			{
				$values    = explode(',', $oneItem->mfo_filial);
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

				$oneItem->mfo_filial = !empty($textValue) ? implode(', ', $textValue) : $oneItem->mfo_filial;
			}

			if (isset($oneItem->departament))
			{
				$values    = explode(',', $oneItem->departament);
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

				$oneItem->departament = !empty($textValue) ? implode(', ', $textValue) : $oneItem->departament;
			}
		}

		return $items;
	}
}

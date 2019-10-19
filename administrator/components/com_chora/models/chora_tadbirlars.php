<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Chora
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Chora records.
 *
 * @since  1.6
 */
class ChoraModelChora_tadbirlars extends \Joomla\CMS\MVC\Model\ListModel
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
				'mazmuni', 'a.`mazmuni`',
				'vazifa', 'a.`vazifa`',
				'muddati', 'a.`muddati`',
				'masul', 'a.`masul`',
				'nazorat', 'a.`nazorat`',
				'tugrilandi_filial', 'a.`tugrilandi_filial`',
				'tugrilandi_bosh_bank', 'a.`tugrilandi_bosh_bank`',
				'tugrilandi_ichki_nazorat', 'a.`tugrilandi_ichki_nazorat`',
				'tugrilandi_ichki_audit', 'a.`tugrilandi_ichki_audit`',
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
        parent::populateState('id', 'DESC');

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
		$query->from('`#__chora` AS a');
                
		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');
		// Join over the foreign key 'masul'
		$query->select('`#__users_3266512`.`name` AS users_fk_value_3266512');
		$query->join('LEFT', '#__users AS #__users_3266512 ON #__users_3266512.`id` = a.`masul`');
		// Join over the foreign key 'nazorat'
		$query->select('`#__departamentlar_3266514`.`nomi` AS #__departamentlar_fk_value_3266514');
		$query->join('LEFT', '#__departamentlar AS #__departamentlar_3266514 ON #__departamentlar_3266514.`nomi` = a.`nazorat`');
                

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
				$query->where('( a.mazmuni LIKE ' . $search . '  OR  a.vazifa LIKE ' . $search . '  OR  a.muddati LIKE ' . $search . '  OR #__users_3266512.name LIKE ' . $search . '  OR #__departamentlar_3266514.nomi LIKE ' . $search . '  OR  a.tugrilandi_filial LIKE ' . $search . '  OR  a.tugrilandi_bosh_bank LIKE ' . $search . '  OR  a.tugrilandi_ichki_nazorat LIKE ' . $search . '  OR  a.tugrilandi_ichki_audit LIKE ' . $search . ' )');
			}
		}
                

		// Filtering created_by
		$filter_created_by = $this->state->get("filter.created_by");

		if ($filter_created_by !== null && !empty($filter_created_by))
		{
			$query->where("a.`created_by` = '".$db->escape($filter_created_by)."'");
		}

		// Filtering muddati
		$filter_muddati_from = $this->state->get("filter.muddati.from");

		if ($filter_muddati_from !== null && !empty($filter_muddati_from))
		{
			$query->where("a.`muddati` >= '".$db->escape($filter_muddati_from)."'");
		}
		$filter_muddati_to = $this->state->get("filter.muddati.to");

		if ($filter_muddati_to !== null  && !empty($filter_muddati_to))
		{
			$query->where("a.`muddati` <= '".$db->escape($filter_muddati_to)."'");
		}

		// Filtering masul
		$filter_masul = $this->state->get("filter.masul");

		if ($filter_masul !== null && !empty($filter_masul))
		{
			$query->where("a.`masul` = '".$db->escape($filter_masul)."'");
		}

		// Filtering nazorat
		$filter_nazorat = $this->state->get("filter.nazorat");

		if ($filter_nazorat !== null && !empty($filter_nazorat))
		{
			$query->where("a.`nazorat` = '".$db->escape($filter_nazorat)."'");
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'DESC');

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

			if (isset($oneItem->masul))
			{
				$values    = explode(',', $oneItem->masul);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__users_3266512`.`name`')
						->from($db->quoteName('#__users', '#__users_3266512'))
						->where($db->quoteName('#__users_3266512.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->name;
					}
				}

				$oneItem->masul = !empty($textValue) ? implode(', ', $textValue) : $oneItem->masul;
			}

			if (isset($oneItem->nazorat))
			{
				$values    = explode(',', $oneItem->nazorat);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__departamentlar_3266514`.`nomi`')
						->from($db->quoteName('#__departamentlar', '#__departamentlar_3266514'))
						->where($db->quoteName('#__departamentlar_3266514.nomi') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->nomi;
					}
				}

				$oneItem->nazorat = !empty($textValue) ? implode(', ', $textValue) : $oneItem->nazorat;
			}
		}

		return $items;
	}
}

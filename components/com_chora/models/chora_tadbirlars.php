<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Chora
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

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
				'id', 'a.id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'mazmuni', 'a.mazmuni',
				'vazifa', 'a.vazifa',
				'muddati', 'a.muddati',
				'masul', 'a.masul',
				'nazorat', 'a.nazorat',
				'tugrilandi_filial', 'a.tugrilandi_filial',
				'tugrilandi_bosh_bank', 'a.tugrilandi_bosh_bank',
				'tugrilandi_ichki_nazorat', 'a.tugrilandi_ichki_nazorat',
				'tugrilandi_ichki_audit', 'a.tugrilandi_ichki_audit',
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

            $query->from('`#__chora` AS a');
            
		// Join over the users for the checked out user.
		$query->select('uc.name AS uEditor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');
		// Join over the foreign key 'masul'
		$query->select('`#__users_3266512`.`name` AS users_fk_value_3266512');
		$query->join('LEFT', '#__users AS #__users_3266512 ON #__users_3266512.`id` = a.`masul`');
		// Join over the foreign key 'nazorat'
		$query->select('`#__departamentlar_3266514`.`nomi` AS #__departamentlar_fk_value_3266514');
		$query->join('LEFT', '#__departamentlar AS #__departamentlar_3266514 ON #__departamentlar_3266514.`nomi` = a.`nazorat`');
		
//filter // filial 1 barcha_soha 0
		$user = JFactory::getUser();
		$userId = $user->get('id');
			$dbqu    = JFactory::getDbo();
			$qu = $dbqu->getQuery(true);
			$qu ="SELECT ch_foydalanuvchilar_.* FROM ch_foydalanuvchilar_ WHERE (((ch_foydalanuvchilar_.user_id)=".$userId."))";
			$dbqu->setQuery($qu);
			$rowqu = $dbqu->loadAssoc();
			
			if (($rowqu['filial'] == 1) AND ($rowqu['barcha_soha'] == 0))
			{
				//echo "w1";
				//$query->where('a.`masul` = 329');
				//$query->where('a.`masul` = 329');
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704 ON #__foydalanuvchilar_20190704.`user_id` = a.`masul`');
				$query->where('#__foydalanuvchilar_20190704.user_id = '.$userId);				
//END//filter // filial 1 barcha_soha 0
			}
			elseif(($rowqu['filial'] == 1) AND ($rowqu['barcha_soha'] == 1))
			{
				//echo "w2";
				//$query->where('a.`masul` = 329');
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_2 ON #__foydalanuvchilar_20190704_2.`user_id` = a.`masul`');
				$query->where('#__foydalanuvchilar_20190704_2.mfo_filial = '.$rowqu['mfo_filial']);
//END//filter // filial 1 barcha_soha 1							
			}elseif(($rowqu['mintaqaviy_filial'] == 1) AND ($rowqu['barcha_soha'] == 0))
			{
				//echo "w3";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_3 ON #__foydalanuvchilar_20190704_3.`user_id` = a.`masul`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_3.`mfo_filial`');
				$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
				$query->where("a.`nazorat` = '".$rowqu['departament']."'");
			}elseif(($rowqu['mintaqaviy_filial'] == 1) AND ($rowqu['barcha_soha'] == 1))
			{
				//echo "w4";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_4 ON #__foydalanuvchilar_20190704_4.`user_id` = a.`masul`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_4.`mfo_filial`');
				$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
			}elseif(($rowqu['bosh_bank'] == 1) AND ($rowqu['barcha_soha'] == 0))
			{
				//echo "w5";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_5 ON #__foydalanuvchilar_20190704_5.`user_id` = a.`masul`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_5.`mfo_filial`');
				//$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
				$query->where("a.`nazorat` = '".$rowqu['departament']."'");
			}elseif(($rowqu['bosh_bank'] == 1) AND ($rowqu['barcha_soha'] == 1))
			{
				//echo "w6";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_6 ON #__foydalanuvchilar_20190704_6.`user_id` = a.`masul`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_6.`mfo_filial`');
				//$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
				//$query->where("a.`nazorat` = '".$rowqu['departament']."'");
			}elseif(($rowqu['ichki_nazorat'] == 1))
			{
				//echo "w7";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_7 ON #__foydalanuvchilar_20190704_7.`user_id` = a.`masul`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_7.`mfo_filial`');
				//$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
				//$query->where("a.`nazorat` = '".$rowqu['departament']."'");
			}elseif(($rowqu['ichki_audit'] == 1))
			{
				//echo "w8";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_8 ON #__foydalanuvchilar_20190704_8.`user_id` = a.`masul`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_8.`mfo_filial`');
				//$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
				//$query->where("a.`nazorat` = '".$rowqu['departament']."'");
			}else{
				$query->where('a.id = 0');
			}












            
		if (!Factory::getUser()->authorise('core.edit', 'com_chora'))
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
					$query->where('( a.mazmuni LIKE ' . $search . '  OR  a.vazifa LIKE ' . $search . '  OR #__users_3266512.name LIKE ' . $search . '  OR #__departamentlar_3266514.nomi LIKE ' . $search . ' )');
                }
            }
            

		// Filtering created_by
		$filter_created_by = $this->state->get("filter.created_by");

		if ($filter_created_by)
		{
			$query->where("a.`created_by` = '" . $db->escape($filter_created_by) . "'");
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

		if ($filter_masul)
		{
			$query->where("a.`masul` = '".$db->escape($filter_masul)."'");
		}

		// Filtering nazorat
		$filter_nazorat = $this->state->get("filter.nazorat");

		if ($filter_nazorat)
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
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		
		foreach ($items as $item)
		{
			
			
			
//mfo
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query ="SELECT ch_foydalanuvchilar_.mfo_filial, ch_foydalanuvchilar_.user_id
FROM ch_foydalanuvchilar_
WHERE (((ch_foydalanuvchilar_.user_id)=".$item->masul."));
";
			$db->setQuery($query);
			$row = $db->loadAssoc();
			
			$item->mfo = $row['mfo_filial'];
//END mfo					
			
			

			if (isset($item->masul))
			{

				$values    = explode(',', $item->masul);
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

				$item->masul = !empty($textValue) ? implode(', ', $textValue) : $item->masul;
			}


			if (isset($item->nazorat))
			{

				$values    = explode(',', $item->nazorat);
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

				$item->nazorat = !empty($textValue) ? implode(', ', $textValue) : $item->nazorat;
			}
			

//print_r($items);die;
//filial tugrilagani
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query ="SELECT Sum(ch_ijro.tugrilanganligi) AS filial_tugrilagan
FROM ch_ijro LEFT JOIN ch_foydalanuvchilar_ ON ch_ijro.created_by = ch_foydalanuvchilar_.user_id
WHERE ((((ch_foydalanuvchilar_.filial)=1) OR ((ch_foydalanuvchilar_.mintaqaviy_filial)=1)) AND ((ch_ijro.chora_id)=".$item->id."));
";
			$db->setQuery($query);
			$row = $db->loadAssoc();
			
			if ($row['filial_tugrilagan'] > 0){
				$item->filial_tugrilagan = $row['filial_tugrilagan'];
			}
			else{
				$item->filial_tugrilagan = 0;
			}


//bosh bank tugrilagani
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query ="SELECT Sum(ch_ijro.tugrilanganligi) AS bosh_bank_tugrilagan
FROM ch_ijro INNER JOIN ch_foydalanuvchilar_ ON ch_ijro.created_by = ch_foydalanuvchilar_.user_id
WHERE (((ch_foydalanuvchilar_.bosh_bank)=1) AND ((ch_ijro.chora_id)=".$item->id."));
";
			$db->setQuery($query);
			$row = $db->loadAssoc();
			
			if ($row['bosh_bank_tugrilagan'] > 0){
				$item->bosh_bank_tugrilagan = $row['bosh_bank_tugrilagan'];
			}
			else{
				$item->bosh_bank_tugrilagan = 0;
			}




//ichki nazorat tugrilagani tugrilagani
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query ="SELECT Sum(ch_ijro.tugrilanganligi) AS ichki_nazorat_tugrilagan
FROM ch_ijro INNER JOIN ch_foydalanuvchilar_ ON ch_ijro.created_by = ch_foydalanuvchilar_.user_id
WHERE (((ch_foydalanuvchilar_.ichki_nazorat)=1) AND ((ch_ijro.chora_id)=".$item->id."));
";
			$db->setQuery($query);
			$row = $db->loadAssoc();
			
			if ($row['ichki_nazorat_tugrilagan'] > 0){
				$item->ichki_nazorat_tugrilagan = $row['ichki_nazorat_tugrilagan'];
			}
			else{
				$item->ichki_nazorat_tugrilagan = 0;
			}



//ichki audit tugrilagani tugrilagani
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query ="SELECT Sum(ch_ijro.tugrilanganligi) AS ichki_audit_tugrilagan
FROM ch_ijro INNER JOIN ch_foydalanuvchilar_ ON ch_ijro.created_by = ch_foydalanuvchilar_.user_id
WHERE (((ch_foydalanuvchilar_.ichki_audit)=1) AND ((ch_ijro.chora_id)=".$item->id."));
";
			$db->setQuery($query);
			$row = $db->loadAssoc();
			
			if ($row['ichki_audit_tugrilagan'] > 0){
				$item->ichki_audit_tugrilagan = $row['ichki_audit_tugrilagan'];
			}
			else{
				$item->ichki_audit_tugrilagan = 0;
			}




	
			
			

			
			
			//print_r($item->filial_tugrilagan);die;

			//$item->filial_tugrilagan = 22;



			
		}
		
		//print_r($items);die;

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
			$app->enqueueMessage(Text::_("COM_CHORA_SEARCH_FILTER_DATE_FORMAT"), "warning");
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

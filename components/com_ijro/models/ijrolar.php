<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Ijro
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Ijro records.
 *
 * @since  1.6
 */
class IjroModelIjrolar extends \Joomla\CMS\MVC\Model\ListModel
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
				'bajarilgan_ish', 'a.bajarilgan_ish',
				'tugrilanganligi', 'a.tugrilanganligi',
				'chora_id', 'a.chora_id',
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

            $query->from('`#__ijro` AS a');
            
		// Join over the users for the checked out user.
		$query->select('uc.name AS uEditor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');
		
		
		
		$user = JFactory::getUser();
		$userId = $user->get('id');
			$dbqu    = JFactory::getDbo();
			$qu = $dbqu->getQuery(true);
			$qu ="SELECT ch_foydalanuvchilar_.* FROM ch_foydalanuvchilar_ WHERE (((ch_foydalanuvchilar_.user_id)=".$userId."))";
			$dbqu->setQuery($qu);
			$rowqu = $dbqu->loadAssoc();
			
			$query->join('INNER', '#__chora AS chora ON chora.`id` = a.`chora_id`');		
		
		if (($rowqu['filial'] == 1) AND ($rowqu['barcha_soha'] == 0))
			{
				echo "w1";
				//$query->where('a.`masul` = 329');
				//$query->where('a.`masul` = 329');
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704 ON #__foydalanuvchilar_20190704.`user_id` = a.`created_by`');
				$query->where('#__foydalanuvchilar_20190704.user_id = '.$userId);				
//END//filter // filial 1 barcha_soha 0
			}elseif(($rowqu['filial'] == 1) AND ($rowqu['barcha_soha'] == 1))
			{
				//echo "w2";
				//$query->where('a.`masul` = 329');
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_2 ON #__foydalanuvchilar_20190704_2.`user_id` = a.`created_by`');
				$query->where('#__foydalanuvchilar_20190704_2.mfo_filial = '.$rowqu['mfo_filial']);
//END//filter // filial 1 barcha_soha 1							
			}elseif(($rowqu['mintaqaviy_filial'] == 1) AND ($rowqu['barcha_soha'] == 0))
			{
				//echo "w3";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_3 ON #__foydalanuvchilar_20190704_3.`user_id` = a.`created_by`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_3.`mfo_filial`');
				$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
				$query->where("chora.`nazorat` = '".$rowqu['departament']."'");
			}elseif(($rowqu['mintaqaviy_filial'] == 1) AND ($rowqu['barcha_soha'] == 1))
			{
				//echo "w4";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_4 ON #__foydalanuvchilar_20190704_4.`user_id` = a.`created_by`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_4.`mfo_filial`');
				$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
			}elseif(($rowqu['bosh_bank'] == 1) AND ($rowqu['barcha_soha'] == 0))
			{
				//echo "w5";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_5 ON #__foydalanuvchilar_20190704_5.`user_id` = a.`created_by`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_5.`mfo_filial`');
				//$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
				$query->where("chora.`nazorat` = '".$rowqu['departament']."'");
			}elseif(($rowqu['bosh_bank'] == 1) AND ($rowqu['barcha_soha'] == 1))
			{
				//echo "w6";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_6 ON #__foydalanuvchilar_20190704_6.`user_id` = a.`created_by`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_6.`mfo_filial`');
				//$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
				//$query->where("a.`nazorat` = '".$rowqu['departament']."'");
			}elseif(($rowqu['ichki_nazorat'] == 1))
			{
				//echo "w7";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_7 ON #__foydalanuvchilar_20190704_7.`user_id` = a.`created_by`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_7.`mfo_filial`');
				//$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
				//$query->where("a.`nazorat` = '".$rowqu['departament']."'");
			}elseif(($rowqu['ichki_audit'] == 1))
			{
				//echo "w8";
				$query->join('INNER', '#__foydalanuvchilar_ AS #__foydalanuvchilar_20190704_8 ON #__foydalanuvchilar_20190704_8.`user_id` = a.`created_by`');
				$query->join('INNER', '#__filiallar AS filiallar ON filiallar.`mfo` = #__foydalanuvchilar_20190704_8.`mfo_filial`');
				//$query->where('filiallar.mintaqa_mfo = '.$rowqu['mfo_filial']);
				//$query->where("a.`nazorat` = '".$rowqu['departament']."'");
			}else{
				//$query->where('a.id = 0');
			}
		
		
		
		$chora_id = JRequest::getVar('chora_id');
		if ($chora_id<>null) {
				$query->where("a.chora_id = '".$db->escape($chora_id)."'");	
			}
		else
			{
				$query->where("a.chora_id = -1");
			}
		
		
            
		if (!Factory::getUser()->authorise('core.edit', 'com_ijro'))
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
					$query->where('( a.bajarilgan_ish LIKE ' . $search . ' )');
                }
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
			$app->enqueueMessage(Text::_("COM_IJRO_SEARCH_FILTER_DATE_FORMAT"), "warning");
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

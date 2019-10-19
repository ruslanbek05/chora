<?php

/**
 * @version    CVS: 1.0.1
 * @package    Com_Foydalanuvchilar
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

use \Joomla\CMS\Factory;
use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table;

/**
 * Foydalanuvchilar model.
 *
 * @since  1.6
 */
class FoydalanuvchilarModelFoydalanuvchi extends \Joomla\CMS\MVC\Model\ItemModel
{
    public $_item;

        
    
        
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 *
	 * @since    1.6
	 *
     * @throws Exception
	 */
	protected function populateState()
	{
		$app  = Factory::getApplication('com_foydalanuvchilar');
		$user = Factory::getUser();

		// Check published state
		if ((!$user->authorise('core.edit.state', 'com_foydalanuvchilar')) && (!$user->authorise('core.edit', 'com_foydalanuvchilar')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		// Load state from the request userState on edit or from the passed variable on default
		if (Factory::getApplication()->input->get('layout') == 'edit')
		{
			$id = Factory::getApplication()->getUserState('com_foydalanuvchilar.edit.foydalanuvchi.id');
		}
		else
		{
			$id = Factory::getApplication()->input->get('id');
			Factory::getApplication()->setUserState('com_foydalanuvchilar.edit.foydalanuvchi.id', $id);
		}

		$this->setState('foydalanuvchi.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('foydalanuvchi.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer $id The id of the object to get.
	 *
	 * @return  mixed    Object on success, false on failure.
     *
     * @throws Exception
	 */
	public function getItem($id = null)
	{
            if ($this->_item === null)
            {
                $this->_item = false;

                if (empty($id))
                {
                    $id = $this->getState('foydalanuvchi.id');
                }

                // Get a level row instance.
                $table = $this->getTable();

                // Attempt to load the row.
                if ($table->load($id))
                {
                    

                    // Check published state.
                    if ($published = $this->getState('filter.published'))
                    {
                        if (isset($table->state) && $table->state != $published)
                        {
                            throw new Exception(Text::_('COM_FOYDALANUVCHILAR_ITEM_NOT_LOADED'), 403);
                        }
                    }

                    // Convert the JTable to a clean JObject.
                    $properties  = $table->getProperties(1);
                    $this->_item = ArrayHelper::toObject($properties, 'JObject');

                    
                } 
            }
        
            

		if (isset($this->_item->created_by))
		{
			$this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
		}

		if (isset($this->_item->modified_by))
		{
			$this->_item->modified_by_name = JFactory::getUser($this->_item->modified_by)->name;
		}

		if (isset($this->_item->user_id) && $this->_item->user_id != '')
		{
			if (is_object($this->_item->user_id))
			{
				$this->_item->user_id = ArrayHelper::fromObject($this->_item->user_id);
			}

			$values = (is_array($this->_item->user_id)) ? $this->_item->user_id : explode(',',$this->_item->user_id);

			$textValue = array();

			foreach ($values as $value)
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__users_3266485`.`name`')
					->from($db->quoteName('#__users', '#__users_3266485'))
					->where($db->quoteName('nomi') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->name;
				}
			}

			$this->_item->user_id = !empty($textValue) ? implode(', ', $textValue) : $this->_item->user_id;

		}

		if (isset($this->_item->mfo_filial) && $this->_item->mfo_filial != '')
		{
			if (is_object($this->_item->mfo_filial))
			{
				$this->_item->mfo_filial = ArrayHelper::fromObject($this->_item->mfo_filial);
			}

			$values = (is_array($this->_item->mfo_filial)) ? $this->_item->mfo_filial : explode(',',$this->_item->mfo_filial);

			$textValue = array();

			foreach ($values as $value)
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__filiallar_3266486`.`mfo`')
					->from($db->quoteName('#__filiallar', '#__filiallar_3266486'))
					->where($db->quoteName('mfo') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->mfo;
				}
			}

			$this->_item->mfo_filial = !empty($textValue) ? implode(', ', $textValue) : $this->_item->mfo_filial;

		}

		if (isset($this->_item->departament) && $this->_item->departament != '')
		{
			if (is_object($this->_item->departament))
			{
				$this->_item->departament = ArrayHelper::fromObject($this->_item->departament);
			}

			$values = (is_array($this->_item->departament)) ? $this->_item->departament : explode(',',$this->_item->departament);

			$textValue = array();

			foreach ($values as $value)
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);

				$query
					->select('`#__departamentlar_3266487`.`nomi`')
					->from($db->quoteName('#__departamentlar', '#__departamentlar_3266487'))
					->where($db->quoteName('nomi') . ' = ' . $db->quote($value));

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->nomi;
				}
			}

			$this->_item->departament = !empty($textValue) ? implode(', ', $textValue) : $this->_item->departament;

		}

            return $this->_item;
        }

	/**
	 * Get an instance of JTable class
	 *
	 * @param   string $type   Name of the JTable class to get an instance of.
	 * @param   string $prefix Prefix for the table class name. Optional.
	 * @param   array  $config Array of configuration values for the JTable object. Optional.
	 *
	 * @return  JTable|bool JTable if success, false on failure.
	 */
	public function getTable($type = 'Foydalanuvchi', $prefix = 'FoydalanuvchilarTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_foydalanuvchilar/tables');

		return Table::getInstance($type, $prefix, $config);
	}

	/**
	 * Get the id of an item by alias
	 *
	 * @param   string $alias Item alias
	 *
	 * @return  mixed
	 */
	public function getItemIdByAlias($alias)
	{
            $table      = $this->getTable();
            $properties = $table->getProperties();
            $result     = null;

            if (key_exists('alias', $properties))
            {
                $table->load(array('alias' => $alias));
                $result = $table->id;
            }
            
                return $result;
            
	}

	/**
	 * Method to check in an item.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('foydalanuvchi.id');
                
		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin'))
			{
				if (!$table->checkin($id))
				{
					return false;
				}
			}
		}

		return true;
                
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('foydalanuvchi.id');

                
		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = Factory::getUser();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout'))
			{
				if (!$table->checkout($user->get('id'), $id))
				{
					return false;
				}
			}
		}

		return true;
                
	}

	/**
	 * Publish the element
	 *
	 * @param   int $id    Item id
	 * @param   int $state Publish state
	 *
	 * @return  boolean
	 */
	public function publish($id, $state)
	{
		$table = $this->getTable();
                
		$table->load($id);
		$table->state = $state;

		return $table->store();
                
	}

	/**
	 * Method to delete an item
	 *
	 * @param   int $id Element id
	 *
	 * @return  bool
	 */
	public function delete($id)
	{
		$table = $this->getTable();

                
                    return $table->delete($id);
                
	}

	
}

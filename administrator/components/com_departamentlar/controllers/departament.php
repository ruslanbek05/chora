<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Departamentlar
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Departament controller class.
 *
 * @since  1.6
 */
class DepartamentlarControllerDepartament extends \Joomla\CMS\MVC\Controller\FormController
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'departamentlar';
		parent::__construct();
	}
}

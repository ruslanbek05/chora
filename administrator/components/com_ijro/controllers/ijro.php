<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Ijro
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Ijro controller class.
 *
 * @since  1.6
 */
class IjroControllerIjro extends \Joomla\CMS\MVC\Controller\FormController
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'ijrolar';
		parent::__construct();
	}
}

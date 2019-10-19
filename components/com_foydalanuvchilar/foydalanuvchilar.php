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
use \Joomla\CMS\MVC\Controller\BaseController;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Foydalanuvchilar', JPATH_COMPONENT);
JLoader::register('FoydalanuvchilarController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = BaseController::getInstance('Foydalanuvchilar');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();

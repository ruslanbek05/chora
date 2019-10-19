<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Kamchilik_turlari
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Controller\BaseController;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Kamchilik_turlari', JPATH_COMPONENT);
JLoader::register('Kamchilik_turlariController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = BaseController::getInstance('Kamchilik_turlari');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();

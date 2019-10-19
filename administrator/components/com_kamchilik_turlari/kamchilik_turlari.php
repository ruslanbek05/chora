<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Kamchilik_turlari
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Controller\BaseController;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_kamchilik_turlari'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Kamchilik_turlari', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Kamchilik_turlariHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'kamchilik_turlari.php');

$controller = BaseController::getInstance('Kamchilik_turlari');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();

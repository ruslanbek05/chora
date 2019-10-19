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

use \Joomla\CMS\MVC\Controller\BaseController;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_departamentlar'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Departamentlar', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('DepartamentlarHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'departamentlar.php');

$controller = BaseController::getInstance('Departamentlar');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();

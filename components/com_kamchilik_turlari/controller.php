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

jimport('joomla.application.component.controller');

use \Joomla\CMS\Factory;

/**
 * Class Kamchilik_turlariController
 *
 * @since  1.6
 */
class Kamchilik_turlariController extends \Joomla\CMS\MVC\Controller\BaseController
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean $cachable  If true, the view output will be cached
	 * @param   mixed   $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController   This object to support chaining.
	 *
	 * @since    1.5
     * @throws Exception
	 */
	public function display($cachable = false, $urlparams = false)
	{
        $app  = Factory::getApplication();
        $view = $app->input->getCmd('view', 'kamchilikturlari');
		$app->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}

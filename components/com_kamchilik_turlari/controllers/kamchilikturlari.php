<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Kamchilik_turlari
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Kamchilikturlari list controller class.
 *
 * @since  1.6
 */
class Kamchilik_turlariControllerKamchilikturlari extends Kamchilik_turlariController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'Kamchilikturlari', $prefix = 'Kamchilik_turlariModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}

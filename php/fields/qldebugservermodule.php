<?php
/**
 * @package        plg_system_qldebug
 * @copyright    Copyright (C) 2017 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('joomla.html.html');
//import the necessary class definition for formfield
jimport('joomla.form.formfield');
require_once JPATH_ROOT . '/plugins/system/qldebug/php/fields/qldebugserver.php';

class JFormFieldQldebugservermodule extends JFormFieldQldebugserver
{
    /**
     * The form field type.
     *
     * @var  string
     * @since 1.6
     */
    protected $type = 'qldebugservermodule'; //the form field type see the name is the same

    protected function getParamsOfExtension()
    {
        if (!isset($_GET['id']) or 0 == $_GET['id']) return array();
        $extensionId = $_GET['id'];
        return $this->askDb('params', '#__modules', '`id`=\'' . $extensionId . '\'');
    }
}
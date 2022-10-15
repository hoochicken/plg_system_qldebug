<?php
/**
 * @package        plg_system_qldebug
 * @copyright    Copyright (C) 2022 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('joomla.html.html');
//import the necessary class definition for formfield
jimport('joomla.form.formfield');

class JFormFieldQldebugserver extends JFormField
{
    /**
     * The form field type.
     *
     * @var  string
     * @since 1.6
     */
    protected $type = 'qldebugserver'; //the form field type see the name is the same

    /**
     * Method to retrieve the lists that resides in your application using the API.
     *
     * @return array The field option objects.
     * @since 1.6
     */
    protected function getInput(): string
    {
        if (!isset($_SERVER)) return JText::_('PLG_SYSTEM_QLDEBUG_MSG_GLOBALNOSERVERVARIABLEFOUND');
        $params = $this->getParamsOfExtension();
        $server = $params->get('server');
        if (!is_array($server)) $server = array();
        $html = array();
        $html[] = '<select name="jform[params][server][]" id="jform_params_server" multiple="multiple">';
        foreach ($_SERVER as $k => $v) {
            $html[] = '<option value="' . $k . '"';
            if (in_array($k, $server)) $html[] = 'selected="selected" ';
            $html[] = '>';
            $html[] = $k;
            $html[] = '</option>';
        }
        $html[] = '</select>';
        return implode("\n", $html);
    }

    protected function getParamsOfExtension()
    {
        if (!isset($_GET['extension_id']) || 0 == $_GET['extension_id']) return array();
        $extensionId = $_GET['extension_id'];
        return $this->askDb('params', '#__extensions', '`extension_id`=\'' . $extensionId . '\'');
    }

    protected function askDb($select, $from, $where, $addition = '')
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($select);
        $query->from($from);
        $query->where($where);
        $db->setQuery($query);
        $result = $db->loadObject();
        $params = new JRegistry();
        $params->loadString($result->params);
        if ($result && isset($result->params)) return $params;
        return false;
    }
}
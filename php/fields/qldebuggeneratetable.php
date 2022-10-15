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

class JFormFieldQldebuggeneratetable extends JFormField
{
    /**
     * The form field type.
     *
     * @var  string
     * @since 1.6
     */
    protected $type = 'qldebuggeneratetable'; //the form field type see the name is the same

    /**
     * Method to retrieve the lists that resides in your application using the API.
     *
     * @return array The field option objects.
     * @since 1.6
     */
    protected function getInput()
    {
        require_once JPATH_SITE . '/plugins/system/qldebug/php/classes/plgSystemQldebugHelper.php';
        $this->obj_helper = new plgSystemQldebugHelper(new stdClass());
        $this->storage = $this->obj_helper->tableStorage;
        $this->generateTable();
        if ($this->obj_helper->checkIfTableExists($this->storage)) return '<a href="' . $this->getUrl() . '&qldebugdroptable=1">' . sprintf(JText::_('PLG_SYSTEM_QLDEBUG_MSG_DROPTABLE'), $this->storage) . '</a>';
        if (!$this->obj_helper->checkIfTableExists($this->storage)) return '<a href="' . $this->getUrl() . '&qldebuggeneratetable=1">' . sprintf(JText::_('PLG_SYSTEM_QLDEBUG_MSG_GENERATETABLE'), $this->storage) . '</a>';
    }

    protected function generateTable()
    {
        $input = JFactory::getApplication()->input;
        if (1 == $input->get('qldebuggeneratetable') && 1 != $this->obj_helper->checkIfTableExists($this->storage)) {
            $this->obj_helper->generateTable($this->storage);
            JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_SYSTEM_QLDEBUG_MSG_TABLEGENERATED'), $this->storage));
            return;
        }
        if (1 == $input->get('qldebugdroptable') && 1 == $this->obj_helper->checkIfTableExists($this->storage)) {
            $this->obj_helper->dropTable($this->storage);
            JFactory::getApplication()->enqueueMessage(sprintf(JText::_('PLG_SYSTEM_QLDEBUG_MSG_TABLEDROPPED'), $this->storage));
            return;
        }
    }

    protected function getUrl()
    {
        $get = [];
        foreach ($_GET as $k => $v) if ('qldebuggeneratetable' != $k && 'qldebugdroptable' != $k) $get[] = $k . '=' . $v;
        return JUri::base() . '?' . implode('&', $get);
    }

}
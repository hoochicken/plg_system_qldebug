<?php
/**
 * @package        plg_system_qldebug
 * @copyright    Copyright (C) 2017 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
defined('JPATH_PLATFORM') or die;

/**
 * Form Rule class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormRuleQldebugdbprotection extends JFormRule
{
    /**
     * The regular expression to use in testing a form field value.
     *
     * @var    string
     * @since  11.1
     */
    //protected $regex = '^[\w.-]+(\+[\w.-]+)*@\w+[\w.-]*?\.\w{2,4}$';

    /**
     * Method to test the email address and optionally check for uniqueness.
     *
     * @param SimpleXMLElement $element The SimpleXMLElement object representing the <field /> tag for the form field object.
     * @param mixed $value The form field value to validate.
     * @param string $group The field name group control value. This acts as as an array container for the field.
     *                                      For example if the field has name="foo" and the group value is set to "bar" then the
     *                                      full field name would end up being "bar[foo]".
     * @param JRegistry $input An optional JRegistry object with the entire data set to validate against the entire form.
     * @param JForm $form The form object for which the field is being tested.
     *
     * @return  boolean  True if the value is valid, false otherwise.
     *
     * @since   11.1
     */
    public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
    {
        if ('' == trim($value)) return true;
        try {
            $msgError = array();
            $arr = array('drop', 'set', 'update', 'delete',);
            foreach ($arr as $v) if (preg_match('/' . $v . '/i', $value)) $msgError[] = sprintf(JText::_('PLG_SYSTEM_QLDEBUG_MSG_DBPROTECTIONINVALIDCOMMAND'), $v);
            if (!preg_match('/select/i', $value)) $msgError[] = JText::_('PLG_SYSTEM_QLDEBUG_MSG_DBPROTECTIONNOSELECT');
            if (preg_match('/\;/i', $value)) $msgError[] = JText::_('PLG_SYSTEM_QLDEBUG_MSG_DBPROTECTIONNOCOLON');

            if (0 < count($msgError)) {
                $msgError = implode('<br />', $msgError);
                $msgError .= '<br />\'' . $value . '\'';
                throw new Exception($msgError);
            }
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            return false;
        }
        return true;
    }
}

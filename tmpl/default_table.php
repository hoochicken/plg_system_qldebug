<?php
/**
 * @package        plg_system_qldebug
 * @copyright    Copyright (C) 2022 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div id="quicklinksTable>" class="<?php echo $v; ?>">
    <?php if (2 != $params->get('output', 1)) require $obj_plugin->getLayoutPath('_headline'); ?>
    <?php /*PRE START*/
    if (1 == $params->get('pre')) {
        echo '<pre';
        if (1 == $params->get('preBreakWord')) echo ' style="white-space:pre-wrap;"';
        echo '>';
    }
    ?>
    <ul>
        <?php
        if (!is_array($obj_plugin->$v) and !is_object($obj_plugin->$v)) : echo sprintf(JText::_('PLG_SYSTEM_QLDEBUG_TABLENOTFOUND'), (string)$params->get('tablename'));
        else :

            foreach ($obj_plugin->$v as $k2 => $v2) :
                echo '<li><a href="#' . $k2 . '">' . ucwords($k2) . '</a></li>';
            endforeach; ?>

            <?php
            foreach ($obj_plugin->$v as $k2 => $v2) :
                echo '<h4 id="' . $k2 . '">' . ucwords($k2) . '</h4>';
                echo '<div style="text-align:right;"><a href="#quicklinksTable">to table overview</a></div>';
                print_r($v2);
            endforeach;
        endif; ?>
    </ul>
    <?php /*PRE STOP*/
    if (1 == $params->get('pre')) echo '</pre>'; ?>
</div>
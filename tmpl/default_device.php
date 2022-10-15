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
<div class="<?php echo $v; ?>">
    <?php if (2 != $params->get('output', 1)) require $obj_plugin->getLayoutPath('_headline'); ?>
    <?php if (1 == $params->get('quicklinks', 0)) require $obj_plugin->getLayoutPath('_totop'); ?>
    <ul>
        <li><?php echo JText::_('PLG_SYSTEM_QLDEBUG_DEVICE_DEVICE'); ?>
            : <?php echo (string)ucwords($this->device->device); ?></li>
        <li><?php echo JText::_('PLG_SYSTEM_QLDEBUG_DEVICE_MOBILE'); ?>
            : <?php if (false == $this->device->mobile) echo JText::_('JNO'); else echo JText::_('JYES'); ?></li>
        <li><?php echo JText::_('PLG_SYSTEM_QLDEBUG_DEVICE_BROWSER'); ?>
            : <?php echo (string)ucwords($this->device->browser); ?></li>
    </ul>
</div>
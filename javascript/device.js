/**
 * @package		plg_system_qldebug
 * @copyright	Copyright (C) 2015 ql.de All rights reserved.
 * @author 		Ingo Holewczuk info@ql.de; Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
jQuery( document ).ready(function()
{
    var html=getSize();
    assignHtml(html)
    jQuery(window).resize(function()
    {
        var html=getSize();
        assignHtml(html)
    });

    function getSize()
    {
        var html='';
        html+=getDevice(jQuery(window).width());
        return html;
    }

    function assignHtml(html)
    {
        jQuery('#deviceContent').html(html);
    }
    function getDevice(width)
    {
        var valueReturn='';
        valueReturn='Desktop <br />&gt;1024px';
        if(width<=1024) valueReturn='iPad landscape <br />width: 1024px';
        if(width<=768) valueReturn='iPad portrait <br />width: 768px';
        if(width<=736) valueReturn='iPhone 6 Plump landscape <br />width: 736px';
        if(width<=568) valueReturn='iPhone 5 landscape <br />width: 568px';
        if(width<=667) valueReturn='iPhone 6 landscape <br />width: 667px';
        if(width<=600) valueReturn='Android (Nexus 4) landscape <br />width: 600px';
        if(width<=414) valueReturn='iPhone 6 Plump portrait <br />width: 414px';
        if(width<=384) valueReturn='Android (Nexus 4) portrait <br />width: 384px';
        if(width<=375) valueReturn='iPhone 6 portrait <br />width: 375px';
        if(width<=320) valueReturn='iPhone 5 portrait <br />Crappy Android landscape <br />width: 320px';
        if(width<=240) valueReturn='Crappy Android portrait <br />width: 240px';
        return valueReturn;
    }
});




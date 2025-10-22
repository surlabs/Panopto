<?php
declare(strict_types=1);

/**
 * This file is part of the Panopto Repository Object plugin for ILIAS.
 * This plugin allows users to embed Panopto videos in ILIAS as repository objects.
 *
 * The Panopto Repository Object plugin for ILIAS is open-source and licensed under GPL-3.0.
 * For license details, visit https://www.gnu.org/licenses/gpl-3.0.en.html.
 *
 * To report bugs or participate in discussions, visit the Mantis system and filter by
 * the category "Panopto" at https://mantis.ilias.de.
 *
 * More information and source code are available at:
 * https://github.com/surlabs/Panopto
 *
 * If you need support, please contact the maintainer of this software at:
 * info@surlabs.es
 *
 */

namespace classes\ui\user;

use connection\PanoptoLTIHandler;
use ilPanoptoPlugin;
use platform\PanoptoException;

/**
 * Class ManageVideosUI
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class ManageVideosUI
{

    /**
     * @var ilPanoptoPlugin
     */
    protected ilPanoptoPlugin $pl;

    /**
     * @throws PanoptoException
     */
    public function render($object): string
    {
        global $DIC;

        $this->pl = ilPanoptoPlugin::getInstance();
        $html = PanoptoLTIHandler::launchTool($object, true, true);

        $DIC['tpl']->addCss($this->pl->getDirectory() . '/templates/default/waiter.css');
        $DIC['tpl']->addJavaScript($this->pl->getDirectory() . '/templates/js/waiter.js');

        $firefox_prompt_html = '<div id="xpan_firefox_prompt" style="display: none; padding: 20px; text-align: center;">
    <p>' . $this->pl->txt('firefox_prompt_info_new_tab') . '</p>
    <button id="xpan_load_videos_btn" class="btn btn-default">' . $this->pl->txt('firefox_prompt_btn_new_tab') . '</button>
</div>';

        $js_logic = "
var panoptoLtiForm = $('#lti_form');
var isFirefox = typeof InstallTrigger !== 'undefined';

if (isFirefox) {
    $('#xpan_firefox_prompt').show();
    $('#basicltiLaunchFrame').hide();

    $('#xpan_load_videos_btn').on('click', function() {
        panoptoLtiForm.attr('target', '_blank');
        panoptoLtiForm.submit();
        $(this).text('" . $this->pl->txt('firefox_prompt_loading_new_tab') . "').prop('disabled', true);
    });
} else {
    srWaiter.show();
    panoptoLtiForm.submit();
}

$('iframe#basicltiLaunchFrame').on('load', function() {
    srWaiter.hide();
});
";

        $DIC['tpl']->addOnLoadCode($js_logic);

        return $html . $firefox_prompt_html . '<div id="sr_waiter" class="sr_waiter"></div>';
    }
}


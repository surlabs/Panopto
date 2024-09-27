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
        $DIC['tpl']->addJavaScript($this->pl->getDirectory() . '/js/waiter.js');
        $DIC['tpl']->addOnLoadCode('$("#lti_form").submit();');
        $DIC['tpl']->addOnLoadCode('srWaiter.show();');
        $DIC['tpl']->addOnLoadCode('$("iframe#basicltiLaunchFrame").load(function(){srWaiter.hide();});');

        return $html . '<div id="sr_waiter" class="sr_waiter"></div>';

    }
}


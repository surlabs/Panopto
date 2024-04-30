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

/**
 * Class ilObjPanoptoGUI
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 * @ilCtrl_isCalledBy ilObjPanoptoGUI: ilRepositoryGUI, ilObjPluginDispatchGUI, ilAdministrationGUI
 * @ilCtrl_Calls      ilObjPanoptoGUI: ilPermissionGUI, ilInfoScreenGUI, ilCommonActionDispatcherGUI
 */
class ilObjPanoptoGUI extends ilObjectPluginGUI
{
    public function getAfterCreationCmd(): string
    {
        return "manageVideos";
    }

    public function getStandardCmd(): string
    {
        return "index";
    }

    public function performCommand(string $cmd): void
    {
        $this->{$cmd}();
    }

    public function getType(): string
    {
        return "xpan";
    }

    public function index(): void
    {
        $this->tpl->setContent('<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1&loop=1&rel=0&cc_load_policy=1&iv_load_policy=3&playlist=dQw4w9WgXcQ" width="560" height="315" title="Rick Astley - Never Gonna Give You Up (Official Music Video)" frameborder="0" allowfullscreen></iframe>');
    }

    public function manageVideos(): void
    {
        $this->tpl->setContent("(En desarrollo) Cargar la página: manageVideos");
    }
}
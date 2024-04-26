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
 * stack@surlabs.es
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
        // TODO: Implement getAfterCreationCmd() method.
    }

    public function getStandardCmd(): string
    {
        // TODO: Implement getStandardCmd() method.
    }

    public function performCommand(string $cmd): void
    {
        // TODO: Implement performCommand() method.
    }

    public function getType(): string
    {
        // TODO: Implement getType() method.
    }
}
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
    /**
     * Get the command to execute after the creation of the object
     * @return string
     */
    public function getAfterCreationCmd(): string
    {
        return "manageVideos";
    }

    /**
     * Get the standard command
     * @return string
     */
    public function getStandardCmd(): string
    {
        return "index";
    }

    /**
     * Execute the command
     * @param string $cmd
     * @return void
     */
    public function performCommand(string $cmd): void
    {
        $this->{$cmd}();
    }

    /**
     * Get the type of the object
     * @return string
     */
    public function getType(): string
    {
        return "xpan";
    }

    /**
     * Show the index page of the object
     * @return void
     */
    public function index(): void
    {
        $this->tpl->setContent("(En desarrollo) Cargar la página: index, con el ID de carpeta: <strong>" . ($this->object->getFolderId() ?? "null") . "</strong> y el ID de referencia: <strong>" . $this->object->getRefId() . "</strong>");
    }

    /**
     * Show the manage videos page
     * @return void
     */
    public function manageVideos(): void
    {
        $this->tpl->setContent("(En desarrollo) Cargar la página: manageVideos");
    }
}
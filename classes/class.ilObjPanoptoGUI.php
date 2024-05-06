<?php
declare(strict_types=1);
use classes\ui\user\UserContentMainUI;
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
 * @ilCtrl_isCalledBy ilObjPanoptoGUI: ilRepositoryGUI, ilObjPluginDispatchGUI, ilAdministrationGUI, UserContentMainUI
 * @ilCtrl_Calls      ilObjPanoptoGUI: ilPermissionGUI, ilInfoScreenGUI, ilCommonActionDispatcherGUI, UserContentMainUI
 */
class ilObjPanoptoGUI extends ilObjectPluginGUI
{

    const TAB_CONTENT = 'content';
    const TAB_INFO = 'info';
    const TAB_VIDEOS = 'videos';
    const TAB_SETTINGS = 'settings';
    const TAB_PERMISSIONS = 'permissions';

    const CMD_STANDARD = 'index';
    const CMD_MANAGE_VIDEOS = 'manageVideos';

    /**
     * @throws ilCtrlException
     * @return void
     */
    protected function setTabs(): void
    {
        global $DIC;
        $lng = $DIC['lng'];

        $DIC->tabs()->addTab(self::TAB_CONTENT, $this->lng->txt(self::TAB_CONTENT), $this->ctrl->getLinkTargetByClass("ilObjPanoptoGUI", UserContentMainUI::CMD_SHOW));
        $DIC->tabs()->addTab(self::TAB_INFO, $this->lng->txt(self::TAB_INFO . '_short'), $this->ctrl->getLinkTargetByClass(ilInfoScreenGUI::class));
        $DIC->tabs()->addSubTab(UserContentMainUI::TAB_SUB_SHOW, "Test", $this->ctrl->getLinkTargetByClass("ilObjPanoptoGUI", UserContentMainUI::CMD_SHOW));

        //Comprobamos el CMD
        if ($this->ctrl->getCmd() == UserContentMainUI::CMD_SHOW) {

            $DIC->tabs()->addSubTab(UserContentMainUI::TAB_SUB_SHOW, "Test", $this->ctrl->getLinkTargetByClass("ilObjPanoptoGUI", UserContentMainUI::CMD_SHOW));
//            $DIC->tabs()->activateTab(UserContentMainUI::TAB_SUB_SHOW);
        }

//        if (ilObjPanoptoAccess::hasWriteAccess()) {
//            $this->tabs_gui->addTab(self::TAB_VIDEOS, $this->plugin->txt('tab_' . self::TAB_VIDEOS), $this->ctrl->getLinkTargetByClass(xpanVideosGUI::class, xpanVideosGUI::CMD_STANDARD));
//            $this->tabs_gui->addTab(self::TAB_SETTINGS, $this->lng->txt(self::TAB_SETTINGS), $this->ctrl->getLinkTargetByClass(xpanSettingsGUI::class, xpanSettingsGUI::CMD_STANDARD));
//        }

//        if ($this->checkPermissionBool("edit_permission")) {
//            $this->tabs_gui->addTab("perm_settings", $lng->txt("perm_settings"), $this->ctrl->getLinkTargetByClass(array(
//                get_class($this),
//                "ilpermissiongui",
//            ), "perm"));
//        }

        //return true;
    }

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
     * @throws ilCtrlException
     */
    public function index(): void
    {
//        $userContentMainUI = new UserContentMainUI();
//        $userContentMainUI->render();
//        UserContentMainUI::render();
//          $this->tpl->setContent("Cargar la página:". UserContentMainUI::CMD_STANDARD);

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
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
    protected UserContentMainUI $userContentMainUI;

    /**
     * @throws ilCtrlException
     * @return void
     */
    protected function setTabs(): void
    {
        $this->tabs->addTab("content", $this->lng->txt("content"), $this->ctrl->getLinkTargetByClass("ilObjPanoptoGUI", "index"));
        $this->tabs->addTab("info", $this->lng->txt('info_short'), $this->ctrl->getLinkTargetByClass(ilInfoScreenGUI::class));

        if (ilObjPanoptoAccess::hasWriteAccess()) {
            $this->tabs->addTab("videos", $this->plugin->txt('tab_videos'), $this->ctrl->getLinkTargetByClass("ilObjPanoptoGUI", "manageVideos"));
            $this->tabs->addTab("settings", $this->lng->txt("settings"), $this->ctrl->getLinkTargetByClass("ilObjPanoptoGUI", "editSettings"));
        }

        if ($this->checkPermissionBool("edit_permission")) {
            $this->tabs->addTab("perm_settings", $this->lng->txt("perm_settings"), $this->ctrl->getLinkTargetByClass(array(
                get_class($this),
                "ilpermissiongui",
            ), "perm"));
        }
    }

    /**
     * Add sub tabs and activate the forwarded sub tab in the parameter.
     *
     * @param string $active_sub_tab
     * @throws ilCtrlException
     */
    protected function addSubTabs(string $active_sub_tab): void
    {
        $this->tabs->addSubTab("subShow",
            $this->plugin->txt('content_show'),
            $this->ctrl->getLinkTarget($this, "index")
        );

        if ($this->access->checkAccess("write", "", $this->parent_id)) {
            $this->tabs->addSubTab("subSorting",
                $this->plugin->txt('content_sorting'),
                $this->ctrl->getLinkTarget($this, "sorting")
            );
        }

        $this->tabs->activateSubTab($active_sub_tab);
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
        $this->tabs->activateTab("content");
        $this->addSubTabs("subShow");

        $this->userContentMainUI = new UserContentMainUI();
        $this->tpl->setContent($this->userContentMainUI->render($this->object));
    }

    /**
     * Show the manage videos page
     * @return void
     */
    public function manageVideos(): void
    {
        $this->tpl->setContent("(En desarrollo) Cargar la página: manageVideos");
    }

    /**
     * Show the edit settings page
     * @return void
     */
    public function editSettings(): void
    {
        $this->tpl->setContent("(En desarrollo) Cargar la página: editSettings");
    }

    /**
     * Show the sorting page
     * @return void
     * @throws ilCtrlException
     */
    public function sorting(): void
    {
        $this->addSubTabs("subSorting");

        $this->tpl->setContent("(En desarrollo) Cargar la página: sorting");
    }
}
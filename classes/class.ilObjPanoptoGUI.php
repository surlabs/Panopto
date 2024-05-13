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


use classes\ui\user\ManageVideosUI;
use classes\ui\user\UserContentMainUI;
use platform\PanoptoException;
use platform\SorterEntry;


/**
 * Class ilObjPanoptoGUI
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 * @ilCtrl_isCalledBy ilObjPanoptoGUI: ilRepositoryGUI, ilObjPluginDispatchGUI, ilAdministrationGUI, UserContentMainUI, PanoptoSortingTableGUI
 * @ilCtrl_Calls      ilObjPanoptoGUI: ilPermissionGUI, ilInfoScreenGUI, ilCommonActionDispatcherGUI, UserContentMainUI, PanoptoSortingTableGUI
 */
class ilObjPanoptoGUI extends ilObjectPluginGUI
{
    protected UserContentMainUI $userContentMainUI;

    protected ManageVideosUI $manageVideosUI;

    /**
     * @throws ilCtrlException
     * @return void
     */
    protected function setTabs(): void
    {
        $this->tabs->addTab("content", $this->lng->txt("content"), $this->ctrl->getLinkTargetByClass("ilObjPanoptoGUI", "index"));
        $this->tabs->addTab("info_short", $this->lng->txt('info_short'), $this->ctrl->getLinkTargetByClass(array(
            get_class($this),
            "ilInfoScreenGUI",
        ), "showSummary"));

        if (ilObjPanoptoAccess::hasWriteAccess()) {
            $this->tabs->addTab("videos", $this->plugin->txt('tab_videos'), $this->ctrl->getLinkTargetByClass("ilObjPanoptoGUI", "manageVideos"));
            $this->tabs->addTab("settings", $this->lng->txt("settings"), $this->ctrl->getLinkTargetByClass("ilObjPanoptoGUI", "editSettings"));
        }

        if ($this->checkPermissionBool("edit_permission")) {
            $this->tabs->addTab("perm_settings", $this->lng->txt("perm_settings"), $this->ctrl->getLinkTargetByClass(array(
                get_class($this),
                "ilPermissionGUI",
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
     */
    public function index(): void
    {

        try{
            $this->tabs->activateTab("content");
            $this->userContentMainUI = new UserContentMainUI();
            $this->tpl->setContent($this->userContentMainUI->render($this->object, $this));
            $this->addSubTabs("subShow");

        } catch (Exception $e) {
            $this->tpl->setOnScreenMessage("failure", $e->getMessage(), true);

        }
    }

    /**
     * Show the manage videos page
     * @return void
     * @throws PanoptoException
     */
    public function manageVideos(): void
    {
        $this->tabs->activateTab("videos");

        $this->manageVideosUI = new manageVideosUI();
        $this->tpl->setContent($this->manageVideosUI->render($this->object));

    }

    /**
     * Show the edit settings page
     * @return void
     * @throws ilCtrlException
     */
    public function editSettings(): void
    {
        $this->tabs->activateTab("settings");

        $this->tpl->setContent($this->initSettingsForm());
    }

    /**
     * Initialize the settings form
     * @return string
     * @throws ilCtrlException
     */
    public function initSettingsForm(): string
    {
        $form = new ilPropertyFormGUI();
        $form->setFormAction($this->ctrl->getFormAction($this));
        $form->setTitle($this->lng->txt("settings"));

        $title = new ilTextInputGUI($this->lng->txt("title"), "title");
        $title->setRequired(true);
        $title->setValue($this->object->getTitle());
        $form->addItem($title);

        $description = new ilTextAreaInputGUI($this->lng->txt("description"), "description");
        $description->setValue($this->object->getDescription());
        $form->addItem($description);

        $online = new ilCheckboxInputGUI($this->lng->txt("online"), "online");
        $online->setChecked($this->object->getOfflineStatus() == false);
        $form->addItem($online);

        $form->addCommandButton("saveSettings", $this->lng->txt("save"));

        return $form->getHTML();
    }

    /**
     * Save the settings
     * @return void
     * @throws ilCtrlException
     */
    public function saveSettings(): void {
        if (isset($_POST['title'])) {
            $this->object->setTitle($_POST['title']);
        }

        if (isset($_POST['description'])) {
            $this->object->setDescription($_POST['description']);
        }

        if (isset($_POST['online'])) {
            $this->object->setOfflineStatus(!$_POST['online']);
        }

        $this->object->update();

        $this->tpl->setOnScreenMessage("success", $this->lng->txt("msg_obj_modified"), true);

        $this->ctrl->redirect($this, "editSettings");
    }

    /**
     * Show the sorting page
     * @return void
     * @throws ilCtrlException
     * @throws Exception
     */
    public function sorting(): void
    {
        $this->addSubTabs("subSorting");
        $sort_table_gui = new PanoptoSortingTableGUI($this->object, $this);
        $this->tpl->setContent($sort_table_gui->getHTML());
    }

    /**
     * @throws ilObjectException
     */
    protected function initHeader($render_locator = true): void
    {
        if ($render_locator) {
            $this->setLocator();
        }
        $this->tpl->setTitleIcon(ilObjPanopto::_getIcon($this->object_id));
        $this->tpl->setTitle($this->object->getTitle());
        $this->tpl->setDescription($this->object->getDescription());

        if (ilObjPanoptoAccess::_isOffline($this->object->getId())) {
            /**
             * @var $list_gui ilObjPanoptoListGUI
             */
            $list_gui = ilObjectListGUIFactory::_getListGUIByType('xpan');
            $this->tpl->setAlertProperties($list_gui->getAlertProperties());
        }

    }

    /**
     * @throws PanoptoException
     */
    public function reorder()
    {
        if (isset($_POST['ids'])) {
            $ids = $_POST['ids'];

            if (!empty($ids)) {
                SorterEntry::saveOrder($ids, $this->object->getFolderExtId());
            }
        }
    }

}
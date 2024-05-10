<?php
declare(strict_types=1);

use classes\ui\user\ManageVideosUI;
use classes\ui\user\UserContentMainUI;
use connection\PanoptoClient;
use platform\PanoptoException;

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
     * @throws ilObjectException
     * @throws PanoptoException
     */
    public function manageVideos(): void
    {
        if (!$this->ctrl->isAsynch()) {
            $this->initHeader();
        }
        $this->tabs->activateTab("videos");

        $this->manageVideosUI = new manageVideosUI();
        $this->tpl->setContent($this->manageVideosUI->render($this->object));

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
     * @throws ilAtomQueryException
     */
    public function reorder()
    {
//        global $DIC;
//        echo "HOla";
//        exit;
//        $atom_query = new ilAtomQueryLock($DIC->database());
//        $atom_query->addTableLock(SorterEntry::TABLE_NAME);
//        $atom_query->addTableLock(SorterEntry::TABLE_NAME . '_seq');
//        $atom_query->addQueryCallable(function(ilDBInterface $db) {
//            $ids = $_POST['ids'];
//            $precedence = 1;
//
//            $existingEntries = SorterEntry::where(["ref_id" => $this->getObject()->getReferenceId()]);
//
//            // Delete previous entries
//            if ($existingEntries->hasSets()) {
//                foreach ($existingEntries->get() as $entry) {
//                    $entry->delete();
//                }
//            }
//
//            foreach ($ids as $id) {
//                $entry = new SorterEntry();
//                $entry->setRefId($this->getObject()->getReferenceId());
//                $entry->setPrecedence($precedence);
//                $entry->setObjectId($id);
//                $entry->create();
//                $precedence++;
//            }
//
//            //echo "{\"success\": true}";
//            //exit;
//        });
//        $atom_query->run();
    }

}
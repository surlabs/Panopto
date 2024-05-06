<?php
declare(strict_types=1);

namespace classes\ui\user;
use connection\PanoptoClient;

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
 * Class UserContentMainUI
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class UserContentMainUI
{
    const CMD_SHOW = "index";
    const CMD_SORTING = "sorting";
    const TAB_SUB_SHOW = "subShow";
    const TAB_SUB_SORTING = "subSorting";

    /**
     * @var PanoptoClient
     */
    protected PanoptoClient $client;
    /**
     * @var String
     */
    protected string $folder_id;

    protected $tpl;

    /**
     * @throws \ilCtrlException
     */
    public static function render(): string
    {
//        $this->addSubTabs(self::TAB_SUB_SHOW);

        // Render the content
        $content_objects = $this->client->getContentObjectsOfFolder(
            $this->folder_id,
            true,
            $_GET['xpan_page'],
            $this->getObject()->getReferenceId());


    }


    /**
     * @throws \ilCtrlException
     */
    protected function addSubTabs($active_sub_tab): void
    {
        global $DIC;

        $DIC->tabs()->addSubTab(self::TAB_SUB_SHOW,
            "Test",
            $DIC->ctrl()->getLinkTarget($this, self::CMD_SHOW)
        );

        if ($DIC->access()->checkAccess("write", "", $this->parent_gui->getRefId())) {
            $DIC->tabs()->addSubTab(self::TAB_SUB_SORTING,
                "Test 2",
                $DIC->ctrl()->getLinkTarget($this, self::CMD_SORTING)
            );
        }

        $DIC->tabs()->activateSubTab($active_sub_tab);
    }
    //TODO: Ver como resolver la implementación del getObject() en esta clase
//    public function getObject() {
//        return $this->parent_gui->getObject();
//    }


}


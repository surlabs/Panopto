<?php
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

use connection\PanoptoClient;
use utils\DTO\ContentObject;

/**
 * Class PanoptoSortingTableGUI
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class PanoptoSortingTableGUI extends ilTable2GUI
{

    const TBL_ROW_TEMPLATE_NAME = "tpl.sorting_row.html";
    const TBL_ROW_TEMPLATE_DIR = "/templates/table_rows/";
    const JS_FILES_TO_EMBED
        = [
            "/templates/js/sortable.js",
        ];
    const CSS_FILES_TO_EMBED
        = [
            "/templates/default/sorting_table.css",
        ];

    /**
     * @var PanoptoClient
     */
    protected PanoptoClient $client;
    /**
     * @var String
     */
    protected string $folder_id;

    /**
     * @var ilPanoptoPlugin
     */
    private ilPanoptoPlugin $pl;



    /**
     * xpanTableGUI constructor.
     * @param                 $a_parent_obj
     * @throws Exception
     */
    public function __construct($a_parent_obj, $a_parent_gui)
    {
        parent::__construct($a_parent_gui);
        $this->pl = ilPanoptoPlugin::getInstance();
        $this->client = PanoptoClient::getInstance();

        $plugin_dir = $this->pl->getDirectory();

        $this->initColumns($this->pl);
        $this->setRowTemplate($this->pl->getDirectory() . self::TBL_ROW_TEMPLATE_DIR . self::TBL_ROW_TEMPLATE_NAME, $plugin_dir);

        $this->setExternalSorting(true);
        $this->setExternalSegmentation(true);
        $this->setShowRowsSelector(true);


        $this->applyFiles($plugin_dir, $a_parent_gui);
//        dump($this->getHTML());
//        exit;

        $folder = $this->client->getFolderByExternalId($a_parent_obj->getFolderExtId());
        if (!$folder) {
            throw new ilException('No external folder found for this object.');
        }
        $this->folder_id = $folder->getId();

        $objects = PanoptoClient::getInstance()->getContentObjectsOfFolder($this->folder_id, false, 0, $a_parent_obj->getReferenceId());
        $this->parseData($objects);

    }


    /**
     * @param ilPanoptoPlugin $pl
     */
    protected function initColumns(ilPanoptoPlugin $pl): void
    {
        $this->addColumn("", 'move_icon');
        $this->addColumn($pl->txt('content_thumbnail'));
        $this->addColumn($pl->txt('content_title'));
        $this->addColumn($pl->txt('content_description'));
    }

    /**
     * @param ContentObject $content_object
     */
    protected function fillRow($content_object): void
    {
        $this->tpl->setVariable("VAL_THUMBNAIL", $content_object->getThumbnailUrl());
        $this->tpl->setVariable("VAL_TITLE", $content_object->getTitle());
        $this->tpl->setVariable("VAL_DESCRIPTION", $content_object->getDescription());
        $this->tpl->setVariable("VAL_MID", $content_object->getId());
    }


    /**
     * @param string $plugin_dir
     * @throws ilCtrlException
     */
    protected function applyFiles(string $plugin_dir, $parent_gui): void
    {
        global $DIC;
        $main_tpl = $DIC->ui()->mainTemplate();

        foreach (self::JS_FILES_TO_EMBED as $pathSuffix) {
            $main_tpl->addJavaScript($plugin_dir . $pathSuffix);
        }

        foreach (self::CSS_FILES_TO_EMBED as $pathSuffix) {
            $main_tpl->addCss($plugin_dir . $pathSuffix);
        }

        $base_link = $this->ctrl->getLinkTarget($parent_gui, '', '', true);
        $main_tpl->addOnLoadCode('PanoptoSorter.init("' . $base_link . '");');
    }

    /**
     * @param ContentObject[] $content_objects
     */
    protected function parseData(array $content_objects): void
    {
        $this->setData($content_objects);
    }
}

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
 * Class ilObjPanoptoListGUI
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class ilObjPanoptoListGUI extends ilObjectPluginListGUI
{
    /**
     * Get the GUI class of the plugin
     * @return string
     */
    public function getGuiClass(): string
    {
        return ilObjPanoptoGUI::class;
    }

    /**
     * Init commands
     * @return array[]
     */
    public function initCommands(): array
    {
        $this->timings_enabled = false;
        $this->subscribe_enabled = false;
        $this->link_enabled = false;
        $this->info_screen_enabled = true;
        $this->delete_enabled = true;
        $this->cut_enabled = false;
        $this->copy_enabled = true;

        return [
            [
                "permission" => "read",
                "cmd" => "index",
                "default" => true,
            ],
            [
                "permission" => "write",
                "cmd" => "manageVideos",
                "txt" => $this->txt("tab_videos"),
            ],
            [
                "permission" => "write",
                "cmd" => "editSettings",
                "txt" => $this->lng->txt("settings"),
            ],
        ];
    }

    /**
     * Get the type of the object
     * @return void
     */
    public function initType(): void
    {
        $this->setType("xpan");
    }
}
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
 * Class ilPanoptoPlugin
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class ilPanoptoPlugin extends ilRepositoryObjectPlugin
{

    protected static ilPanoptoPlugin $instance;

    const PLUGIN_NAME = 'Panopto';

    /**
     * Remove the plugin related data from the database
     * @return void
     */
    protected function uninstallCustom(): void
    {
        global $DIC;
        $DIC->database()->dropTable("xpan_config");
        $DIC->database()->dropTable("xpan_order");
        $DIC->database()->dropTable("xpan_objects");
    }


    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getPluginName()
    {
        return self::PLUGIN_NAME;
    }
}
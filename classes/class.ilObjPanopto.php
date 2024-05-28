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

use platform\PanoptoDatabase;
use platform\PanoptoException;

/**
 * Class ilObjPanopto
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class ilObjPanopto extends ilObjectPlugin
{
    private bool $online;
    private int $folder_ext_id;

    /**
     * Create a new object
     * @param bool $clone_mode
     * @throws PanoptoException
     */
    protected function doCreate(bool $clone_mode = false): void
    {
        $xpanDb = new PanoptoDatabase();

        $xpanDb->insert("xpan_objects", [
            "obj_id" => $this->getId(),
            "is_online" => 0,
            "folder_ext_id" => $this->getFolderExtId()
        ]);
    }

    /**
     * Read the object from the database
     * @throws PanoptoException
     */
    protected function doRead(): void
    {
        $xpanDb = new PanoptoDatabase();
        $result = $xpanDb->select("xpan_objects", ["obj_id" => $this->getId()], ["is_online", "folder_ext_id"]);

        if (empty($result)) {
            $this->doCreate();

            $this->online = false;
            $this->folder_ext_id = $this->getFolderExtId();
        } else {
            $this->online = (bool)$result[0]["is_online"];
            $this->folder_ext_id = (int)$result[0]["folder_ext_id"];
        }
    }

    /**
     * Update the object in the database
     * @throws PanoptoException
     */
    protected function doUpdate(): void
    {
        $xpanDb = new PanoptoDatabase();
        $xpanDb->update("xpan_objects", ["is_online" => (int)$this->online, "folder_ext_id" => $this->folder_ext_id], ["obj_id" => $this->getId()]);
    }

    /**
     * Set the type of the object as the id of the plugin
     * @return void
     */
    protected function initType(): void
    {
        $this->setType("xpan");
    }

    /**
     * Get the online status of the object
     * @return bool
     */
    public function isOnline(): bool
    {
        return $this->online;
    }

    /**
     * Set the online status of the object
     * @param bool $online
     */
    public function setOnline(bool $online): void
    {
        $this->online = $online;
    }

    /**
     * Get the reference id of the object
     * @return int
     */
    public function getReferenceId(): int
    {
        return $this->getRefId() ?: self::_getAllReferences($this->getId())[0];
    }

    /**
     * Get the folder external id
     * @return int
     * @throws PanoptoException
     */
    public function getFolderExtId(): int
    {
        if (!isset($this->folder_ext_id) || $this->folder_ext_id != $this->getRefId()) {
            $xpanDb = new PanoptoDatabase();

            $this->folder_ext_id = $this->getRefId();

            $xpanDb->update("xpan_objects", ["folder_ext_id" => $this->folder_ext_id], ["obj_id" => $this->getId()]);
        }

        return $this->folder_ext_id;
    }
}
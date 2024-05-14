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
 * Class ilObjPanoptoAccess
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class ilObjPanoptoAccess extends ilObjectPluginAccess
{
    /**
     * Instance of ilObjPanoptoAccess
     * @var ilObjPanoptoAccess|null
     */
    protected static ?ilObjPanoptoAccess $instance = null;


    /**
     * Get instance of ilObjPanoptoAccess
     * @return ilObjPanoptoAccess
     */
    public static function getInstance(): ilObjPanoptoAccess
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Check if the user has write access
     * @param string|null $ref_id
     * @return bool
     * @throws PanoptoException
     */
    public static function hasWriteAccess(?string $ref_id = null) : bool
    {
        return self::checkAccess("write", "write", $ref_id);
    }

    /**
     * Check if the user has X access
     *
     * @param string $cmd
     * @param string $permission
     * @param int|null $ref_id
     * @param int|null $obj_id
     * @param int|null $user_id
     *
     * @return bool
     * @throws PanoptoException
     */
    protected static function checkAccess(string $cmd, string $permission, ?int $ref_id, ?int $obj_id = null, ?int $user_id = null): bool
    {
        return self::getInstance()->_checkAccess($cmd, $permission, $ref_id, $obj_id, $user_id);
    }

    /**
     * Check internal access
     *
     * @param string $cmd
     * @param string $permission
     * @param int|null $ref_id
     * @param int|null $obj_id
     * @param int|null $user_id
     * @return bool
     * @throws PanoptoException
     */
    public function _checkAccess(string $cmd, string $permission, ?int $ref_id, ?int $obj_id, ?int $user_id = null): bool {
        if ($ref_id === NULL) {
            $ref_id = (int) filter_input(INPUT_GET, "ref_id");
        }

        if ($obj_id === NULL) {
            $obj_id = ilObjPanopto::_lookupObjectId($ref_id);
        }

        if ($user_id == NULL) {
            $user_id = $this->user->getId();
        }

        switch ($permission) {
            case "visible":
            case "read":
                return (($this->access->checkAccessOfUser($user_id, $permission, "", $ref_id) && !self::_isOffline($obj_id))
                    || $this->access->checkAccessOfUser($user_id, "write", "", $ref_id));

            case "delete":
                return ($this->access->checkAccessOfUser($user_id, "delete", "", $ref_id)
                    || $this->access->checkAccessOfUser($user_id, "write", "", $ref_id));

            case "write":
            case "edit_permission":
            default:
                return $this->access->checkAccessOfUser($user_id, $permission, "", $ref_id);
        }
    }

    /**
     * Check if the object is offline
     * @param int $obj_id
     * @return bool
     * @throws PanoptoException
     */
    public static function _isOffline(int $obj_id): bool
    {
        $xpanDb = new PanoptoDatabase();
        $result = $xpanDb->select("xpan_objects", ["obj_id" => $obj_id], ["is_online"]);

        return empty($result) || (int) $result[0]["is_online"] === 0;
    }
}
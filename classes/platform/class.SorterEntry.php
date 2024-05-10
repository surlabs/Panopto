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

namespace platform;

/**
 * Class SorterEntry
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class SorterEntry
{
    /**
     * @param array $objects
     * @param int $ref_id
     * @return array
     * @throws PanoptoException
     */
    public static function generateSortedObjects(array $objects, int $ref_id = 0) : array
    {
        $sorted = [];

        if (count($objects) > 0) {
            $order = (new PanoptoDatabase)->select("xpan_order", array(
                "ref_id" => $ref_id
            ), array("session_id"), "ORDER BY `position` ASC");

            foreach ($order as $o) {
                foreach ($objects as $key => $object) {
                    if ($object->getId() == $o["session_id"]) {
                        $sorted[] = $object;
                        unset($objects[$key]);
                        break;
                    }
                }
            }

            $sorted = array_merge($sorted, $objects);
        }

        return $sorted;
    }

    /**
     * @param array $ids
     * @param int $ref_id
     * @throws PanoptoException
     */
    public static function saveOrder(array $ids, int $ref_id = 0) : void
    {
        $db = new PanoptoDatabase();

        $db->delete("xpan_order", ["ref_id" => $ref_id]);

        foreach ($ids as $key => $id) {
            $db->insert("xpan_order", [
                "ref_id" => $ref_id,
                "position" => $key,
                "session_id" => $id,
            ]);
        }
    }
}
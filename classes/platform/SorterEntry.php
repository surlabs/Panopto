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
 * Class PanoptoRender
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class SorterEntry
{

    /**
     * @param array $objects
     * @param int   $ref_id
     * @return array
     */
    public static function generateSortedObjects(array $objects, int $ref_id = 0) : array
    {
        $sorted = [];

        if (count($objects) > 0) {
//            dump($objects);

            $sorted = $objects;

//            dump($objects); exit();
        }

        return $sorted;
    }
}
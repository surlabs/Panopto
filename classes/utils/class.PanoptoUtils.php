<?php

namespace utils;
use ilObjPanopto;
use ilObjUser;
use platform\PanoptoConfig;
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
 * Class PanoptoUtils
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class PanoptoUtils
{
    /**
     * @throws PanoptoException
     */
    public static function getUserIdentifier($user_id = 0): string
    {
        global $DIC;
        $user = $user_id ? new ilObjUser($user_id) : $DIC->user();
        return match (PanoptoConfig::get('user_id')) {
            'login' => $user->getLogin(),
            'email' => $user->getEmail(),
            default => $user->getExternalAccount(),
        };
    }

    public static function getExternalIdOfObjectById($ref_id = 0): string
    {
        $ref_id = $ref_id ? $ref_id : $_GET['ref_id'];
        return ilObjPanopto::_lookupTitle(ilObjPanopto::_lookupObjId($_GET['ref_id'])) . ' (ID: ' . $ref_id . ')';
    }
}
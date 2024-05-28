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

namespace utils\DTO;

/**
 * Class ContentObjectBuilder
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class ContentObjectBuilder
{

    /**
     * @param array $results
     * @return Playlist[]
     */
    public static function buildPlaylistDTOsFromArray(array $results): array
    {
        $playlists = [];
        foreach ($results as $result) {
            $playlists[] = new Playlist($result['Id'], $result['Name'], $result['Description'], $result['Urls']['ThumbnailUrl']);
        }
        return $playlists;
    }

    /**
     * @param \Panopto\SessionManagement\Session[] $sessions
     * @return Session[]
     */
    public static function buildSessionsDTOsFromSessions(array $sessions): array
    {
        $sessions_array = [];
        foreach ($sessions as $session) {
            $sessions_array[] = new Session(
                $session->getId(),
                $session->getName(),
                $session->getDescription() ?? '',
                $session->getThumbUrl(),
                $session->getDuration());
        }
        return $sessions_array;
    }

    /**
     * @param array $array
     * @return Session
     */
    public static function buildSessionDTOFromArray(array $array): Session
    {
        return new Session(
            $array['Id'],
            $array['Name'],
            $array['Description'] ?? '',
            $array['Urls']['ThumbnailUrl'] ?? '',
            $array['Duration']
        );
    }

    /**
     * @param array $array
     * @return Session[]
     */
    public static function buildSessionDTOsFromArray(array $array): array
    {
        $sessions = [];
        foreach ($array as $item) {
            $sessions[] = self::buildSessionDTOFromArray($item);
        }
        return $sessions;
    }
}

<?php
namespace utils\DTO;

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

class Session extends ContentObject
{
    /**
     * @var int
     */
    protected $duration;

    /**
     * Session constructor.
     * @param string $id
     * @param string $title
     * @param string $description
     * @param string $thumbnail_url
     * @param int    $duration
     */
    public function __construct(string $id, string $title, string $description, string $thumbnail_url, ?int $duration)
    {
        $this->duration = $duration;
        parent::__construct($id, $title, $description, $thumbnail_url);
    }

    /**
     * @return int
     */
    public function getDuration() : ?int
    {
        return $this->duration;
    }


}

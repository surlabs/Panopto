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
 * Class ContentObject
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class ContentObject
{
    /**
     * @var string
     */
    protected string $id;
    /**
     * @var string
     */
    protected string $title;
    /**
     * @var string
     */
    protected string $description;
    /**
     * @var string
     */
    protected string $thumbnail_url;

    /**
     * ContentObject constructor.
     * @param string $id
     * @param string $title
     * @param string $description
     * @param string $thumbnail_url
     */
    public function __construct(string $id, string $title, $description, string $thumbnail_url)
    {
        $this->id = $id;
        $this->title = $title;
        $this->thumbnail_url = $thumbnail_url;
        $this->description = (string)$description;
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getThumbnailUrl() : string
    {
        return $this->thumbnail_url;
    }


}

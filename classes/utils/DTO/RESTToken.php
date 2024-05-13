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

use stdClass;

/**
 * Class RESTToken
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class RESTToken
{
    /**
     * @var string
     */
    private string $access_token;
    /**
     * @var int
     */
    private int $expiry;

    /**
     * RESTToken constructor.
     * @param string $access_token
     * @param int    $expiry
     */
    public function __construct(string $access_token, int $expiry)
    {
        $this->access_token = $access_token;
        $this->expiry = $expiry;
    }

    /**
     * @return string
     */
    public function getAccessToken() : string
    {
        return $this->access_token;
    }

    /**
     * @return int
     */
    public function getExpiry() : int
    {
        return $this->expiry;
    }

    public function isExpired() : bool
    {
        return time() > $this->expiry;
    }

    public function jsonSerialize() : string
    {
        $std_class = new stdClass();
        $std_class->access_token = $this->access_token;
        $std_class->expiry = $this->expiry;
        return json_encode($std_class);
    }

    public static function jsonUnserialize(string $json) : self
    {
        $decoded = json_decode($json);
        return new self($decoded->access_token, $decoded->expiry);
    }
}

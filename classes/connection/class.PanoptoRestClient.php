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

namespace connection;

use platform\PanoptoConfig;
use platform\PanoptoException;
use connection\PanoptoTokenHandler as RESTToken;
use League\OAuth2\Client\Provider\GenericProvider as OAuth2Provider;

/**
 * Class PanoptoClient
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class PanoptoRestClient
{
    /**
     * @var self
     */
    protected static PanoptoRestClient $instance;
    private string $base_url;
    private OAuth2Provider $oauth2_provider;
    private PanoptoTokenHandler $token;


    /**
     * @return self
     */
    public static function getInstance(): PanoptoRestClient
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @throws PanoptoException
     */
    public function __construct() {
//        $this->log = xpanLog::getInstance();
        $host = PanoptoConfig::get('hostname');
        if (str_starts_with($host, 'https://')) {
            $host = substr($host, 8);
        }
        $this->base_url = 'https://' . rtrim($host, '/');
        $this->oauth2_provider = new OAuth2Provider(array(
            'clientId' => PanoptoConfig::get('rest_client_id'),
            'clientSecret' => PanoptoConfig::get('rest_client_secret'),
            'urlAccessToken' => $this->base_url . '/Panopto/oauth2/connect/token',
            'urlAuthorize' => '',
            'urlResourceOwnerDetails' => ''
        ));
        $this->loadToken();
    }

    private function loadToken(): void
    {
        $token = xpanConfig::getToken();
        if (!$token || $token->isExpired()) {
            $this->log('fetch access token');
            $oauth2_token = $this->oauth2_provider->getAccessToken("password", [
                "username" => xpanConfig::getConfig(xpanConfig::F_REST_API_USER),
                "password" => xpanConfig::getConfig(xpanConfig::F_REST_API_PASSWORD),
                "scope" => "api"
            ]);
            $token = new RESTToken($oauth2_token->getToken(), $oauth2_token->getExpires());
            PanoptoConfig::set('rest_token', $token);
        }
        $this->token = $token;
    }

    /**
     * @param string $folder_id
     * @return Playlist[]
     * @throws ilException
     */
    public function getPlaylistsOfFolder(string $folder_id) : array
    {
        $response = $this->get('/Panopto/api/v1/folders/' . $folder_id . '/playlists');
        return ContentObjectBuilder::buildPlaylistDTOsFromArray($response["Results"]);
    }

    /**
     * @param string $playlist_id
     * @return array
     * @throws ilException
     */
    public function getSessionsOfPlaylist(string $playlist_id) : array
    {
        $response = $this->get('/Panopto/api/v1/playlists/' . $playlist_id . '/sessions');
        return ContentObjectBuilder::buildSessionDTOsFromArray($response['Results']);
    }

    /**
     * @param string $playlist_id
     * @return string
     * @throws ilException
     */
    public function getFolderIdOfPlaylist(string $playlist_id) : string
    {
        $response = $this->get('/Panopto/api/v1/playlists/' . $playlist_id);
        if (!isset($response['Folder']['Id'])) {
            throw new ilException('Panopto REST: could not fetch folder id of playlist ' . $playlist_id);
        }
        return $response['Folder']['Id'];
    }

    /**
     * @param string $relative_url
     * @return array
     * @throws ilException
     */
    private function get(string $relative_url) : array
    {
        $this->log('GET ' . $relative_url);
        $url = $this->base_url . $relative_url;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $this->token->getAccessToken()]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        if ($response === false) {
            throw new ilException('Panopto REST: curl error nr: ' . curl_errno($curl) . ', message: ' . curl_error($curl));
        }
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_status >= 300) {
            $message = is_string($response) ? $response : (is_array($response) ? print_r($response, true) : '');
            throw new ilException('Panopto REST: error response from Panopto server, status ' . $http_status . ', message: ' . $message);
        }
        return json_decode($response, true);
    }

    public static function jsonUnserializeToken(string $json) : self
    {
        $decoded = json_decode($json);
        return new self($decoded->access_token, $decoded->expiry);
    }

    private function log(string $message)
    {
        $this->log->write('Panopto REST Client: ' . $message);
    }


}
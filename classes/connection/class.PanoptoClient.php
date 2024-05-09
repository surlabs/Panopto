<?php
declare(strict_types=1);

namespace connection;
require_once __DIR__."/../../vendor/autoload.php";

use Panopto\AccessManagement\AccessManagement;
use Panopto\AccessManagement\AccessRole;
use Panopto\Client as PanoptoClientAPI;
use Panopto\AccessManagement\FolderAccessDetails;
use Panopto\AccessManagement\GetFolderAccessDetails;
use Panopto\AccessManagement\GetSessionAccessDetails;
use Panopto\AccessManagement\GetUserAccessDetails;
use Panopto\AccessManagement\GrantUsersAccessToFolder;
use Panopto\AccessManagement\GrantUsersViewerAccessToSession;
use Panopto\AccessManagement\UserAccessDetails;
use Panopto\SessionManagement\ArrayOfSessionState;
use Panopto\SessionManagement\Folder;
use Panopto\SessionManagement\GetAllFoldersByExternalId;
use Panopto\SessionManagement\GetSessionsList;
use Panopto\SessionManagement\ListSessionsRequest;
use Panopto\SessionManagement\SessionManagement;
use Panopto\SessionManagement\SessionState;
use Panopto\UserManagement\CreateUser;
use Panopto\UserManagement\GetUserByKey;
use Panopto\UserManagement\SyncExternalUser;
use Panopto\UserManagement\User;
use Panopto\UserManagement\UserManagement;
use Panopto\AccessManagement\SessionAccessDetails;
use Panopto\SessionManagement\Pagination;
use utils\DTO\ContentObjectBuilder;
use connection\PanoptoRestClient;
use Exception;
use Panopto\SessionManagement\Session;
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
 * Class PanoptoClient
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class PanoptoClient
{
//    const ROLE_VIEWER = AccessRole::Viewer;
//    const ROLE_VIEWER_WITH_LINK = AccessRole::ViewerWithLink;
//    const ROLE_CREATOR = AccessRole::Creator;
//    const ROLE_PUBLISHER = AccessRole::Publisher;

    /**
     * @var self
     */
    protected static PanoptoClient $instance;


    /**
     * @return self
     */
    public static function getInstance(): PanoptoClient
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @var Client
     */
    protected $panoptoclient;
    /**
     * @var \Panopto\stdClass
     */
    protected $auth;
    /**
     * @var PanoptoRestClient
     */
    protected $rest_client;
//    /**
//     * @var xpanLog
//     */
//    protected $log;

    /**
     * xpanClient constructor.
     * @throws PanoptoException
     */
    public function __construct() {
//        $this->log = xpanLog::getInstance();

        $arrContextOptions=array("ssl"=>array( "verify_peer"=>false, "verify_peer_name"=>false));
        $this->panoptoclient = new PanoptoClientAPI(PanoptoConfig::get('hostname'), array('trace' => 1, 'stream_context' => stream_context_create($arrContextOptions)));
        $this->panoptoclient->setAuthenticationInfo(PanoptoConfig::getApiUserKey(), '', PanoptoConfig::get('application_key'));
        $this->auth = $this->panoptoclient->getAuthenticationInfo();
        $this->rest_client = PanoptoRestClient::getInstance();

    }

    public function getContentObjectsOfFolder($folder_id, $page_limit = false, $page = 0, int $ref_id = 0) : array
    {
        $perpage = 10;
        $request = new ListSessionsRequest();
        $request->setFolderId($folder_id);

        $pagination = new Pagination();
        $pagination->setMaxNumberResults(999);
        $pagination->setPageNumber(0);
        $request->setPagination($pagination);

        $states = new ArrayOfSessionState();
        $states->setSessionState(array( SessionState::Complete, SessionState::Broadcasting, SessionState::Scheduled ));
        $request->setStates($states);

//        $this->log->write('*********');
//        $this->log->write('SOAP call "GetSessionsList"');
//        $this->log->write("request:");
//        $this->log->write(print_r($request, true));

        $params = new GetSessionsList(
            $this->auth,
            $request,
            ''
        );

        /** @var SessionManagement $session_client */
        $session_client = $this->panoptoclient->SessionManagement();
        try {
            $sessions_result = $session_client->GetSessionsList($params);
        } catch (Exception $e) {
//            $this->logException($e, $session_client);
            throw $e;
        }

        $sessions = $sessions_result->getGetSessionsListResult();

//        $this->log->write('Status: ' . substr($session_client->__last_response_headers, 0, strpos($session_client->__last_response_headers, "\r\n")));
//        $this->log->write('Received ' . $sessions->getTotalNumberResults() . ' object(s).');
        

        $sessions = ContentObjectBuilder::buildSessionsDTOsFromSessions($sessions->getResults()->getSession() ?? []);
        $playlists = $this->rest_client->getPlaylistsOfFolder($folder_id);
        $objects = array_merge($sessions, $playlists);
//        $objects = SorterEntry::generateSortedObjects($objects, $ref_id);
        //TODO: Buscar SorterEntry y aplicarlo.
        if ($page_limit) {
            // Implement manual pagination
            return array(
                "count"    => count($objects),
                "objects" => array_slice($objects, $page * $perpage, $perpage),
            );
        } else {
            return $objects;
        }

    }

    /**
     * @throws Exception
     */
    public function getFolderByExternalId($ext_id) {
        $folders = $this->getAllFoldersByExternalId(array($ext_id));
        return array_shift($folders);
    }

    public function getAllFoldersByExternalId(array $ext_ids): array
    {
//        $this->log->write('*********');
//        $this->log->write('SOAP call "GetAllFoldersByExternalId"');
//        $this->log->write("folderExternalIds:");
//        $this->log->write(print_r($ext_ids, true));
//        $this->log->write("providerNames:");
//        $this->log->write(print_r(array(xpanConfig::getConfig(xpanConfig::F_INSTANCE_NAME)), true));

        $params = new GetAllFoldersByExternalId(
            $this->auth,
            $ext_ids,
            array(PanoptoConfig::get('instance_name'))
        );


        $session_client = $this->panoptoclient->SessionManagement();

        try {
            $return = $session_client->GetAllFoldersByExternalId($params)->getGetAllFoldersByExternalIdResult()->getFolder();
        } catch (Exception $e) {
//            $this->logException($e, $session_client);
            throw $e;
        }

//        $this->log->write('Status: ' . substr($session_client->__last_response_headers, 0, strpos($session_client->__last_response_headers, "\r\n")));
        //        $this->log->write('Received ' . (int) count($return) . ' object(s).');
        return is_array($return) ? $return : array();
    }

}
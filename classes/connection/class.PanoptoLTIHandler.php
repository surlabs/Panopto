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

require_once __DIR__ . "/../../vendor/autoload.php";

use League\OAuth1\Client as OAuth1;

use platform\PanoptoConfig;
use platform\PanoptoException;
use utils\PanoptoUtils;

/**
 * Class PanoptoLTIHandler
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class PanoptoLTIHandler
{
    /**
     * @throws PanoptoException
     */
    private static function signOAuth($launch_data, $launch_url): string
    {
        $key = PanoptoConfig::get('instance_name');
        $secret = PanoptoConfig::get('application_key');

        $credentials = new OAuth1\Credentials\ClientCredentials();
        $credentials->setIdentifier($key);
        $credentials->setSecret($secret);

        ksort($launch_data);
        $signature = new OAuth1\Signature\HmacSha1Signature($credentials);

        return $signature->sign($launch_url . '/Panopto/BasicLTI/BasicLTILanding.aspx', $launch_data);
    }

    /**
     * @throws PanoptoException
     */
    public static function launchTool($object, $showIframe = false): string
    {
        global $DIC;
        $launch_url = 'https://' . PanoptoConfig::get('hostname');

        $launch_data = [
            "user_id" => PanoptoUtils::getUserIdentifier(),
            "roles" => "Instructor",
            "resource_link_id" => $object->getFolderExtId(),
            "resource_link_title" => PanoptoUtils::getExternalIdOfObjectById($object->getFolderExtId()),
            "lis_person_name_full" => str_replace("'", "`", $DIC->user()->getFullname()),
            "lis_person_name_family" => str_replace("'", "`", $DIC->user()->getLastname()),
            "lis_person_name_given" => str_replace("'", "`", $DIC->user()->getFirstname()),
            "lis_person_contact_email_primary" => $DIC->user()->getEmail(),
            "context_id" => $object->getFolderExtId(),
            "context_title" => PanoptoUtils::getExternalIdOfObjectById($object->getFolderExtId()),
            "context_label" => "urn:lti:context-type:ilias/Object_" . $object->getFolderExtId(),
            "context_type" => "urn:lti:context-type:ilias/Object",
            "launch_presentation_locale" => 'de',
            "launch_presentation_document_target" => 'iframe',
            "lti_version" => "LTI-1p0",
            "lti_message_type" => "basic-lti-launch-request",
            "oauth_callback" => "about:blank",
            "oauth_consumer_key" => PanoptoConfig::get('instance_name'),
            "oauth_version" => "1.0",
            "oauth_nonce" => uniqid('', true),
            "oauth_timestamp" => time(),
            "oauth_signature_method" => "HMAC_SHA1"
        ];

        $launch_data['oauth_signature'] = self::signOAuth($launch_data, $launch_url);

        $html = '<form id="lti_form" action="' . $launch_url . '/Panopto/BasicLTI/BasicLTILanding.aspx" method="post" target="basicltiLaunchFrame" enctype="application/x-www-form-urlencoded">';
        foreach ($launch_data as $key => $value) {
            $html .= "<input type='hidden' name='$key' value='" . htmlspecialchars((string)$value, ENT_QUOTES) . "'>";
        }
        $html .= '</form>';
        $html .= '<iframe name="basicltiLaunchFrame" id="basicltiLaunchFrame" src="" style="width:100%;height:100%;min-height:800px;border:none;' . ($showIframe ? 'min-height: calc(100dvh - 290px);' : 'display:none;') . '"></iframe>';

        return $html;
    }

    /**
     * @throws PanoptoException
     */
    public static function launchToolPageComponent()
    {
        global $DIC;
        $launch_url = 'https://' . PanoptoConfig::get('hostname') . '/Panopto/BasicLTI/BasicLTILanding.aspx';

        $launch_data = array(
            "user_id" => PanoptoUtils::getUserIdentifier(),
            "roles" => "Viewer",
            "lis_person_name_full" => str_replace("'","`",($DIC->user()->getFullname())),
            "lis_person_name_family" => str_replace("'","`",($DIC->user()->getLastname())),
            "lis_person_name_given" => str_replace("'","`",($DIC->user()->getFirstname())),
            "lis_person_contact_email_primary" => $DIC->user()->getEmail(),
            "context_type" => "urn:lti:context-type:ilias/Object",
            'launch_presentation_locale' => 'de',
            'launch_presentation_document_target' => 'iframe',
            "lti_version" => "LTI-1p0",
            "lti_message_type" => "basic-lti-launch-request",
            "oauth_callback" => "about:blank",
            "oauth_consumer_key" => PanoptoConfig::get('instance_name'),
            "oauth_version" => "1.0",
            "oauth_nonce" => uniqid('', true),
            "oauth_timestamp" => time(),
            "oauth_signature_method" => "HMAC_SHA1"
        );

        $launch_data['oauth_signature'] = self::signOAuth($launch_data, $launch_url);

        return json_encode($launch_data);
    }
}


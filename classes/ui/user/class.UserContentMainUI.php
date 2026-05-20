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

namespace classes\ui\user;

use connection\PanoptoClient;
use connection\PanoptoLTIHandler;
use connection\PanoptoLog;
use DateTime;
use Exception;
use ilCtrl;
use ilCtrlException;
use ilException;
use ilGlobalTemplateInterface;
use ilPanoptoPlugin;
use ilTemplate;
use platform\PanoptoConfig;
use utils\DTO\ContentObject;
use utils\DTO\Session;
use ilLanguage;
use ILIAS\UI\Renderer;

/**
 * Class UserContentMainUI
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class UserContentMainUI
{
    /**
     * @var PanoptoClient
     */
    protected PanoptoClient $client;
    /**
     * @var String
     */
    protected string $folder_id;

    protected ilGlobalTemplateInterface $tpl;

    /**
     * @var ilPanoptoPlugin
     */
    protected ilPanoptoPlugin $pl;

    /**
     * @var ilCtrl
     */
    protected ilCtrl $ctrl;
    protected ilLanguage $lng;
    protected Renderer $ui_renderer;

    /**
     * @throws ilCtrlException
     * @throws Exception
     */
    public function render($object, $parent): string
    {
        global $DIC;
        $this->tpl = $DIC->ui()->mainTemplate();
        $this->ctrl = $DIC->ctrl();

        // Render the content
        $this->client = PanoptoClient::getInstance();

        $folder = $this->client->getFolderByExternalId(
            $object->getFolderExtId(),
        );
        if (!$folder) {
            throw new ilException("No external folder found for this object.");
        }
        $this->folder_id = $folder->getId();

        $this->pl = ilPanoptoPlugin::getInstance();

        return self::createContentObject($object, $parent);
    }

    /**
     * @throws Exception
     */
    public function createContentObject($panoptoObject, $parent): string
    {
        $page = isset($_GET["xpan_page"]) ? $_GET["xpan_page"] : 0;
        $content_objects = $this->client->getContentObjectsOfFolder(
            $this->folder_id,
            true,
            $page,
            $panoptoObject->getFolderExtId(),
        );

        if (!$content_objects["count"]) {
            $this->tpl->setOnScreenMessage(
                "success",
                ilPanoptoPlugin::getInstance()->txt("msg_no_videos"),
                true,
            );
            return "";
        }

        $tpl = new ilTemplate(
            "tpl.content_list.html",
            true,
            true,
            $this->pl->getDirectory(),
        );
        $pages = 1 + floor($content_objects["count"] / 10);

        // Extract all session IDs for batching
        $session_ids = [];
        foreach ($content_objects["objects"] as $object) {
            if ($object instanceof Session) {
                $session_ids[] = $object->getId();
            }
        }

        // Single batch call for all availability data
        $availability_data = [];
        if (!empty($session_ids)) {
            $availability_data = $this->client->getSessionsAvailabilitySettings(
                $session_ids,
            );
        }

        // Apply filtering using batched data
        $filtered_count = 0;
        foreach ($content_objects["objects"] as $key => $object) {
            if ($object instanceof Session) {
                $session_id = $object->getId();
                $availabilityStart = $this->processAvailabilityData(
                    $availability_data[$session_id] ?? null,
                );

                if ($availabilityStart && $availabilityStart > new DateTime()) {
                    unset($content_objects["objects"][$key]);
                    $filtered_count++;
                    continue;
                }

                $tpl->setCurrentBlock("duration");
                $tpl->setVariable(
                    "DURATION",
                    $this->formatDuration((int) $object->getDuration()),
                );
                $tpl->parseCurrentBlock();
                $tpl->setVariable("IS_PLAYLIST", "false");
            } else {
                $tpl->setVariable("IS_PLAYLIST", "true");
                $tpl->touchBlock("playlist_icon");
            }

            $tpl->setCurrentBlock("list_item");
            $tpl->setVariable("ID", $object->getId());
            $tpl->setVariable("THUMBNAIL", $object->getThumbnailUrl());
            $tpl->setVariable(
                "TITLE",
                htmlspecialchars($object->getTitle(), ENT_QUOTES, "UTF-8"),
            );
            $tpl->setVariable(
                "DESCRIPTION",
                htmlspecialchars(
                    $object->getDescription(),
                    ENT_QUOTES,
                    "UTF-8",
                ),
            );
            $tpl->parseCurrentBlock();
        }

        // "previous" button
        if ($page) {
            $this->ctrl->setParameter($parent, "xpan_page", $page - 1);
            $link = $this->ctrl->getLinkTarget($parent, "index");
            // top
            $tpl->setCurrentBlock("previous_top"); // for some reason, i had to do 2 different blocks for top and bottom pagination
            $tpl->setVariable("LINK_PREVIOUS", $link);
            $tpl->parseCurrentBlock();
            // bottom
            $tpl->setCurrentBlock("previous_bottom");
            $tpl->setVariable("LINK_PREVIOUS", $link);
            $tpl->parseCurrentBlock();
        }

        // pages
        if ($pages > 1) {
            for ($i = 1; $i <= $pages; $i++) {
                $this->ctrl->setParameter($parent, "xpan_page", $i - 1);
                $link = $this->ctrl->getLinkTarget($parent, "index");
                // top
                $tpl->setCurrentBlock("page_top");
                $tpl->setVariable("LINK_PAGE", $link);
                if ($i - 1 == $page) {
                    $tpl->setVariable("ADDITIONAL_CLASS", "xpan_page_active");
                }
                $tpl->setVariable("LABEL_PAGE", $i);
                $tpl->parseCurrentBlock();
                // bottom
                $tpl->setCurrentBlock("page_bottom");
                $tpl->setVariable("LINK_PAGE", $link);
                if ($i - 1 == $page) {
                    $tpl->setVariable("ADDITIONAL_CLASS", "xpan_page_active");
                }
                $tpl->setVariable("LABEL_PAGE", $i);
                $tpl->parseCurrentBlock();
            }
        }

        // "next" button
        if ($content_objects["count"] > ($page + 1) * 10) {
            $this->ctrl->setParameter($this, "xpan_page", $page + 1);
            $link = $this->ctrl->getLinkTarget($parent, "index");
            // top
            $tpl->setCurrentBlock("next_top");
            $tpl->setVariable("LINK_NEXT", $link);
            $tpl->parseCurrentBlock();
            // bottom
            $tpl->setCurrentBlock("next_bottom");
            $tpl->setVariable("LINK_NEXT", $link);
            $tpl->parseCurrentBlock();
        }

        $lti_form = PanoptoLTIHandler::launchTool($panoptoObject, false, false);

        $this->tpl->addCss("Customizing/global/plugins/Services/Repository/RepositoryObject/Panopto/templates/default/content_list.css");
        $this->tpl->addJavaScript('Customizing/global/plugins/Services/Repository/RepositoryObject/Panopto/templates/js/Panopto.js');
        $this->tpl->addOnLoadCode('Panopto.base_url = "https://' . PanoptoConfig::get('hostname') . '";');
        $this->tpl->addOnloadCode('
            $(function() {
                var $form = $("#lti_form");
                if ($form.length > 0) {
                    $form.submit();
                }
            });
        ');
        $this->tpl->addJavaScript("assets/js/modal.min.js");

        if ($filtered_count > 0) {
            PanoptoLog::getInstance()->write(
                "Filtered $filtered_count videos with future publication dates",
            );
        }

        return '<div class="xpan_flex">' .
            $tpl->get() .
            "</div>" .
            $lti_form .
            $this->getModalPlayer();
    }

    protected function formatDuration(int $duration_in_seconds): string
    {
        $t = floor($duration_in_seconds);

        $hours = (int) ($t / 3600);
        $minutes = (int) (($t % 3600) / 60);
        $seconds = $t % 60;

        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }

    /**
     * Generates the HTML string for the Panopto video player modal.
     *
     * @return string The rendered HTML modal component.
     */
    private function processAvailabilityData($availability_setting): ?DateTime
    {
        if (!$availability_setting) {
            return null;
        }

        if (
            $availability_setting["start_setting_type"] === "SpecificDate" &&
            $availability_setting["start_date"]
        ) {
            $start_date = $availability_setting["start_date"];
            if ($start_date && method_exists($start_date, "getDateTime")) {
                return $start_date->getDateTime();
            }
        }

        return null;
    }

    protected function getModalPlayer(): string
    {
        global $DIC;
        $this->lng = $DIC->language();

        $ui_factory = $DIC->ui()->factory();

        // Base container that will be targeted by Panopto.js to inject the iframe
        $html_content = '<div id="panopto-modal-video-container"></div>';

        // Wrap the HTML content inside an ILIAS Legacy Component
        $legacy_factory = $ui_factory->legacy();
        $content_component = $legacy_factory->content($html_content);

        // Build the roundtrip modal
        $title = $this->lng->txt("rep_robj_xpnt_player_modal_title");
        $modal = $ui_factory->modal()->roundtrip($title, $content_component);

        // Render the modal component into an HTML string
        return $DIC->ui()->renderer()->render($modal);
    }
}

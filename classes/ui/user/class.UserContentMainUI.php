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


       $folder = $this->client->getFolderByExternalId($object->getFolderExtId());
        if (!$folder) {
            throw new ilException('No external folder found for this object.');
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

        $content_objects = $this->client->getContentObjectsOfFolder(
            $this->folder_id,
            true,
            $_GET['xpan_page'],
            $panoptoObject->getFolderExtId());

        if (!$content_objects['count']) {
            $this->tpl->setOnScreenMessage("success", ilPanoptoPlugin::getInstance()->txt("msg_no_videos"), true);
            return "";
        }

        $tpl = new ilTemplate('tpl.content_list.html', true, true, $this->pl->getDirectory());
        $pages = 1 + floor($content_objects['count'] / 10);

        // "previous" button
        if ($_GET['xpan_page']) {
            $this->ctrl->setParameter($parent, 'xpan_page', $_GET['xpan_page'] - 1);
            $link = $this->ctrl->getLinkTarget($parent, 'index');
            // top
            $tpl->setCurrentBlock('previous_top');  // for some reason, i had to do 2 different blocks for top and bottom pagination
            $tpl->setVariable('LINK_PREVIOUS', $link);
            $tpl->parseCurrentBlock();
            // bottom
            $tpl->setCurrentBlock('previous_bottom');
            $tpl->setVariable('LINK_PREVIOUS', $link);
            $tpl->parseCurrentBlock();
        }

        // pages
        if ($pages > 1) {
            for ($i = 1; $i <= $pages; $i++) {
                $this->ctrl->setParameter($parent, 'xpan_page', $i - 1);
                $link = $this->ctrl->getLinkTarget($parent, 'index');
                // top
                $tpl->setCurrentBlock('page_top');
                $tpl->setVariable('LINK_PAGE', $link);
                if (($i-1) == $_GET['xpan_page']) {
                    $tpl->setVariable('ADDITIONAL_CLASS', 'xpan_page_active');
                }
                $tpl->setVariable('LABEL_PAGE', $i);
                $tpl->parseCurrentBlock();
                // bottom
                $tpl->setCurrentBlock('page_bottom');
                $tpl->setVariable('LINK_PAGE', $link);
                if (($i-1) == $_GET['xpan_page']) {
                    $tpl->setVariable('ADDITIONAL_CLASS', 'xpan_page_active');
                }
                $tpl->setVariable('LABEL_PAGE', $i);
                $tpl->parseCurrentBlock();
            }
        }

        // "next" button
        if ($content_objects['count'] > (($_GET['xpan_page'] + 1)*10)) {
            $this->ctrl->setParameter($this, 'xpan_page', $_GET['xpan_page'] + 1);
            $link = $this->ctrl->getLinkTarget($this, 'index');
            // top
            $tpl->setCurrentBlock('next_top');
            $tpl->setVariable('LINK_NEXT', $link);
            $tpl->parseCurrentBlock();
            // bottom
            $tpl->setCurrentBlock('next_bottom');
            $tpl->setVariable('LINK_NEXT', $link);
            $tpl->parseCurrentBlock();
        }

        // videos
        /** @var ContentObject $object */
        foreach ($content_objects['objects'] as $object) {
            if ($object instanceof Session) {
                $tpl->setCurrentBlock('duration');
                $tpl->setVariable('DURATION', $this->formatDuration($object->getDuration()));
                $tpl->parseCurrentBlock();
                $tpl->setVariable('IS_PLAYLIST', 'false');
            } else {
                $tpl->setVariable('IS_PLAYLIST', 'true');
                $tpl->touchBlock('playlist_icon');
            }

            $tpl->setCurrentBlock('list_item');
            $tpl->setVariable('ID', $object->getId());
            $tpl->setVariable('THUMBNAIL', $object->getThumbnailUrl());
            $tpl->setVariable('TITLE', $object->getTitle());
            $tpl->setVariable('DESCRIPTION', $object->getDescription());
            $tpl->parseCurrentBlock();
        }

        $lti_form = PanoptoLTIHandler::launchTool($panoptoObject);

        $this->tpl->addCss($this->pl->getDirectory() . '/templates/default/content_list.css');
        $this->tpl->addJavaScript($this->pl->getDirectory() . '/templates/js/Panopto.js');
        $this->tpl->addOnLoadCode('Panopto.base_url = "https://' . PanoptoConfig::get('hostname') . '";');
        $this->tpl->addJavaScript("./Services/UIComponent/Modal/js/Modal.js");


        return '<div class="xpan_flex">'.$tpl->get().'</div>'.$lti_form .  $this->getModalPlayer();
    }

    protected function formatDuration($duration_in_seconds): string
    {
        $t = floor($duration_in_seconds);
        return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
    }

    /**
     * @return String
     */
    protected function getModalPlayer(): string
    {
        global $DIC;
        $factory = $DIC->ui()->factory();
        $renderer = $DIC->ui()->renderer();
        $message = $factory->legacy('<section><div id="xpan_video_container"></div></section>');
        $modal = $factory->modal()->roundtrip('', $message);
        $this->tpl->addOnLoadCode('$("#lti_form").submit();');

        return $renderer->render($modal);

    }


}


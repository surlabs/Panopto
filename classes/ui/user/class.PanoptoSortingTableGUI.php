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

use connection\PanoptoClient;
use ILIAS\Data\URI;
use ILIAS\HTTP\Wrapper\WrapperFactory;
use ILIAS\UI\Component\Table\OrderingBinding;
use ILIAS\UI\Component\Table\OrderingRowBuilder;
use ILIAS\UI\Factory;
use ILIAS\UI\Renderer;
use platform\PanoptoException;
use platform\SorterEntry;

/**
 * Class PanoptoSortingTableGUI
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class PanoptoSortingTableGUI implements OrderingBinding
{
    protected ilPanoptoPlugin $plugin;
    protected Factory $ui_factory;
    protected Renderer $ui_renderer;
    protected $request;
    protected WrapperFactory $wrapper;
    protected ILIAS\Refinery\Factory $refinery;
    protected array $records;
    private object $parent_obj;


    public function __construct(object $parent_obj)
    {
        global $DIC;

        $this->plugin = ilPanoptoPlugin::getInstance();
        $this->ui_factory = $DIC->ui()->factory();
        $this->ui_renderer = $DIC->ui()->renderer();
        $this->request = $DIC->http()->request();
        $this->wrapper = $DIC->http()->wrapper();
        $this->refinery = $DIC->refinery();

        $this->parent_obj = $parent_obj;

        $DIC->ui()->mainTemplate()->addCss('Customizing/global/plugins/Services/Repository/RepositoryObject/Panopto/templates/default/sorting_table.css');

        $this->initRecords();
    }

    public function getRows(OrderingRowBuilder $row_builder, array $visible_column_ids): Generator
    {
        foreach ($this->records as $record) {
            yield $row_builder->buildOrderingRow($record['id'], $record);
        }
    }

    /**
     * @throws PanoptoException
     */
    public function getHTML(): string
    {
        $target = (new URI((string) $this->request->getUri()))->withParameter('saveOrder', 1);

        $table = $this->ui_factory->table()
            ->ordering("", $this->getColumns(), $this, $target)
            ->withRequest($this->request);

        if ($this->request->getMethod() == "POST" && $this->wrapper->query()->has('saveOrder') && $this->wrapper->query()->retrieve('saveOrder', $this->refinery->kindlyTo()->int()) == 1) {
            $data = $table->getData();

            SorterEntry::saveOrder($data, $this->parent_obj->getFolderExtId());

            $this->setOrder($data);
        }

        return $this->ui_renderer->render($table);
    }

    private function getColumns(): array
    {
        return [
            "thumbnail" => $this->ui_factory->table()->column()->text($this->plugin->txt('content_thumbnail')),
            "title" => $this->ui_factory->table()->column()->text($this->plugin->txt('content_title')),
            "description" => $this->ui_factory->table()->column()->text($this->plugin->txt('content_description'))
        ];
    }

    /**
     * @throws PanoptoException
     * @throws ilException
     * @throws Exception
     */
    private function initRecords(): void
    {
        $client = PanoptoClient::getInstance();
        $folder = $client->getFolderByExternalId($this->parent_obj->getFolderExtId());

        if (!$folder) {
            throw new ilException('No external folder found for this object.');
        }

        $this->records = [];

        $objects =  PanoptoClient::getInstance()->getContentObjectsOfFolder($folder->getId(), false, 0, $this->parent_obj->getFolderExtId());

        foreach ($objects as $object) {
            $this->records[$object->getId()] = [
                'id' => $object->getId(),
                'thumbnail' => "<img src='" . $object->getThumbnailUrl() . "' alt='" . $object->getTitle() . "' class='panopto_table_thumbnail'/>",
                'title' => $object->getTitle(),
                'description' => $object->getDescription(),
            ];
        }
    }

    public function setOrder(array $ordered): void
    {
        $r = [];

        foreach ($ordered as $id) {
            $r[$id] = $this->records[$id];
        }

        $this->records = $r;
    }
}

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

use ILIAS\UI\Renderer;
use ILIAS\UI\Factory;
use classes\ui\admin\PluginConfigurationMainUI;
use platform\PanoptoConfig;
use platform\PanoptoException;
use connection\PanoptoClient;

/**
 * Class ilPanoptoConfigGUI
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 * @ilCtrl_IsCalledBy  ilPanoptoConfigGUI: ilObjComponentSettingsGUI
 */
class ilPanoptoConfigGUI extends ilPluginConfigGUI
{

    private static Factory $factory;
    protected ilCtrlInterface $control;
    protected ilGlobalTemplateInterface $tpl;
    protected $request;
    protected Renderer $renderer;
    protected PluginConfigurationMainUI $config_ui;

    /**
     * Handles all commands, default is "configure"
     * @throws ilException
     * @throws PanoptoException
     */
    function performCommand(string $cmd): void
    {
        global $DIC;
        self::$factory = $DIC->ui()->factory();
        $this->tpl = $DIC->ui()->mainTemplate();
        $this->control = $DIC->ctrl();
        $this->request = $DIC->http()->request();
        $this->renderer = $DIC->ui()->renderer();
        $this->config_ui = new PluginConfigurationMainUI();

        switch ($cmd) {
            case "configure":
                PanoptoConfig::load();
                $this->control->setParameterByClass('ilPanoptoConfigGUI', 'cmd', 'configure');
                $sections = $this->config_ui->configure();
                $form_action = $this->control->getLinkTargetByClass("ilPanoptoConfigGUI", "configure");
                
                $test_message = '';
                $save_message = '';
                
                // Check if form was submitted
                if ($this->request->getMethod() == "POST") {
                    $form = self::$factory->input()->container()->form()->standard($form_action, $sections);
                    $form = $form->withRequest($this->request);
                    $result = $form->getData();
                    
                    if ($result) {
                        // Save configuration
                        PanoptoConfig::save();
                        $save_message = $this->renderer->render(self::$factory->messageBox()->success($this->plugin_object->txt('info_config_saved')));
                        
                        // Test connection after saving
                        try {
                            $client = PanoptoClient::getInstance();
                            $client->testConnection();
                            $test_message = $this->renderer->render(self::$factory->messageBox()->success($this->plugin_object->txt('test_connection_success')));
                        } catch (PanoptoException $e) {
                            $test_message = $this->renderer->render(self::$factory->messageBox()->failure($this->plugin_object->txt('test_connection_failed') . $e->getMessage()));
                        }
                    }
                }
                
                $rendered = $this->renderForm($form_action, $sections, $save_message, $test_message);
                break;
            default:
                throw new ilException("command not defined");

        }

        $this->tpl->setContent($rendered);
    }

    /**
     * @throws ilCtrlException
     */
    private function renderForm(string $form_action, array $sections, string $save_message = '', string $test_message = ''): string
    {
        global $DIC;
        
        //Create the form
        $form = self::$factory->input()->container()->form()->standard(
            $form_action,
            $sections
        );

        return $save_message . $test_message . $this->renderer->render($form);
    }

    public function save(): string
    {
        PanoptoConfig::save();
        return $this->renderer->render(self::$factory->messageBox()->success($this->plugin_object->txt('info_config_saved')));
    }

    public function testConnection(): array
    {
        try {
            $client = PanoptoClient::getInstance();
            $client->testConnection();
            return ['success' => true, 'message' => $this->plugin_object->txt('test_connection_success')];
        } catch (PanoptoException $e) {
            return ['success' => false, 'message' => $this->plugin_object->txt('test_connection_failed') . $e->getMessage()];
        }
    }

}
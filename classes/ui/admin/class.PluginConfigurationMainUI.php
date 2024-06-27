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

namespace classes\ui\admin;

use ilCtrlInterface;
use ilException;
use ILIAS\DI\Exceptions\Exception;
use ILIAS\UI\Factory;
use ilPanoptoPlugin;
use platform\PanoptoConfig as PanoptoConfig;
use platform\PanoptoException;

/**
 * Class PluginConfigurationMainUI
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class PluginConfigurationMainUI
{
    /**
     * Configure screen
     * @throws ilException
     */
    /**
     * @var ilCtrlInterface
     */
    protected ilCtrlInterface $control;
    /**
     * @var Factory
     */
    protected Factory $factory;


    /**
     * @throws ilException|PanoptoException
     */
    public function configure(): array
    {
        global $DIC;
        $object1 = new PanoptoConfig();
        $this->factory = $DIC->ui()->factory();
        $this->control = $DIC->ctrl();
        $plugin_object = ilPanoptoPlugin::getInstance();

        try {


            $object = $object1;

            //SOAP Api section
            $form_fields_soap = [];

            $field_api_user = $this->factory->input()->field()->text(
                $plugin_object->txt('conf_api_user'),
                $plugin_object->txt('conf_api_user_info'))
                ->withValue(PanoptoConfig::get('api_user'))
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
                        PanoptoConfig::set('api_user', $v);
                    }
                ));

            $form_fields_soap["api_user"] = $field_api_user;

            $field_hostname = $this->factory->input()->field()->text(
                $plugin_object->txt('conf_hostname'),
                $plugin_object->txt('conf_hostname_info'))
                ->withValue(PanoptoConfig::get('hostname'))
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
                        PanoptoConfig::set('hostname', $v);
                    }
                ));

            $form_fields_soap["hostname"] = $field_hostname;

            $field_instance_name = $this->factory->input()->field()->text(
                $plugin_object->txt('conf_instance_name'),
                $plugin_object->txt('conf_instance_name_info'))
                ->withValue(PanoptoConfig::get('instance_name'))
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
                        PanoptoConfig::set('instance_name', $v);
                    }
                ));

            $form_fields_soap["instance_name"] = $field_instance_name;

            $field_application_key = $this->factory->input()->field()->text(
                $plugin_object->txt('conf_application_key'),
                $plugin_object->txt('conf_application_key_info'))
                ->withValue(PanoptoConfig::get('application_key'))
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
                        PanoptoConfig::set('application_key', $v);
                    }
                ));

            $form_fields_soap["application_key"] = $field_application_key;

            $identification_options = array(
                'login' => $plugin_object->txt('conf_login'),
                'external_account' => $plugin_object->txt('conf_external_account'),
                'email' => $plugin_object->txt('conf_email')
            );

            $field_identification = $this->factory->input()->field()->select(
                $plugin_object->txt('conf_user_id'),
                $identification_options,
                $plugin_object->txt('conf_user_id_info'))
                ->withValue(PanoptoConfig::get('user_id'))
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
                        PanoptoConfig::set('user_id', $v);
                    }
                ));

            $form_fields_soap["user_id"] = $field_identification;

            $section_soap = $this->factory->input()->field()->section($form_fields_soap, $plugin_object->txt("conf_header_soap"), "");


            //REST API section
            $form_fields_rest = [];

            $field_rest_api_user = $this->factory->input()->field()->text(
                $plugin_object->txt('conf_rest_api_user'),
                $plugin_object->txt('conf_rest_api_user_info'))
                ->withValue(PanoptoConfig::get('rest_api_user'))
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
                        PanoptoConfig::set('rest_api_user', $v);
                    }
                ));

            $form_fields_rest["rest_api_user"] = $field_rest_api_user;

            $field_rest_api_password = $this->factory->input()->field()->text(
                $plugin_object->txt('conf_rest_api_password'),
                $plugin_object->txt('conf_rest_api_password_info'))
                ->withValue(PanoptoConfig::get('rest_api_password'))
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
                        PanoptoConfig::set('rest_api_password', $v);
                    }
                ));

            $form_fields_rest["rest_api_password"] = $field_rest_api_password;

            $field_rest_client_name = $this->factory->input()->field()->text(
                $plugin_object->txt('conf_rest_client_name'),
                $plugin_object->txt('conf_rest_client_name_info'))
                ->withValue(PanoptoConfig::get('rest_client_name'))
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
                        PanoptoConfig::set('rest_client_name', $v);
                    }
                ));

            $form_fields_rest["rest_client_name"] = $field_rest_client_name;

            $field_rest_client_id = $this->factory->input()->field()->text(
                $plugin_object->txt('conf_rest_client_id'),
                $plugin_object->txt('conf_rest_client_id_info'))
                ->withValue(PanoptoConfig::get('rest_client_id'))
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
                        PanoptoConfig::set('rest_client_id', $v);
                    }
                ));

            $form_fields_rest["rest_client_id"] = $field_rest_client_id;

            $field_rest_client_secret = $this->factory->input()->field()->text(
                $plugin_object->txt('conf_rest_client_secret'),
                $plugin_object->txt('conf_rest_client_secret_info'))
                ->withValue(PanoptoConfig::get('rest_client_secret'))
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
                        PanoptoConfig::set('rest_client_secret', $v);
                    }
                ));

            $form_fields_rest["rest_client_secret"] = $field_rest_client_secret;

            $section_rest = $this->factory->input()->field()->section($form_fields_rest, $plugin_object->txt("conf_header_rest"), "");

            return [
//                "config_general" => $section_general,
                "config_soap" => $section_soap,
                "config_rest" => $section_rest
            ];


        } catch (Exception $e) {
            throw new ilException($e->getMessage());
        }


    }
}
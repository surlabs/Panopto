<?php
declare(strict_types=1);

namespace classes\ui\admin;

use ilCtrlInterface;
use ilException;

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
class PluginConfigurationMainUI
{
    /**
     * Configure screen
     * @throws ilException
     */

    protected ilCtrlInterface $control;

    public function configure(): array
    {
        global $DIC;

        self::$factory = $DIC->ui()->factory();
        $this->control = $DIC->ctrl();

        try {


            $object = $this->object;

            //General section
            $form_fields_general = [];

            $field = self::$factory->input()->field()->text(
                $this->plugin_object->txt('conf_object_title'),
                $this->plugin_object->txt('conf_object_title_info'))
                ->withValue("TEST")
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
//                        $object->setWebsocket($v);
                    }
                ));

            $form_fields_general["object_title"] = $field;

            $section_general = self::$factory->input()->field()->section($form_fields_general, $this->plugin_object->txt("conf_header_general"), "");

            //SOAP Api section
            $form_fields_soap = [];

            $field_api_user = self::$factory->input()->field()->text(
                $this->plugin_object->txt('conf_rest_api_user'),
                $this->plugin_object->txt('conf_rest_api_user_info'))
                ->withValue("TEST2")
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
//                        $object->setWebsocket($v);
                    }
                ));

            $form_fields_soap["api_user"] = $field_api_user;

            $field_hostname = self::$factory->input()->field()->text(
                $this->plugin_object->txt('conf_hostname'),
                $this->plugin_object->txt('conf_hostname_info'))
                ->withValue("TEST2")
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
//                        $object->setWebsocket($v);
                    }
                ));

            $form_fields_soap["hostname"] = $field_hostname;

            $field_instance_name = self::$factory->input()->field()->text(
                $this->plugin_object->txt('conf_instance_name'),
                $this->plugin_object->txt('conf_instance_name_info'))
                ->withValue("TEST2")
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
//                        $object->setWebsocket($v);
                    }
                ));

            $form_fields_soap["instance_name"] = $field_instance_name;

            $field_application_key = self::$factory->input()->field()->text(
                $this->plugin_object->txt('conf_application_key'),
                $this->plugin_object->txt('conf_application_key_info'))
                ->withValue("TEST2")
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
//                        $object->setWebsocket($v);
                    }
                ));

            $form_fields_soap["application_key"] = $field_application_key;

            $identification_options = array(
                'login' => $this->plugin_object->txt('conf_login'),
                'external_account' => $this->plugin_object->txt('conf_external_account'),
                'email' => $this->plugin_object->txt('conf_email')
            );

            $field_identification = self::$factory->input()->field()->select(
                $this->plugin_object->txt('conf_user_id'),
                $identification_options,
                $this->plugin_object->txt('conf_user_id_info'))
                ->withValue("login")
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
//                        $object->setWebsocket($v);
                    }
                ));

            $form_fields_soap["user_id"] = $field_identification;

            $section_soap = self::$factory->input()->field()->section($form_fields_soap, $this->plugin_object->txt("conf_header_soap"), "");


            //REST API section
            $form_fields_rest = [];

            $field_rest_api_user = self::$factory->input()->field()->text(
                $this->plugin_object->txt('conf_rest_api_user'),
                $this->plugin_object->txt('conf_rest_api_user_info'))
                ->withValue("TEST2")
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
//                        $object->setWebsocket($v);
                    }
                ));

            $form_fields_rest["rest_api_user"] = $field_rest_api_user;

            $field_rest_api_password = self::$factory->input()->field()->text(
                $this->plugin_object->txt('conf_rest_api_password'),
                $this->plugin_object->txt('conf_rest_api_password_info'))
                ->withValue("TEST2")
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
//                        $object->setWebsocket($v);
                    }
                ));

            $form_fields_rest["rest_api_password"] = $field_rest_api_password;

            $field_rest_client_name = self::$factory->input()->field()->text(
                $this->plugin_object->txt('conf_rest_client_name'),
                $this->plugin_object->txt('conf_rest_client_name_info'))
                ->withValue("TEST2")
                ->withRequired(true)
                ->withAdditionalTransformation($DIC->refinery()->custom()->transformation(
                    function ($v) use ($object) {
//                        $object->setWebsocket($v);
                    }
                ));

            $form_fields_rest["rest_client_name"] = $field_rest_client_name;

            $section_rest = self::$factory->input()->field()->section($form_fields_rest, $this->plugin_object->txt("conf_header_rest"), "");

            return ["config_general" => $section_general, "config_soap" => $section_soap, "config_rest" => $section_rest];


        } catch(Exception $e){
            throw new ilException($e->getMessage());
        }


    }
}
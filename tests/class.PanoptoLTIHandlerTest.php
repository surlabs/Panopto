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

namespace tests;

use PHPUnit\Framework\TestCase;
use platform\PanoptoConfig;
use platform\PanoptoException;

/**
 * Class PanoptoLTIHandlerTest
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class PanoptoLTIHandlerTest extends TestCase
{
    /**
     * @throws PanoptoException
     */
    public function test() {
        // Hasta ahora el objetivo es que carguen las clases de ilias al usar php unit, por que si no, mal vamos

        // Por el momento el erro que tiene es que al no estar ilias iniciado por ejemplo $DIC no esta definido
        // entonces da error al llamar $DIC->database() en PanoptoDatabase.php el cual se llama en PanoptoConfig.php
        print PanoptoConfig::get("hostname");
    }
}
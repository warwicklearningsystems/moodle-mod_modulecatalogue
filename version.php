<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines the version and other meta-info about the plugin
 *
 * Setting the $plugin->version to 0 prevents the plugin from being installed.
 * See https://docs.moodle.org/dev/version.php for more info.
 *
 * @package    mod_modulecatalogue
 * @copyright  2016 University of Warwick <learnsys@warwick.ac.uk>
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'mod_modulecatalogue';
$plugin->version = 2021032201;
$plugin->release = 'v1.1';
$plugin->requires = 2014051200;
$plugin->maturity = MATURITY_ALPHA;
$plugin->cron = 0;
$plugin->dependencies = array();

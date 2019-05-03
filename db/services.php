<?php

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
 * Web service local plugin template external functions and service definitions.
 *
 * @package    localwarwickws
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.
$functions = array(

  'warwick_modulecatalogue_add_string' => array(
    'classname'   => 'mod_modulecatalogue_external',
    'methodname'  => 'modulecatalogue_add_string',
    'classpath'   => 'mod/modulecatalogue/externallib.php',
    'description' => 'Add entries for a given module code',
    'type'        => 'write',
  ),
  'warwick_modulecatalogue_list_strings' => array(
    'classname'   => 'mod_modulecatalogue_external',
    'methodname'  => 'modulecatalogue_list_strings',
    'classpath'   => 'mod/modulecatalogue/externallib.php',
    'description' => 'Lists all strings for a given module code',
    'type'        => 'read',
  ),
  'warwick_modulecatalogue_remove_string' => array(
    'classname'   => 'mod_modulecatalogue_external',
    'methodname'  => 'modulecatalogue_remove_string',
    'classpath'   => 'mod/modulecatalogue/externallib.php',
    'description' => 'Remove entries for a given module code',
    'type'        => 'write',
  ),

);


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
 * External Web Service Template
 *
 * @package    localwarwickws
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . "/my/lib.php");

require_once($CFG->libdir . "/completionlib.php");
require_once($CFG->libdir . '/grouplib.php');
require_once($CFG->libdir . '/enrollib.php');
require_once($CFG->dirroot . "/user/lib.php");


class mod_modulecatalogue_external extends external_api {

  /** Add blocks */

  public static function modulecatalogue_add_string_parameters() {
    return new external_function_parameters(
      array(
        'modulecode' => new external_value(PARAM_TEXT, 'Module code', VALUE_REQUIRED),
        'key' => new external_value(PARAM_TEXT, 'Key name', VALUE_REQUIRED),
        'value' => new external_value(PARAM_TEXT, 'Value', VALUE_REQUIRED)
      )
    );
  }

  public static function modulecatalogue_add_string_returns() {
    return new external_value(PARAM_BOOL, 'Success');
  }

  public static function modulecatalogue_add_string($modulecode, $key, $value) {
    //global $PAGE;

    //Parameter validation
    //REQUIRED
    $params = self::validate_parameters(self::modulecatalogue_add_string_parameters(),
      array('courseid' => $courseid, 'blockname' => $blockname));


  }

  /** Remove blocks */

  public static function modulecatalogue_remove_string_parameters() {
    return new external_function_parameters(
      array(
        'modulecode' => new external_value(PARAM_TEXT, 'Module code', VALUE_REQUIRED),
        'key' => new external_value(PARAM_TEXT, 'Key name', VALUE_REQUIRED),
      )
    );
  }

  public static function modulecatalogue_remove_string_returns() {
    return new external_value(PARAM_BOOL, 'Success');
  }

  public static function modulecatalogue_remove_string($modulecode, $key) {

    global $DB;

    $params = self::validate_parameters(self::modulecatalogue_remove_string_parameters(),
      array('modulecode' => $modulecode, 'key' => $key));

    $delete = $DB->delete_records('modulecatalogue_data',
      array('modulecode' => $params['modulecode'], 'key' => $params['key']));

    return $delete;
  }


  /** List blocks */

  public static function modulecatalogue_list_strings_parameters() {
    return new external_function_parameters(
      array(
        'modulecode' => new external_value(PARAM_TEXT, 'Module code', VALUE_REQUIRED)
      )
    );
  }

  public static function modulecatalogue_list_strings_returns() {
    return new external_multiple_structure(
      new external_single_structure(
        array(
          'key' => new external_value(PARAM_TEXT, 'Key name', VALUE_REQUIRED),
          'value' => new external_value(PARAM_TEXT, 'Value', VALUE_REQUIRED)
        )
      )
    );
  }

  public static function modulecatalogue_list_strings($modulecode) {
    global $DB;

    $params = self::validate_parameters(self::modulecatalogue_list_strings_parameters(),
      array('modulecode' => $modulecode));
    
    //MOO-1813: Added extra parameter $academicyear to the array of parameters
    $params = self::validate_parameters(self::modulecatalogue_list_strings_parameters(),
      array('academicyear' => $academicyear));

    $modulestrings = array();
    //MOO-1813: get records from database using the newly added $academicyear to the array of values
    $values = $DB->get_records('modulecatalogue_data', array('modulecode' => $params['modulecode']), array('academicyear' => $params['academicyear'])); //MOO-1811: Made changes to make the academic year an additional parameter to be entered

    foreach($values as $v) {
      $g = new stdClass();
      $g->key = $v->key;
      $g->value = $v->value;

      $modulestrings[] = $g;
    }

    return $modulestrings;
  }

}

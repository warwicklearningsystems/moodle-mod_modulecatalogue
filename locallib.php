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
 * Internal library of functions for module modulecatalogue
 *
 * All the modulecatalogue specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_modulecatalogue
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/*
 * Does something really useful with the passed things
 *
 * @param array $things
 * @return object
 *function modulecatalogue_do_something_useful(array $things) {
 *    return new stdClass();
 *}
 */

/**
 * Retrieves assignment data from Tabula API
 *
 * @param string Module code
 * @return object information on assignments
 */
function get_modulecatalogue_data($modulecode) {

  global $DB;

  $cataloguedata = array();

  if($modulecode != '') {


    $url = 'https://courses-dev.warwick.ac.uk/modules/2020/' . $modulecode . '.json';

    //$curldata = download_file_content($url, array('Authorization' => 'Basic ' .
    //  (string)base64_encode( $username . ":" . $password )), false, true);

    $curldata = download_file_content($url, null, false, true);


    if($curldata->status == 200) {
      $cataloguedata = json_decode($curldata->results);

      foreach($cataloguedata as $k => $v) {

        if( !$DB->record_exists('modulecatalogue_data', array('modulecode' => $modulecode, 'academicyear' => '19/20', 'labelkey' => $k)) ) {

          // Insert
          $DB->insert_record('modulecatalogue_data',
            array('modulecode' => $modulecode,
                  'academicyear'=> '19/20',
                  'labelkey' => $k,
                  'labelvalue' => $v
            ));

        } else {

          $id = $DB->get_field('modulecatalogue_data', 'id', array('modulecode' => $modulecode, 'academicyear' => '19/20','labelkey' => $k));

          // Update
          $DB->update_record('modulecatalogue_data',
            array('modulecode' => $modulecode,
              'academicyear'=> '19/20',
              'labelkey' => $k,
              'labelvalue' => $v,
              'id' => $id
            ));

        }

      }


    }

  }


  return $cataloguedata;
}
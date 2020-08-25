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
 * insertRecords inserts all remaining records for each of the arrays and objects
 *
 * @param array $things
 * @return object
 */

/**
 * Retrieves assignment data from Tabula API
 *
 * @param string Module code
 * @return object information on assignments
 */

function get_modulecatalogue_data($modulecode, $academicyear) {

  global $DB;

  $cataloguedata = array();

  if($modulecode != '') {

    $url = 'https://courses.warwick.ac.uk/modules/' .$academicyear ."/" .$modulecode . '.json'; //MOO-1813 Modified URL to use new parameter added
    // $url = 'https://courses-dev.warwick.ac.uk/modules/' .'20/21' ."/" .$modulecode . '.json';

    //$curldata = download_file_content($url, array('Authorization' => 'Basic ' .
    //  (string)base64_encode( $username . ":" . $password )), false, true);

    $curldata = download_file_content($url, null, false, true);

    if($curldata->status == 200) {
      $cataloguedata = json_decode($curldata->results);

      foreach($cataloguedata as $k => $v) {
        // MOO-1808 Insert new data from JSON into database: First deal with the Stdclass object as that throws exception errors
        if ($v instanceof stdClass){
          switch ($k){
            case 'department' || 'faculty' || 'level' || 'leader':
              $sectionName = $k;
              foreach($cataloguedata->$sectionName as $k => $v){
                if (!($v instanceof stdClass)){
                  $k = $sectionName .$k;
                  if( !$DB->record_exists('modulecatalogue_data',
                    array('modulecode' => $modulecode,
                      'academicyear' => $academicyear, 'labelkey' => $k)) ){
                    $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> $academicyear, 'labelkey' => $k, 'labelvalue' => $v));
                  }
                }
              }
          }
        }
        // MOO-1808 Insert new data from JSON into database: logic to handle all the arrays.
        else{
          if(is_array($v)){
            switch ($k){
              case ($k == 'postRequisiteModules') || ($k == 'locations') || ($k == 'studyAmounts') || ($k == 'learningOutcomes') :
                $sectionName = $k;
                if (is_array($v)){
                  $array_size= count($v); // MOO-1808 determine size of array
                  //MOO-1808 code to parse through an Array of data structures
                  foreach($cataloguedata->$sectionName as $k => $v){
                    $index = $k;
                    if ($v instanceof stdClass){
                      $stdClass = json_decode(json_encode($v));
                      foreach($stdClass as $k => $v){
                        $k = Substr($sectionName,0,-1) .$k .$index;
                        write_to_database($k, $v, $modulecode, $academicyear);
                      }
                    }
                    else{
                      if ($sectionName == 'learningOutcomes'){
                        if ($index == 0){
                          $v = ltrim($v, "By the end of the module, students should be able to:");
                        }
                      }
                      $k = substr($sectionName, 0,-1) .$index;
                      write_to_database($k, $v, $modulecode, $academicyear);
                    }
                  }
                }
              // MOO-1808 Insert new data from JSON into database: logic to handle assessments - this has to be done separately due to its complex structure.
              case ($k == 'assessmentGroups'):
                $sectionName = $k;
                if (is_array($v)){
                  foreach($cataloguedata->$sectionName as $k => $v){
                    if ($v instanceof stdClass){
                      foreach($cataloguedata->assessmentGroups[0] as $k => $v){
                        $array = array($k => $v);
                        if(is_array($v)){
                          $count_array = count($v);
                          $array = array_values($array[components]);
                          for ($x = 0; $x <=  $count_array; $x++){
                            $object = $array[$x];
                            foreach($object as $k => $v){
                              $k = "assesmentGrp" .$k ."$x";
                              if ($k == 'weighting')$v = "Weighting: " .$v ."%";
                              write_to_database($k, $v, $modulecode, $academicyear);
                            }
                          }
                        }
                        else{
                          switch ($k){
                            case 'totalExamWeighting' || 'totalCourseworkWeighting' || 'groupName':
                              write_to_database($k, $v, $modulecode, $academicyear);
                          }
                        }

                      }
                    }
                  }
                }
            }
          }
          // MOO-1808 Insert new data from JSON into database: logic to insert all root data in json file.
          else{
            write_to_database($k, $v, $modulecode, $academicyear);
          }

        }
      }
    }
  }

  return $cataloguedata;
}

function write_to_database( $key, $value, $modulecode, $academicyear){

  global $DB;
  if( !$DB->record_exists('modulecatalogue_data', array('modulecode' => $modulecode,
    'academicyear' => $academicyear, 'labelkey' => $key)) ){
    if (!(is_null($value))){
      $DB->insert_record('modulecatalogue_data',
        array('modulecode' => $modulecode,
          'academicyear'=> $academicyear,
          'labelkey' => $key,
          'labelvalue' => $value));
    }
  }
  else{
    $id = $DB->get_field('modulecatalogue_data', 'id', array('modulecode' => $modulecode, 'academicyear' => $academicyear,'labelkey' => $key));
    if (!(is_null($id))){
      $DB->update_record('modulecatalogue_data',
        array('modulecode' => $modulecode,
          'academicyear'=> $academicyear,
          'labelkey' => $key,
          'labelvalue' => $value,
          'id' => $id ));
    }
  }
}

function insertLocations( $cataloguedata){
    foreach($cataloguedata->locations as $k => $v){
        $object = $cataloguedata->locations[0];
        foreach($object as $k => $v){
            print_r("am here ");
            $k = "locations" .$k;
            if( !$DB->record_exists('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear' => $academicyear, 'labelkey' => $k)) ){
                $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> $academicyear, 'labelkey' => $k, 'labelvalue' => $v));
            }
        }
    }                      
}

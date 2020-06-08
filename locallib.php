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
function get_modulecatalogue_data($modulecode) {

  global $DB;

  $cataloguedata = array();

  if($modulecode != '') {
    
    $url = 'https://courses-dev.warwick.ac.uk/modules/2020/' . $modulecode . '.json';
   // $url = 'https://courses-dev.warwick.ac.uk/modules/' .'20/21' ."/" .$modulecode . '.json';

    //$curldata = download_file_content($url, array('Authorization' => 'Basic ' .
    //  (string)base64_encode( $username . ":" . $password )), false, true);

    $curldata = download_file_content($url, null, false, true);
   
    if($curldata->status == 200) {
      $cataloguedata = json_decode($curldata->results);
         
      foreach($cataloguedata as $k => $v) {

        if( !$DB->record_exists('modulecatalogue_data', array('modulecode' => $modulecode, 'academicyear' => '20/21', 'labelkey' => $k)) ) {

          // MOO-1808 Insert new data from JSON into database    
          if(!($v instanceof stdClass)){
              if (!(is_array($v))){
                  if (!(is_null($v))){
                     $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> '20/21', 'labelkey' => $k, 'labelvalue' => $v));
                  }                                
              }
              else{
                  switch ($k){
                      case 'locations' :
                            $sectionName = $k;
                           foreach($cataloguedata->$sectionName as $k => $v){
                                $object = $cataloguedata->$sectionName[0];
                                foreach($object as $k => $v){
                                    $k = $sectionName .$k;
                                    if( !$DB->record_exists('modulecatalogue_data', 
                                        array('modulecode' => $modulecode, 
                                        'academicyear' => '20/21', 'labelkey' => $k)) ){
                                            $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> '20/21', 'labelkey' => $k, 'labelvalue' => $v));
                                    }
                                }                          
                           }
                      case 'learningOutcomes' :
                            foreach($cataloguedata->learningOutcomes as $k => $v){
                                if ($k == 0){
                                    $v = ltrim($v, "By the end of the module, students should be able to:");
                                }    
                                $k = "learningOutcome" .$k;                        
                                if( !$DB->record_exists('modulecatalogue_data', 
                                    array('modulecode' => $modulecode, 
                                    'academicyear' => '20/21', 'labelkey' => $k)) ){
                                            $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> '20/21', 'labelkey' => $k, 'labelvalue' => $v));
                                 }
                             }
                      case 'studyAmounts' :
                            foreach($cataloguedata->studyAmounts as $k => $v){
                                for ($x = 0; $x <= 4; $x++){
                                    $object = $cataloguedata->studyAmounts[$x];
                                    foreach($object as $k => $v){
                                        $k = "studyAmounts" .$k ."$x";
                                        if( !$DB->record_exists('modulecatalogue_data', 
                                            array('modulecode' => $modulecode, 
                                            'academicyear' => '20/21', 'labelkey' => $k)) ){
                                                if (!(is_null($v))){
                                                    $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> '20/21', 'labelkey' => $k, 'labelvalue' => $v));
                                                }
                                            }
                                        }
                                }
                            }
                        
                      case 'postrequisitemodules':
                            foreach($cataloguedata->postRequisiteModules as $k => $v){
                                $object = $cataloguedata->postRequisiteModules[0];
                                foreach($object as $k => $v){
                                        $k = "postRequisiteModules" ."$k";
                                        if( !$DB->record_exists('modulecatalogue_data', 
                                            array('modulecode' => $modulecode, 
                                            'academicyear' => '20/21', 'labelkey' => $k)) ){
                                                if (!(is_null($v))){
                                                    $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> '20/21', 'labelkey' => $k, 'labelvalue' => $v));
                                                }
                                        }
                                }
                            } 
                      case 'assessmentGroups':
                          foreach($cataloguedata->assessmentGroups as $k => $v){
                                if ($v instanceof stdClass){
                                    $object = $cataloguedata->assessmentGroups[0];
                                     foreach($object as $k => $v){
                                         $array = array($k => $v);
                                         switch ($k){
                                             case 'totalExamWeighting' || 'totalCourseworkWeighting' || 'groupName':
                                                 if( !$DB->record_exists('modulecatalogue_data', 
                                                        array('modulecode' => $modulecode, 
                                                        'academicyear' => '20/21', 'labelkey' => $k)) ){
                                                      $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> '20/21', 'labelkey' => $k, 'labelvalue' => $v));
                                                 }
                                         }
                                         
                                         $array = array_values($array[components]);
                                         for ($x = 0; $x <= 10; $x++){
                                            $object = $array[$x];
                                            foreach($object as $k => $v){
                                                if ($k != 'components'){
                                                    if (is_null($v)){
                                                        $v = "";
                                                    }                                                
                                                    if ($k == 'weighting'){
                                                        $v = "Weighting: " .$v ."%";
                                                    }
                                                    $k = "assesmentGrp" .$k ."$x";
                                                    if( !$DB->record_exists('modulecatalogue_data', 
                                                        array('modulecode' => $modulecode, 
                                                        'academicyear' => '20/21', 'labelkey' => $k)) ){
                                                           
                                                               $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> '20/21', 'labelkey' => $k, 'labelvalue' => $v));
                                                        
                                                    }                                                          
                                                }
                                            }
                                        } 
                                     }
                                }
                          }
                            
                      }
                                                                           
        }
            
          } else {
              switch ($k){
                  case 'department' || 'faculty' || 'level' || 'leader':
                      $sectionName = $k;
                      foreach($cataloguedata->department as $k => $v){
                          if (!($v instanceof stdClass)){
                              $k = "department" .$k;
                              if( !$DB->record_exists('modulecatalogue_data', 
                                array('modulecode' => $modulecode, 
                                    'academicyear' => '20/21', 'labelkey' => $k)) ){
                                    $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> '20/21', 'labelkey' => $k, 'labelvalue' => $v));
                              }
                          }
                      }
              }
 
          }
        

         // MOO 1808 $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> '20/21', 'labelkey' => $k, 'labelvalue' => $v));

        } else {

            //MOO-1808 Update any records in Database if any data in JSON file has changed
            $id = $DB->get_field('modulecatalogue_data', 'id', array('modulecode' => $modulecode, 'academicyear' => '20/21','labelkey' => $k));
                      
            if(!($v instanceof stdClass)){
              if (!(is_array($v))){
                  if (!(is_null($v))){                  
                      $DB->update_record('modulecatalogue_data', array('modulecode' => $modulecode, 'academicyear'=> '20/21', 'labelkey' => $k,'labelvalue' => $v,'id' => $id ));
                  }
              }
              
          }
          

        }

      }


    }

  }

  return $cataloguedata;
}

function insertLocations( $cataloguedata){
    foreach($cataloguedata->locations as $k => $v){
        $object = $cataloguedata->locations[0];
        foreach($object as $k => $v){
            print_r("am here ");
            $k = "locations" .$k;
            if( !$DB->record_exists('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear' => '20/21', 'labelkey' => $k)) ){
                $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> '20/21', 'labelkey' => $k, 'labelvalue' => $v));
            }
        }
    }                      
}

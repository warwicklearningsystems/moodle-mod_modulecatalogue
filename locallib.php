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

function get_modulecatalogue_data($modulecode, $academicyear, $adminname, $adminemail) {

  global $DB;

  $cataloguedata = array();

  if($modulecode != '') {

    $url = 'https://courses.warwick.ac.uk/modules/' .$academicyear ."/" .$modulecode . '.json'; //MOO-1813 Modified URL to use new parameter added
    
    $modulecaturl = 'https://courses.warwick.ac.uk/modules/' .$academicyear ."/" .$modulecode;
    
    // $url = 'https://courses-dev.warwick.ac.uk/modules/' .'20/21' ."/" .$modulecode . '.json';

    //$curldata = download_file_content($url, array('Authorization' => 'Basic ' .
    //  (string)base64_encode( $username . ":" . $password )), false, true);
    
    $curldata = download_file_content($url, null, null, true, 300, 20, true, null); //MOO-2373 Modified to fix issue of licensing

    if($curldata->status == 200) {
      $cataloguedata = json_decode($curldata->results);
      
      //MOO-1888 Added necessary code to store admin name and email to database
      write_to_database('adminname', $adminname, $modulecode, $academicyear);
      write_to_database('adminemail', $adminemail, $modulecode, $academicyear);
      
      /*
       * MOO-1983 Added code to handle alertmessage.
       * need to extract alertmessage from settings.php then we need to extract any URL link in it.
       */

      $alertmessage = get_config('mod_modulecatalogue', 'alertinformation');
      $applyAlert = get_config('mod_modulecatalogue', 'applyAlert');
      $all_urls = "";   //MOO-1983 initialize URL link extracted field.
      
      // MOO-1983 extract URL link from alertmessage
      if ($alertmessage != ""){
          $all_urls = rtrim(url_extract($alertmessage),'.');
      }
      
      write_to_database('alertmessage', implode('<br />', expand_array(rtrim($alertmessage,$all_urls))), $modulecode, $academicyear);
      write_to_database('urllink', $all_urls, $modulecode, $academicyear);
      write_to_database('alert', $applyAlert, $modulecode, $academicyear);
      write_to_database('catalogueurl', $modulecaturl, $modulecode, $academicyear);

      foreach($cataloguedata as $k => $v) {
        // MOO-1808 Insert new data from JSON into database: First deal with the Stdclass object as that throws exception errors
        if ($v instanceof stdClass){
          switch ($k){
            case 'department' || 'faculty' || 'level' || 'leader':
              $sectionName = $k;
              foreach($cataloguedata->$sectionName as $k => $v){
                if (!($v instanceof stdClass)){
                    $k = $sectionName .$k;
                    write_to_database($k, $v, $modulecode, $academicyear); //MOO 1828 changes to clear up code
                }
              }
          }
        }
        // MOO-1808 Insert new data from JSON into database: logic to handle all the arrays.
        else{
          if(is_array($v)){
            switch ($k){
                case "learningOutcomes" :
                    $sectionName = $k;
                    $value = implode('<br />', $v);
                    write_to_database($sectionName, $value, $modulecode, $academicyear);
                    break;
                
                case "locations":
                    $sectionName = $k;
                    foreach($cataloguedata->$sectionName as $k => $v){
                        if ($v instanceof stdClass){
                            $stdClass = json_decode(json_encode($v));
                            foreach($stdClass as $k => $v){
                                $k = Substr($sectionName,0,-1) .$k;
                                write_to_database($k, $v, $modulecode, $academicyear);
                            }
                        }
                    }
                    break;
                
                case 'availability':            //MOO-2415 Made changes for inclusion availability
                    $sectionName = $k;
                    $section = '';
                    if (is_array($v)){
                        if (empty($v)){
                            write_to_database($sectionName, 0, $modulecode, $academicyear);
                        } else{
                            write_to_database($sectionName, 1, $modulecode, $academicyear);
                        }                      
                        $array = json_decode(json_encode($v), true);               
                                       
                        foreach($array as $index => $value){                          
                            $subarray = json_decode(json_encode($value), true);
                            
                            if ((count($subarray['second']['courses'])) > 0){
                                foreach($subarray as $k => $v){
                                    $section = $subarray['first'];
                                    if (is_array($v)){
                                        foreach($v as $key => $val){                                          
                                            if (!(empty($val))){
                                                if($key == 'courses'){
                                                    foreach($val as &$value){   
                                                        if ((count($value['routes'])) == 1){                                                          
                                                            $value['courseName'] = 'Year ' . $value['routes'][0]['block'] .' of ' .$value['courseName'];
                                                            unset($value['routes']);                                                                                                                     
                                                        }
                                                        write_to_database($section, serialize($val), $modulecode, $academicyear);
                                                    }
                                                } else{
                                                   $section = $section .$key;
                                                    foreach($subarray['second']['comments'] as $k => $v){
                                                        write_to_database($section, $v, $modulecode, $academicyear);
                                                    }                                                   
                                                }                                            
                                            }
                                        }
                                    }                                   
                                }
                            }
                            else{
                                $section = $subarray['first'];
                                foreach($subarray['second']['comments'] as $k => $v){
                                    $newArray = array();
                                    $newArray[0]['courseName'] = $v;
                                    $newArray[0]['courseCode'] = '';
                                }
                                write_to_database($section, serialize($newArray), $modulecode, $academicyear);
                            }
                        }
                    }
                    break;
                
                case 'preRequisiteModules':
                case 'studyAmounts':
                case 'postRequisiteModules': 
                case 'antiRequisiteModules':
                    
                    $sectionName = $k;
                    if (is_array($v)){
                        //MOO-1808 code to parse through an Array of data structures
                        $array = json_decode(json_encode($v), true);
                        
                        foreach($array as &$value){
                            
                            if (isset($value['requiredDescription'])){
                                $value['requiredDescription'] = extract_course_weightings($value['requiredDescription'], $cataloguedata->totalStudyHours); 
                            }                          
                        }
  
                        $encoded_serialized_string = serialize($array); 
                        
                        write_to_database($sectionName, $encoded_serialized_string, $modulecode, $academicyear);
                    }   
                    break;
                    
                case 'assessmentGroups':
                    $sectionName = $k;
                    $array = array();$subArray = array();
                        if (is_array($v)){
                        foreach($cataloguedata->$sectionName as $k => $v){
                            if ($v instanceof stdClass){
                                if ($k == 0){
                                    $sectionName = "assessments";                                         
                                } else{
                                    $sectionName = "resit";
                                }
                                if($v instanceof stdClass){
                                    $array = json_decode(json_encode($v), true);
                                    foreach($array as $k => $val){
                                        if (is_array($val)){                                          
                                            $encoded_serialized_string = serialize($val);
                                            write_to_database($sectionName, $encoded_serialized_string, $modulecode, $academicyear);                                          
                                        } else{                                  
                                            write_to_database($sectionName .$k, $val, $modulecode, $academicyear);                                          
                                        } 
                                    } 
                                    
                                }                          
                            }                            
                        }
                    }
                    break;
                }
          }
          // MOO-1808 Insert new data from JSON into database: logic to insert all root data in json file.
          else{
              /*
               * MOO 1935 Modified else code to fix presentation of information as separated paragraphs.
               */
              if (($k == "outlineSyllabus") || ($k == "indicativeReadingList") || ($k == "aims") || ($k == "transferableSkills") || ($k == "introductoryDescription") || ($k == "subjectSpecificSkills") || ($k == "privateStudyDescription")){
                  
                  if ((!(is_null($v))) && (strlen($v) >= 2)){  /* MOO 2019 remove any null values */
                      
                      $value = implode('<br />', expand_array($v));
                      write_to_database($k, $value, $modulecode, $academicyear);                     
                  } else{
                      $value = "No skills defined for this module.";
                      write_to_database($k, $value, $modulecode, $academicyear);
                  }      
              } else{
                  write_to_database($k, $v, $modulecode, $academicyear);
              }    
          }

        }
      }
    }
  }

  return $cataloguedata;
}

/*
 * MOO-1808 write-to-database function to write/modify database entry
 * if record exists, update_record is triggered to update record
 * else, if no record exists, insert_record inserts new record.
 */
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
            $k = "locations" .$k;
            if( !$DB->record_exists('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear' => $academicyear, 'labelkey' => $k)) ){
                $DB->insert_record('modulecatalogue_data', array('modulecode' => $modulecode,'academicyear'=> $academicyear, 'labelkey' => $k, 'labelvalue' => $v));
            }
        }
    }                      
}

/*
 * MOO 1935 expand array function introduced.
 * this method will take a string, convert to an array
 * removing any empty elements and return the array.
 */
function expand_array($value){
   
    $valueArray = array();   
    $valueArray = explode(("<br />"),nl2br($value));    
    $i = 0;
                 
    //clean up and remove any null or empty values from the array
    foreach ($valueArray as $value){
        if ((strlen($value)) <= 3) {
            unset($valueArray[$i]);
        }
        /*
         * MOO-1983 Added logic to capitalize first character if not capitalized 
         * and replace leading bullet point for the HTML type
         */
        if (preg_match("/[a-z]/", substr(trim($value),0,1))){
            $valueArray[$i] = ucfirst(trim($value));
        }
        
        elseif((ord(substr(trim($value), 0, 1)) == 194) || (ord(substr(trim($value), 0, 1)) == 239)){
            $valueArray[$i] = ucfirst(substr(trim($value), 3));
        }

        $i++;
    }
    //re-index the array 
    return array_values($valueArray);
}

/*
 * MOO-1983 Added function url_extract, to check if the alert information 
 * provided includes a URL link. The field needs to be extracted and displayed
 */
function url_extract($value){
    $matches = "";
    preg_match_all('!https?://\S+!', $value, $matches);
    $all_urls = $matches[0];
    return implode($all_urls);
}

/*
 * MOO-2373 extract_course_weightings() to calculate the weights not added in JSON file
 * takes the parameter name, value and total value to express as percentage
 */
function extract_course_weightings($v, $totalVal){
    
    if (stripos($v, 'session')!= 0){
        $numSess = intval(substr($v,0, stripos($v, 'session')-1));
            if (stripos($v, 'hour')<>0){
                if (stripos($v, 'minute')>0){
                    $valSessHrs = intval(trim(substr($v, stripos($v, 'hour')-3,2),' '));
                    $valSessMins = intval(trim(substr($v, stripos($v, 'minute')-3,2),' '));
                    $valSess = ((($valSessHrs * 60) + $valSessMins)/60);
                    $v = $v .' (' .(($numSess * $valSess)/ $totalVal)*100 .'%)';
                } else{
                    $valSess = intval(trim(substr($v, stripos($v, 'hour')-3,2),' '));
                    $v = $v .' (' .round((($numSess * $valSess)/ $totalVal)*100,1) .'%)';  //round(ceil($number*100)/100,2);                   
                }               
            } else{ //sessions in minutes
                $valSessMins = intval(trim(substr($v, stripos($v, 'minute')-3,2),' '));
                $v = $v .' (' .round((($numSess * ($valSessMins / 60))/ $totalVal)*100,1) .'%)';
            }
          
            } else{
                if (stripos($v, 'minute')>0){                
                    $valSess = intval(trim(substr($v, stripos($v, 'hour')-3,2),' '));
                } else{
                    $valSess = intval(trim(substr($v, 0, stripos($v, 'hour')),' '));                    
                }
                $v = $v .' (' .round((($valSess / $totalVal)*100),1) .'%)';               
            }

    return $v;
}
function stdToArray($obj){
  $reaged = (array)$obj;
  foreach($reaged as $key => &$field){
    if(is_object($field))$field = stdToArray($field);
  }
  return $reaged;
}


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
    // $url = 'https://courses-dev.warwick.ac.uk/modules/' .'20/21' ."/" .$modulecode . '.json';

    //$curldata = download_file_content($url, array('Authorization' => 'Basic ' .
    //  (string)base64_encode( $username . ":" . $password )), false, true);

    $curldata = download_file_content($url, null, false, true);

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
      
      write_to_database('alertmessage', implode(expand_array(rtrim($alertmessage,$all_urls)),'<br />') , $modulecode, $academicyear);
      write_to_database('urllink', $all_urls, $modulecode, $academicyear);
      write_to_database('alert', $applyAlert, $modulecode, $academicyear);

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
                    $value = implode($v, '<br />');
                    write_to_database($sectionName, $value, $modulecode, $academicyear);
                    
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
                    
                case ($k == 'postRequisiteModules') || ($k == 'preRequisiteModules') || ($k == 'studyAmounts') :
                    $sectionName = $k;
                    if (is_array($v)){
                        $array_size= count($v);     // MOO-1808 determine size of array
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
                        }
                    }
                    
                case ($k == 'assessmentGroups'):
                    $sectionName = $k;
                    $array = array();$subArray = array();
                    if (is_array($v)){
                        foreach($cataloguedata->$sectionName as $k => $v){
                            if ($v instanceof stdClass){
                                foreach($cataloguedata->assessmentGroups[0] as $k => $v){
                                    if(is_array($v)){
                                        if ($k == 'components'){
                                            $array = $v;
                                            for ($x = 0; $x <=  count($array)-1; $x++){
                                                $subArray = get_object_vars($array[$x]);
                                                foreach($subArray as $k => $v){
                                                    $k = "assesmentGrp" .$k ."$x";
                                                    write_to_database($k, $v, $modulecode, $academicyear);
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        write_to_database($k, $v, $modulecode, $academicyear);
                                    }
                                }
                            }
                        }
                    }
            }
          }
          // MOO-1808 Insert new data from JSON into database: logic to insert all root data in json file.
          else{
              /*
               * MOO 1935 Modified else code to fix presentation of information as separated paragraphs.
               */
              if (($k == "outlineSyllabus") || ($k == "indicativeReadingList") || ($k == "aims") || ($k == "transferableSkills") || ($k == "introductoryDescription") || ($k == "subjectSpecificSkills") || ($k == "privateStudyDescription")){
                  if (!(is_null($v))){  /* MOO 2019 remove any null values */
                      $value = implode(expand_array($v),'<br />');
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

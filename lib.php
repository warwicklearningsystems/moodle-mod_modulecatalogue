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
 * Library of interface functions and constants for module modulecatalogue
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 *
 * All the modulecatalogue specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_modulecatalogue
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Example constant, you probably want to remove this :-)
 */
define('MODULECATALOGUE_ULTIMATE_ANSWER', 42);

require_once($CFG->dirroot . '/mod/modulecatalogue/classes/renderer.php');

/* Moodle core API */

/**
 * Returns the information on whether the module supports a feature
 *
 * See {@link plugin_supports()} for more info.
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function modulecatalogue_supports($feature) {
    switch($feature) {
        case FEATURE_IDNUMBER:                return true;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_MOD_INTRO:               return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return false;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_BACKUP_MOODLE2:          return true;
        case FEATURE_NO_VIEW_LINK:            return true;

        default: return null;
    }
}

/**
 * Saves a new instance of the modulecatalogue into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $modulecatalogue Submitted data from the form in mod_form.php
 * @param mod_modulecatalogue_mod_form $mform The form instance itself (if needed)
 * @return int The id of the newly inserted modulecatalogue record
 */
function modulecatalogue_add_instance(stdClass $modulecatalogue, mod_modulecatalogue_mod_form $mform = null) {
    global $DB;

    $modulecatalogue->timecreated = time();
    $modulecatalogue->id = $DB->insert_record('modulecatalogue', $modulecatalogue);

    return $modulecatalogue->id;
}

/**
 * Updates an instance of the modulecatalogue in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param stdClass $modulecatalogue An object from the form in mod_form.php
 * @param mod_modulecatalogue_mod_form $mform The form instance itself (if needed)
 * @return boolean Success/Fail
 */
function modulecatalogue_update_instance(stdClass $modulecatalogue, mod_modulecatalogue_mod_form $mform = null) {
    global $DB;

    $modulecatalogue->timemodified = time();
    $modulecatalogue->id = $modulecatalogue->instance;
    $result = $DB->update_record('modulecatalogue', $modulecatalogue);

    return $result;
}

/**
 * This standard function will check all instances of this module
 * and make sure there are up-to-date events created for each of them.
 * If courseid = 0, then every modulecatalogue event in the site is checked, else
 * only modulecatalogue events belonging to the course specified are checked.
 * This is only required if the module is generating calendar events.
 *
 * @param int $courseid Course ID
 * @return bool
 */
function modulecatalogue_refresh_events($courseid = 0) {
    global $DB;

    if ($courseid == 0) {
        if (!$modulecatalogues = $DB->get_records('modulecatalogue')) {
            return true;
        }
    } else {
        if (!$modulecatalogues = $DB->get_records('modulecatalogue', array('course' => $courseid))) {
            return true;
        }
    }

    foreach ($modulecatalogues as $modulecatalogue) {
        // Create a function such as the one below to deal with updating calendar events.
        // modulecatalogue_update_events($modulecatalogue);
    }

    return true;
}

/**
 * Removes an instance of the modulecatalogue from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function modulecatalogue_delete_instance($id) {
    global $DB;

    if (! $modulecatalogue = $DB->get_record('modulecatalogue', array('id' => $id))) {
        return false;
    }

    $DB->delete_records('modulecatalogue', array('id' => $modulecatalogue->id));

    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 *
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @param stdClass $course The course record
 * @param stdClass $user The user record
 * @param cm_info|stdClass $mod The course module info object or record
 * @param stdClass $modulecatalogue The modulecatalogue instance record
 * @return stdClass|null
 */
function modulecatalogue_user_outline($course, $user, $mod, $modulecatalogue) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * It is supposed to echo directly without returning a value.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $modulecatalogue the module instance record
 */
function modulecatalogue_user_complete($course, $user, $mod, $modulecatalogue) {
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in modulecatalogue activities and print it out.
 *
 * @param stdClass $course The course record
 * @param bool $viewfullnames Should we display full names
 * @param int $timestart Print activity since this timestamp
 * @return boolean True if anything was printed, otherwise false
 */
function modulecatalogue_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link modulecatalogue_print_recent_mod_activity()}.
 *
 * Returns void, it adds items into $activities and increases $index.
 *
 * @param array $activities sequentially indexed array of objects with added 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 */
function modulecatalogue_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@link modulecatalogue_get_recent_mod_activity()}
 *
 * @param stdClass $activity activity record with added 'cmid' property
 * @param int $courseid the id of the course we produce the report for
 * @param bool $detail print detailed report
 * @param array $modnames as returned by {@link get_module_types_names()}
 * @param bool $viewfullnames display users' full names
 */
function modulecatalogue_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 *
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * Note that this has been deprecated in favour of scheduled task API.
 *
 * @return boolean
 */
function modulecatalogue_cron () {
    return false;
}

/**
 * Returns all other caps used in the module
 *
 * For example, this could be array('moodle/site:accessallgroups') if the
 * module uses that capability.
 *
 * @return array
 */
function modulecatalogue_get_extra_capabilities() {
    return array();
}


/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 *
 * @global object
 * @param object $coursemodule
 * @return cached_cm_info|null
 */
function modulecatalogue_get_coursemodule_info($coursemodule) {
    global $DB, $COURSE, $PAGE;
    
    $metadata = get_course_metadata($COURSE->id);
    
    $academic_year = "";
    $module_Code = "";
    
    $usedefault = 0;
    
    if (isset($metadata)){
            foreach($metadata as $k => $v){
                switch ($k){
                    case 'Module Code':
                        $module_Code = $v;
                    case 'Academic Year':
                        $academic_year = $v;
                }
            } 
        }

    // Retrieve details on this catalogue instance including template and module code
    // MOO-1813: modified query to DB method to now use the academicyear from the table mdl_modulecatalogue.
    if ($modcat = $DB->get_record('modulecatalogue',
      array('id'=>$coursemodule->instance), 'id, name, modulecode, academicyear, defaultcodes, template, intro, introformat, timecreated, adminsupport, adminsupportname')) {

        if (empty($modcat->name)) {
            // modulecatalogue name missing, fix it
            $modcat->name = "modulecatalogue{$modcat->id}";
            $DB->set_field('modulecatalogue', 'name', $modcat->name, array('id'=>$modcat->id));
        }
        
        // Build information ready for display...
        $info = new cached_cm_info();
        
        // no filtering hre because this info is cached and filtered later
        $info->content = format_module_intro('modulecatalogue', $modcat, $coursemodule->id, false);
        $info->name  = $modcat->name;
        $info->template = $modcat->template;
        $info->modulecode = $modcat->modulecode;
        $info->academicyear = $modcat->academicyear;
        $info->defaultcodes = $modcat->defaultcodes;
        $info->timecreated = $modcat->timecreated;
        $info->adminsupport = $modcat->adminsupport;
        $info->adminsupportname = $modcat->adminsupportname;  
        
        //Moo 1888 Added new fields to be stored in database
        $adminemail = $modcat->adminsupport;
        $adminname = $modcat->adminsupportname;
        
        //Moo 1826 set variables to that of the default codes.
        if ($modcat->defaultcodes == 1){
            $academicyear = get_full_year($academic_year);
            $modulecode = $module_Code;
        } else{
            $modulecode = $modcat->modulecode;
            $academicyear = $modcat->academicyear;
        }

        // Set name of module catalogue item if empty
        if (empty($modcat->name)) {
          $modcat->name = "Module catalogue {$modcat->id}";
          $DB->set_field('modulecatalogue', 'name', $modcat->name, array('id'=>$modcat->id));
        }
        $info->name = $modcat->name;

        // TODO: if $modcat->modulecode is empty, we could infer module code from $COURSE->idnumber
        // (i.e. splitting characters before the '-', if there are five of them in
        // pattern [A-Z][A-Z][A-Z0-9][A-Z0-9][A-Z0-9]
        //$modulecode = $modcat->modulecode;
      	//MOO-1813: set the value of $academicyear to the value from the data stored in mdl_modulecatalogue
        //$academicyear = $modcat->academicyear; 
        if (isset($modcat->usedefault)){
            $usedefault = $modcat->usedefault;
        }
      
               
        if($modulecode != '') {

          // Get data from API and store
          get_modulecatalogue_data($modulecode, $academicyear, $adminname, $adminemail);

          // Get data from DB
          
          if ($moddata = $DB->get_records_menu('modulecatalogue_data',
            array('modulecode' => $modulecode, 'academicyear' => $academicyear),'', 'labelkey, labelvalue')) { //MOO-1813: Modified get_records_menu to use newly added $academicyear

            // Build data set ready for rendering
            $t = new \mod_modulecatalogue\output\cataloguedata($moddata, $modcat->template);
           // print_r($moddata);
            // Render item for display
            $catdisplay = $PAGE->get_renderer('mod_modulecatalogue');
            $info->content = $catdisplay->render($t);

          } else {
            // No catalogue data to retrieve
            // MOO-1813: Modified functionality to provide more meaningful information if no records exist.
            $info->content = get_string('nocataloguedata', 'modulecatalogue') .' Course Code: ' .$modulecode ."Academic Year: " .$academicyear;
          }

        } else {
          // No module code, so we don't attempt to retrieve anything
          $info->content = get_string('nocataloguedata', 'modulecatalogue');
        }

        return $info;
    } else {
        return null;
    }
}
/* MOO-1826 Inserted function to retrieve course metadata.
 * this contains all the default codes
 */
function get_course_metadata($courseid) {
    $handler = \core_customfield\handler::get_handler('core_course', 'course');
    // This is equivalent to the line above.
    //$handler = \core_course\customfield\course_handler::create();
    $datas = $handler->get_instance_data($courseid);
    
    $metadata = [];
    foreach ($datas as $data) {
        if (empty($data->get_value())) {
            continue;
        }
        $cat = $data->get_field()->get_category()->get('name');
        $metadata[$data->get_field()->get('name')] = $data->get_value();
    }

    return $metadata;
}
/* Moo-1826 Inserted function to retrieve full academic year from default parameters
 * $academic year is in format XX/XX needs to be in XXXX format
 */
function get_full_year($academic_year){
    
    $academicyear = '';
    if (!(is_null(strpos($academic_year, '/')))){
         $academicyear = '20' .substr($academic_year, 0, stripos($academic_year, '/'));
    }
    return $academicyear;
}

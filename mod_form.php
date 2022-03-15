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
 * The main modulecatalogue configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_modulecatalogue
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot. '/mod/modulecatalogue/db/defaultcodes.php'); //MOO2373 use defaultcodes 

/**
 * Module instance settings form
 *
 * @package    mod_modulecatalogue
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_modulecatalogue_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG, $COURSE; 

        $mform = $this->_form;
               
        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $TEMPLATEOPTIONS = array('fullentry1' => 'Official module content description',
            'shortentry1' => 'Short module catalogue summary', 
            'fancyentry' => 'Fancy summary',
            'catalogue1' => 'Catalogue 1 entry');

       // MOO-1813: list of options in dropdown list box. user presented in format xx/xx as in 20/21 but stored as xxxx as in 2020 as JSON data uses 4 digit years.
        $ACADEMICYEAROPTIONS = array(2019 => "19/20", 2020 => "20/21", 2021 => "21/22", 2022 => "22/23", 2023 => "23/24", 2024 => "24/25", 2025 => "25/26", 2026 => "26/27", 2027 => "27/28", 2028 => "28/29",2029 => "29/30", 2030 => "30/31");
        $mform->addElement('select', 'template', get_string('template', 'modulecatalogue'), $TEMPLATEOPTIONS);
        $mform->addHelpButton('template', 'template', 'modulecatalogue');
        
        //MOO-1826 get course metadata to retrieve current default codes;
        $metadata = get_course_metadata($COURSE->id);
        /*
         * MOO2373 use defaultcodes
         */
        if (isset($metadata)){
            if (!(isset($metadata["Academic Year"]))) {
                $metadata["Academic Year"] = modulecatalogue_current_academic_year();
            }
            $defaultCodes =  new DefaultCodes();
            $defaultCodes->moduleCode = $metadata['Module Code'];
            $defaultCodes->academicYear = $metadata['Academic Year'];
        }
                
        //MOO-1888: Added text box for adminsupport name to hold name of admin support.
        $options = ['size' => 80, 'maxlength' => 80, 'pattern'=>"[a-zA-Z][a-zA-Z\s]*", 'title'=>"Please use only Alphabetic characters"];
        $mform->addElement('text', 'adminsupportname', get_string('adminsupportname', 'modulecatalogue'), $options);
        $mform->setType('adminsupportname', PARAM_TEXT);
        $mform->addHelpButton('adminsupportname', 'adminsupportname', 'modulecatalogue');
        
        //MOO-1888: Modified existing text box for adminsupport to hold email address and validation.
        $options = ['size' => 80, 'maxlength' => 80, 'pattern'=>"[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$", 'title'=>"Please include the '@' in the e-Mail address."];
        $mform->addElement('text', 'adminsupport', get_string('adminsupport', 'modulecatalogue'), $options);
        $mform->setType('adminsupport', core_user::get_property_type('email'));
        $mform->addHelpButton('adminsupport', 'adminsupport', 'modulecatalogue');
        
        //MOO-1826 Inserted options for default codes;
        $autopopulateoptions = array(
                0 => get_string('no'),
                1 => get_string('yes'),
            ); 
        
        $mform->addElement('select', 'defaultcodes', get_string('defaultcodes', 'modulecatalogue'), $autopopulateoptions);
        $mform->addHelpButton('defaultcodes', 'defaultcodes', 'modulecatalogue');
        
        //MOO-1826 customize module code to prevent unwanted entries been entered and force users to enter right format;
        $str = 'autoupdate';
        $options = ['size' => 8, 'maxlength' => 8, 'pattern'=>"[A-Za-z]{2}[0-9A-Za-z]{3}[-]{1}[0-9]{1,2}", 'title'=>"Please enter the course code as in AANNN-NN", 'required']; //MOO-1983 removed space not needed
        $mform->addElement('text', 'modulecode', get_string('modulecode', 'modulecatalogue'), $options);
        $mform->addHelpButton('modulecode', 'modulecode', 'modulecatalogue');
       
        //MOO-1826 Inserted options for default codes 
        
        $mform->setType('modulecode', PARAM_ALPHANUMEXT);
        //MOO-1813: New dropdown list box for user to enter academic year.
        $mform->addElement('select', 'academicyear', get_string('academicyear', 'modulecatalogue'), $ACADEMICYEAROPTIONS);      
        $mform->addHelpButton('academicyear', 'academicyear', 'modulecatalogue');
        
        //Moo 2373 Modified to use default codes class 
	if (((is_null($defaultCodes->moduleCode)) || (is_null($defaultCodes->academicYear))) || (!(isset($metadata)))){        
            $mform->setDefault('autoupdate',1);
            $mform->setDefault('defaultcodes', 0);
        } else{
            $mform->setDefault('modulecode', $defaultCodes->moduleCode);
            $key = array_search($defaultCodes->academicYear, $ACADEMICYEAROPTIONS);
            $mform->setDefault('academicyear', $key);
            $mform->setDefault('defaultcodes', 1);
        }
        
        $mform->addElement('static', 'autoupdatenote', '', get_string('autoupdatenote', 'modulecatalogue'));
        $mform->disabledIf('modulecode', 'defaultcodes', 'eq', 1);
        $mform->disabledIf('academicyear', 'defaultcodes', 'eq', 1);       
        // Add standard grading elements.
        //$this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }
}

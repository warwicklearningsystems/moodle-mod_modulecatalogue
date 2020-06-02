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
        global $CFG;

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $TEMPLATEOPTIONS = array('fullentry1' => 'Official module content description',
                            'shortentry1' => 'Short module catalogue summary',
                            'fancyentry' => 'Fancy summary',
                            'catalogue1' => 'Catalogue 1 entry');

        $ACADEMICYEAROPTIONS = array("2019" => "19/20", "2020" => "20/21", "2021" => "21/22", "2022" => "22/23", "2023" => "23/24", "2024" => "24/25", "2025" => "25/26", "2026" => "26/27", "2027" => "27/28", "2028" => "28/29","2029" => "29/30", "2030" => "30/31");
        
        $mform->addElement('select', 'template', get_string('template', 'modulecatalogue'), $TEMPLATEOPTIONS);

        $mform->addElement('text', 'modulecode', get_string('modulecode', 'modulecatalogue'), array('size' => '64'));
        $mform->setType('modulecode', PARAM_ALPHANUMEXT);
        
        $mform->addElement('select', 'academicyear', get_string('academicyear', 'modulecatalogue'), $ACADEMICYEAROPTIONS);      

	$mform->addElement('text', 'adminsupport', get_string('adminsupport', 'modulecatalogue'), array('size' => '128'));
        $mform->setType('adminsupport', PARAM_ALPHANUMEXT);

        // Add standard grading elements.
        //$this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }
}

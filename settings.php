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
 * Url module admin settings and defaults
 *
 * @package    mod_modulecatalogue
 * @copyright  2018 Learning Support Systems, University of Warwick
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!defined('MOODLE_INTERNAL')) {
  die('Direct access to this script is forbidden.');
}

if ($ADMIN->fulltree) {
  require_once("$CFG->libdir/resourcelib.php");

  $settings->add( new admin_setting_configtext('mod_modulecatalogue/apiusername',
    get_string('catalogue_username', 'mod_modulecatalogue'),
    get_string('catalogue_username_desc', 'mod_modulecatalogue'), '') );

  $settings->add( new admin_setting_configpasswordunmask('mod_modulecatalogue/apipassword',
    get_string('catalogue_password',  'mod_modulecatalogue'),
    get_string('catalogue_password_desc',  'mod_modulecatalogue'), '') );
  
    /*
    * MOO-1983 Added new field, alertinformation to setup module/course wide general information
    */
    $options = array(0 => get_string('no'), 1 => get_string('yes'));
    $settings->add(new admin_setting_configselect('mod_modulecatalogue/applyAlert',
            get_string('applyAlert', 'mod_modulecatalogue'),
            get_string('applyAlert_desc', 'mod_modulecatalogue'), 0, $options));
    
    $settings->add( new admin_setting_configtextarea('mod_modulecatalogue/alertinformation',
            get_string('alertinformation', 'mod_modulecatalogue'),
            get_string('alertinformation_desc', 'mod_modulecatalogue'),'', PARAM_TEXT, 100, 5 ));
}



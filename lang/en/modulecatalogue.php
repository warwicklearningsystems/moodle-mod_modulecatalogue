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
 * English strings for modulecatalogue
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_modulecatalogue
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Module catalogue';
$string['modulenameplural'] = 'Module catalogues';
$string['modulename_help'] = 'Use the modulecatalogue module for... | The modulecatalogue module allows...';
$string['modulecatalogue:addinstance'] = 'Add a new modulecatalogue';
$string['modulecatalogue:submit'] = 'Submit modulecatalogue';
$string['modulecatalogue:view'] = 'View modulecatalogue';
$string['modulecataloguefieldset'] = 'Custom example fieldset';
$string['modulecataloguename'] = 'modulecatalogue name';
$string['modulecataloguename_help'] = 'This is the content of the help tooltip associated with the modulecataloguename field. Markdown syntax is supported.';
$string['modulecatalogue'] = 'Module catalogue';
$string['pluginadministration'] = 'modulecatalogue administration';
$string['pluginname'] = 'Module catalogue';
$string['defaultcodes'] = 'Use default module code and academic year';
$string['defaultcodes_help'] = 'Check \'Yes\' if you wish to use the current Course code and academic year.<br />
this will automatically retrieve the current Course code setup in Course Settings.<br />
If No default course code is vailable or have not yet been setup, you will be required to use new course code.<br />
To not use the default code, change this option back to \'No\', to enter new course code and academic year';
$string['modulecode'] = 'Module code and credits (eg CS118-15)';
$string['modulecode_help'] = 'Enter the module code followed by a dash and the number of credits as in the following example: <br> CS123-15';

$string['adminsupport'] = 'Course support email';
$string['adminsupport_help'] = 'Please enter support contact email';
$string['adminsupportname'] = 'Course support contact';
$string['adminsupportname_help'] = 'Please enter name of the appropriate support contact staff or team';

$string['template'] = 'Template';
$string['template_help'] = 'Please select which template to use, depending on the amount of information required: <br />  "Tabbed summary" provides most information arranged neatly in 4 different sections or tabs <br /> "Full collapsed Summary" is a complete breakdown of course information <bR /> "Short collapsed summary" is a summary of the full, providing assessment information and outline syllabus ';
$string['academicyear'] = 'Academic Year'; //MOO-1813 Added legend for dropdown list box in parameter entry screen
$string['academicyear_help'] = 'Please select Academic Year from the list. <br /> Default will be current academic year';

$string['nocataloguedata'] = 'No module catalogue data available';
$string['usedefaultcode'] = 'Use new Code and Academic Year';
$string['autoupdatenote'] = '<p style="color:#11054D;font-size:0.9em;"><i></i></p>';

$string['catalogue_username'] = 'Username';
$string['catalogue_username_desc'] = 'Username used to authenticate with Module catalogue API';
$string['catalogue_password'] = 'Password';
$string['catalogue_password_desc'] = 'Password used to authenticate with Module catalogue API';

/*
 *  MOO-1983 Added new field, alertinformation to setup module/course wide general information
 */
$string['alertinformation'] = 'University Alert on all Courses';
$string['alertinformation_desc'] = 'General alert applicable to all modules and Courses';
$string['applyAlert'] = 'Apply alert on all Courses';
$string['applyAlert_desc'] = 'Use alert on all modules and Courses';
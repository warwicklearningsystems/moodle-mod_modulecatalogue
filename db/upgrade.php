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
 * This file keeps track of upgrades to the modulecatalogue module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package    mod_modulecatalogue
 * @copyright  2016 University of Warwick <learnsys@warwick.ac.uk>
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute modulecatalogue upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_modulecatalogue_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.
    
    if ($oldversion < 2020083000){
        $table = new xmldb_table('modulecatalogue');
        $field = new xmldb_field('academicyear');
        $field1 = new xmldb_field('defaultcodes');
        $precision = "1";
        $default = "0";
        $field->set_attributes(XMLDB_TYPE_CHAR, 6, XMLDB_UNSIGNED, XMLDB_NOTNULL, false, "");
        $field1->set_attributes(XMLDB_TYPE_INTEGER, $precision, XMLDB_UNSIGNED, XMLDB_NOTNULL, false, $default);
        if (!$dbman->field_exists($table, $field)){
            $dbman->add_field($table, $field);
        }
        
        if (!$dbman->field_exists($table, $field1)){
            $dbman->add_field($table, $field1);
        }
    }
    
    /*
     * MOO-1888 Added two new columns to the table modulecatalogue
     * to add admin email and administrator name to initial setup screen
     */
    if ($oldversion < 2020090400){
        $table = new xmldb_table('modulecatalogue'); 
        $field = new xmldb_field('adminsupport');
        $field1 = new xmldb_field('adminsupportname');
        $precision = "100";
        $default = "";
        $field->set_attributes(XMLDB_TYPE_CHAR, $precision, false, XMLDB_NOTNULL, false, $default);
        $field1->set_attributes(XMLDB_TYPE_CHAR, $precision, false, XMLDB_NOTNULL, false, $default);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
            $dbman->add_field($table, $field1);
        }
    }

    return true;
}

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
 * Upgrade handler file.
 *
 * @package    block_eventpage
 * @author     Andres Ramos
 * @copyright  2017 LMS Doctor
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_block_eventpage_upgrade($oldversion) {
    global $CFG, $DB;

    $result = true;
    $dbman  = $DB->get_manager();

    if ($oldversion < 2018010200) {

        // Define table block_eventpage to be created.
        $table = new xmldb_table('block_eventpage');

        // Adding fields to table block_eventpage.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '254', null, XMLDB_NOTNULL, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('startdate', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('starttime', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('endtime', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('logopath', XMLDB_TYPE_CHAR, '150', null, XMLDB_NOTNULL, null, null);
        $table->add_field('creatorid', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('themecolor', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('fontcolor', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('linkcolor', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('latitude', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('longitude', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('distance', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('street', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('city', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('zipcode', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('other', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_eventpage.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_eventpage.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Eventpage savepoint reached.
        upgrade_block_savepoint(true, 2018010200, 'eventpage');
    }

    if ($oldversion < 2018010201) {

        // Define field courseid to be added to block_eventpage.
        $table = new xmldb_table('block_eventpage');
        $field = new xmldb_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'description');

        // Conditionally launch add field courseid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Eventpage savepoint reached.
        upgrade_block_savepoint(true, 2018010201, 'eventpage');
    }

    if ($oldversion < 2018010202) {

        // Changing type of field latitude on table block_eventpage to number.
        $table = new xmldb_table('block_eventpage');
        $field = new xmldb_field('latitude', XMLDB_TYPE_NUMBER, '10', null, null, null, null, 'linkcolor');

        // Launch change of type for field latitude.
        $dbman->change_field_type($table, $field);

        // Changing type of field longitude on table block_eventpage to int.
        $table = new xmldb_table('block_eventpage');
        $field = new xmldb_field('longitude', XMLDB_TYPE_NUMBER, '10', null, null, null, null, 'latitude');

        // Launch change of type for field longitude.
        $dbman->change_field_type($table, $field);

        // Eventpage savepoint reached.
        upgrade_block_savepoint(true, 2018010202, 'eventpage');
    }

    if ($oldversion < 2018010203) {

        // Changing type of field latitude on table block_eventpage to number.
        $table = new xmldb_table('block_eventpage');
        $field = new xmldb_field('latitude', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'linkcolor');

        // Launch change of type for field latitude.
        $dbman->change_field_type($table, $field);

        // Changing type of field longitude on table block_eventpage to int.
        $table = new xmldb_table('block_eventpage');
        $field = new xmldb_field('longitude', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'latitude');

        // Launch change of type for field longitude.
        $dbman->change_field_type($table, $field);

        // Eventpage savepoint reached.
        upgrade_block_savepoint(true, 2018010203, 'eventpage');
    }

    if ($oldversion < 2018010204) {

        // Changing type of field latitude on table block_eventpage to number.
        $table = new xmldb_table('block_eventpage');
        $field = new xmldb_field('latitude', XMLDB_TYPE_NUMBER, '10', null, null, null, null, 'linkcolor');

        // Launch change of type for field latitude.
        $dbman->change_field_type($table, $field);

        // Changing type of field longitude on table block_eventpage to int.
        $table = new xmldb_table('block_eventpage');
        $field = new xmldb_field('longitude', XMLDB_TYPE_NUMBER, '10', null, null, null, null, 'latitude');

        // Launch change of type for field longitude.
        $dbman->change_field_type($table, $field);

        // Eventpage savepoint reached.
        upgrade_block_savepoint(true, 2018010204, 'eventpage');
    }

    if ($oldversion < 2018010205) {

        // Changing type of field latitude on table block_eventpage to number.
        $table = new xmldb_table('block_eventpage');
        $field = new xmldb_field('latitude', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'linkcolor');

        // Launch change of type for field latitude.
        $dbman->change_field_type($table, $field);

        // Changing type of field longitude on table block_eventpage to int.
        $table = new xmldb_table('block_eventpage');
        $field = new xmldb_field('longitude', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'latitude');

        // Launch change of type for field longitude.
        $dbman->change_field_type($table, $field);

        // Eventpage savepoint reached.
        upgrade_block_savepoint(true, 2018010205, 'eventpage');
    }

    return $result;
}
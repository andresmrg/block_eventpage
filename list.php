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
 * View all requests displayed on an HTML table.
 *
 * @package     block_eventpage
 * @copyright   2017 Andres Ramos
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/tablelib.php');
require('list_table.php');
global $OUTPUT, $PAGE;

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/eventpage/list.php');
$PAGE->set_pagelayout('standard');

$PAGE->requires->js(new moodle_url('/blocks/eventpage/js/jquery.js'), true);
$PAGE->requires->js(new moodle_url('/blocks/eventpage/dist/sweetalert.min.js'), true);
$PAGE->requires->css('/blocks/eventpage/dist/sweetalert.css');
$PAGE->requires->js(new moodle_url('/blocks/eventpage/js/locallib.js'));

$download = optional_param('download', '', PARAM_ALPHA);

$table = new list_table('uniqueid');
$table->is_downloading($download, 'eventpagereport', get_string('pluginname',  'block_eventpage'));

if (!$table->is_downloading()) {
        // Define headers.
        $PAGE->set_title(get_string('eventlist', 'block_eventpage'));
        $PAGE->set_heading(get_string('eventlist', 'block_eventpage'));
        $PAGE->navbar->add(get_string('eventlist', 'block_eventpage'));
        echo $OUTPUT->header(); // Output header.
        $neweventurl = new moodle_url('/blocks/eventpage/process.php', array('action' => 'add'));
        echo html_writer::link($neweventurl, "Add New Event Page", array('class' => 'btn btn-default'));
}

$table->define_baseurl("$CFG->wwwroot/blocks/eventpage/list.php");
$table->set_sql('*', '{block_eventpage}', 1);
$table->out(30, true); // Print table.

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}

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
 * This file allows the user to edit a course.
 *
 * @package     block_eventpage
 * @author      Andrés Ramos
 * @copyright   2017 LMS Doctor
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('process_form.php');
require_once('locallib.php');

// Make sure guests can't access to this page.
require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

global $OUTPUT, $PAGE, $DB;

$PAGE->set_url('/blocks/eventpage/process.php');
$PAGE->set_pagelayout('standard');

$action   = required_param('action', PARAM_NOTAGS);
$courseid = optional_param('courseid', 0, PARAM_INT);
$eventid  = optional_param('id', 0, PARAM_INT);
$e        = block_eventpage_get_page($eventid);

// If course id is empty, we are probably editing.
if (!empty($courseid)) {
    $course = $DB->get_record('course', array('id' => $courseid), $fields='id, fullname');
} else {
    $course = $DB->get_record('course', array('id' => $e->courseid), $fields='id, fullname');
}

// Get course context.
$context = context_course::instance($course->id);
$PAGE->set_context($context);

// Deletion action. It redirects to the event page list.
if ($action == 'del') {
    $e = block_eventpage_get_page($eventid);
    $result = block_eventpage_delete_record($e);
    var_dump($result);

    $returnurl = new moodle_url('/blocks/eventpage/list.php');
    $returnmsg = 'The event page was succesfully deleted';
    redirect($returnurl, $returnmsg, null, \core\output\notification::NOTIFY_WARNING);
}

// Define headers.
$PAGE->set_title(get_string('title_editcourse', 'block_eventpage'));
$PAGE->set_heading(get_string('title_editcourse', 'block_eventpage'));


// Nav breadcump.
$PAGE->navbar->ignore_active();
// $PAGE->navbar->add($node, new moodle_url('/course/view.php', array('id' => $course->id)));
$PAGE->navbar->add(get_string('title_editcourse', 'block_eventpage'));


$mform    = new process_form();

// Load existing files into draft area.
if (empty($logopath->id)) {
    $logopath               = new stdClass;
    $logopath->id           = 0;
    $logopath->definition   = '';
    $logopath->format       = FORMAT_HTML;
}

$draftid_editor = file_get_submitted_draft_itemid('logopath');
file_prepare_draft_area($draftid_editor, $context->id, 'block_eventpage', 'logopath',
                                       $logopath->id, array('subdirs'=>true), $logopath->definition);

// Load description.
if (empty($description->id)) {
    $description = new stdClass;
    $description->id = null;
    $description->definition = '';
    $description->format = FORMAT_HTML;
}

// Prepare some data to send to the form.
$dataform           = new stdClass;
$dataform->logopath = $draftid_editor;
$dataform->courseid = $course->id;
$dataform->description['text'] = (isset($e->description)) ? $e->description : '';
$mform->set_data($dataform);

if ($from = $mform->get_data()) {

    // Content of editor.
    $from->description = $from->description['text'];
    $result = false;
    if ($from->action == 'add') {
        $result = block_eventpage_save_record($from);

        file_save_draft_area_files($from->logopath, $context->id, 'block_eventpage', 'logopath',
                                          $logopath->id, array('subdirs' => 0));

        $returnurl = new moodle_url('/course/view.php', array('id' => $course->id));
        $returnmsg = 'The event page was succesfully saved';
        redirect($returnurl, $returnmsg, null, \core\output\notification::NOTIFY_SUCCESS);
    }

    if ($from->action == 'edit') {
        file_save_draft_area_files($from->logopath, $context->id, 'block_eventpage', 'logopath',
                                          $logopath->id, array('subdirs' => 0));
        block_eventpage_update_record($from);
        $returnurl = new moodle_url('/course/view.php', array('id' => $course->id));
        $returnmsg = 'The event page was succesfully updated';
        redirect($returnurl, $returnmsg, null, \core\output\notification::NOTIFY_SUCCESS);
    }

    // We need to add code to appropriately act on and store the submitted data.
    // if (!$DB->update_record('block_eventpage_course', $mform)) {
    //     print_error('inserterror', 'block_eventpage');
    // }

    // $courseurl = new moodle_url('/blocks/eventpage/manageeventpage.php?success=edited');
    // redirect($courseurl);

} else {
    // ... mform didn't validate or this is the first display.
    echo $OUTPUT->header();
    $mform->display();
    // if (has_capability('block/eventpage:editeventpage', $context, $USER->id)) {
    //     $mform->display();
    // } else {
    //     print_error('nopermissiontoviewpage', 'error', '');
    // }
    echo $OUTPUT->footer();
}
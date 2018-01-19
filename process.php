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

global $OUTPUT, $PAGE, $DB, $USER, $CFG;

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
// $context = context_system::instance();
$context = context_course::instance($course->id);
$PAGE->set_context($context);

// Deletion action. It redirects to the event page list.
if ($action == 'del') {
    $e = block_eventpage_get_page($eventid);
    $result = block_eventpage_delete_record($e);

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

$maxbytes = $CFG->maxbytes;
$attachmentoptions  = array('subdirs'=>false, 'maxfiles'=>1, 'maxbytes'=>$maxbytes);
$mform              = new process_form(null, array('attachmentoptions'=>$attachmentoptions));

// $filemanageropts    = array('subdirs' => 0, 'maxbytes' => '0', 'maxfiles' => 1, 'context' => $context);
// $customdata         = array('filemanageropts' => $filemanageropts);

// $draftitemid        = file_get_submitted_draft_itemid('attachments');
// file_prepare_draft_area($draftitemid, $context->id, 'block_eventpage', 'attachment', $e->id, $filemanageropts);


// Could also use $CFG->maxbytes if you are not coding within a course context
echo "<br><br><br><br><br><br><br><br><br><br>";
if (isset($e) && !empty($e)) {
    $attachment = file_prepare_standard_filemanager($e, 'logopath', $attachmentoptions, $context,
                                           'block_eventpage', 'intro', 0);

}


// Load description.
if (empty($description->id)) {
    $description                = new stdClass;
    $description->id            = null;
    $description->definition    = '';
    $description->format        = FORMAT_HTML;
}

// $dataform                       = new stdClass;
// $dataform->id                   = (isset($e->id)) ? $e->id : null;
// // $dataform->attachments          = $draftitemid;
// $dataform->courseid             = $course->id;
// print_object($e->description);
// $e->description['text']  = (isset($e->description)) ? $e->description : '';

$mform->set_data($e);

if ($data = $mform->get_data()) {

    // Content of editor.
    $data->description  = $data->description['text'];
    $result             = false;

    if ($data->action == 'add') {

        // Save record and the attachment image.
        $itemid = block_eventpage_save_record($data);
        file_save_draft_area_files($data->attachments, $context->id, 'block_eventpage', 'attachment', 0, $filemanageropts);

        // Prepare URL to redirect.
        $returnurl = new moodle_url('/course/view.php', array('id' => $course->id));
        $returnmsg = 'The event page was succesfully saved';

        // Redirect to the course page.
        redirect($returnurl, $returnmsg, null, \core\output\notification::NOTIFY_SUCCESS);

    }

    if ($data->action == 'edit') {

        echo "FOMR SUBMISION: <br>";
        // print_object($data);
        echo "<br>";


        $data = file_postupdate_standard_filemanager($data, 'logopath', $attachmentoptions, $context,
                                              'block_eventpage', 'intro', 0);

        print_object($data);

        $data->logopath = $data->logopath_filemanager;
        block_eventpage_update_record($data);

        // file_save_draft_area_files($data->attachments, $context->id, 'block_eventpage', 'attachment', $itemid, $filemanageropts);

        $returnurl = new moodle_url('/course/view.php', array('id' => $course->id));
        $returnmsg = 'The event page was succesfully updated';
        redirect($returnurl, $returnmsg, null, \core\output\notification::NOTIFY_SUCCESS);

    }

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
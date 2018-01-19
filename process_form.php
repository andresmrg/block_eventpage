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
 * Form for editing a selfstudy course.
 *
 * @package     block_eventpage
 * @author      Andres Ramos
 * @copyright   2017 LMS Doctor
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir . '/formslib.php');

class process_form extends moodleform {

    public function definition() {
        global $DB, $PAGE;

        $PAGE->requires->js(new moodle_url('/blocks/eventpage/js/jquery.js'));
        $PAGE->requires->js(new moodle_url('/blocks/eventpage/js/jquery.timePicker.js'));
        $PAGE->requires->css('/blocks/eventpage/js/timePicker.css');

        $PAGE->requires->js(new moodle_url('/blocks/eventpage/js/locallib.js'));

        $mform    = & $this->_form;
        $courseid = optional_param('courseid', 0, PARAM_INT);
        $action   = optional_param('action', '', PARAM_NOTAGS);
        // $eventid  = optional_param('id', 0, PARAM_INT);

        // If editing, lets hide submit the id hidden.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'courseid', $courseid);
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'action', $action);
        $mform->setType('action', PARAM_NOTAGS);

        $mform->addElement('html', '<style></style>');

        $mform->addElement('header', 'headertime', get_string('general', 'block_eventpage'));

        $mform->addElement('text', 'name', get_string('name', 'block_eventpage'));
        $mform->setType('name', PARAM_NOTAGS);
        $mform->addRule('name', null, 'required', null, 'server');

        $mform->addElement('date_selector', 'startdate', get_string('startdate', 'block_eventpage'));
        $mform->setType('startdate', PARAM_NOTAGS);

        $mform->addElement('text', 'starttime', get_string('starttime', 'block_eventpage'));
        $mform->setType('starttime', PARAM_NOTAGS);
        $mform->addRule('starttime', null, 'required', null, 'server');

        $mform->addElement('text', 'endtime', get_string('endtime', 'block_eventpage'));
        $mform->setType('endtime', PARAM_NOTAGS);
        $mform->addRule('endtime', null, 'required', null, 'server');

        $mform->addElement ('editor', 'description', get_string('description', 'block_eventpage'));
        $mform->setType('description', PARAM_RAW);
        $mform->addRule('description', null, 'required', null, 'server');

        $mform->addElement('filemanager', 'logopath_filemanager', get_string('logo', 'block_eventpage'), null,
                    array('subdirs' => 0, 'maxbytes' => 10485760, 'areamaxbytes' => 10485760, 'maxfiles' => 1));

        // Location section.
        $mform->addElement('header', 'location', get_string('location', 'block_eventpage'));

        $mform->addElement('text', 'street', get_string('street', 'block_eventpage'));
        $mform->setType('street', PARAM_NOTAGS);
        $mform->addRule('street', null, 'required', null, 'server');

        $mform->addElement('text', 'city', get_string('city', 'block_eventpage'));
        $mform->setType('city', PARAM_NOTAGS);
        $mform->addRule('city', null, 'required', null, 'server');

        $mform->addElement('text', 'zipcode', get_string('zipcode', 'block_eventpage'));
        $mform->setType('zipcode', PARAM_NOTAGS);
        $mform->addElement('text', 'other', get_string('other', 'block_eventpage'));
        $mform->setType('other', PARAM_NOTAGS);

        $mform->addElement('text', 'latitude', get_string('latitude', 'block_eventpage'));
        $mform->setType('latitude', PARAM_NOTAGS);
        $mform->addElement('text', 'longitude', get_string('longitude', 'block_eventpage'));
        $mform->setType('longitude', PARAM_NOTAGS);
        $mform->addElement('text', 'distance', get_string('distance', 'block_eventpage'));
        $mform->setType('distance', PARAM_INT);


        // Advance section.
        $mform->addElement('header', 'advance', get_string('advance', 'block_eventpage'));

        $mform->addElement('text', 'themecolor', get_string('themecolor', 'block_eventpage'));
        $mform->setType('themecolor', PARAM_NOTAGS);
        $mform->addElement('text', 'fontcolor', get_string('fontcolor', 'block_eventpage'));
        $mform->setType('fontcolor', PARAM_NOTAGS);
        $mform->addElement('text', 'linkcolor', get_string('linkcolor', 'block_eventpage'));
        $mform->setType('linkcolor', PARAM_NOTAGS);

        // if ($action == 'edit') {

        //     // Load existing event.
        //     $eventpage = block_eventpage_get_page($eventid);
        //     $mform->setDefault('name', $eventpage->name);
        //     $mform->setDefault('description', $eventpage->description);
        //     $mform->setDefault('startdate', $eventpage->startdate);
        //     $mform->setDefault('starttime', $eventpage->starttime);
        //     $mform->setDefault('endtime', $eventpage->endtime);
        //     $mform->setDefault('themecolor', $eventpage->themecolor);
        //     $mform->setDefault('fontcolor', $eventpage->fontcolor);
        //     $mform->setDefault('linkcolor', $eventpage->linkcolor);
        //     $mform->setDefault('latitude', $eventpage->latitude);
        //     $mform->setDefault('longitude', $eventpage->longitude);
        //     $mform->setDefault('distance', $eventpage->distance);
        //     $mform->setDefault('street', $eventpage->street);
        //     $mform->setDefault('city', $eventpage->city);
        //     $mform->setDefault('zipcode', $eventpage->zipcode);
        //     $mform->setDefault('other', $eventpage->other);
        // }

        // if ($action == 'add') {

        //     if (!empty($courseid)) {
        //         $courseinfo = block_eventpage_get_course_info($courseid);
        //         $mform->addElement (
        //            'editor',
        //            'description',
        //            get_string('description', 'block_eventpage')
        //         );
        //     }

        // }

        $this->add_action_buttons();
    }

    function validation($data, $file) {
        $errors = array();

        if (!empty($data['latitude']) && !is_numeric($data['latitude'])) {
            $errors['latitude'] = get_string('mustbenumeric', 'block_eventpage');
        }

        if (!empty($data['longitude']) && !is_numeric($data['longitude'])) {
            $errors['longitude'] = get_string('mustbenumeric', 'block_eventpage');
        }

        return $errors;
    }

}



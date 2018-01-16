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

        $PAGE->requires->js(new moodle_url('/blocks/eventpage/js/jquery.js'), true);
        $PAGE->requires->js(new moodle_url('/blocks/eventpage/js/jquery.timePicker.js'));
        $PAGE->requires->css('/blocks/eventpage/js/timePicker.css');

        $PAGE->requires->js(new moodle_url('/blocks/eventpage/dist/js/bootstrap-colorpicker.js'));
        $PAGE->requires->css('/blocks/eventpage/dist/css/bootstrap-colorpicker.css');

        $PAGE->requires->js(new moodle_url('/blocks/eventpage/js/locallib.js'));

        $mform    = & $this->_form;
        $courseid = optional_param('courseid', 0, PARAM_INT);
        $action   = optional_param('action', '', PARAM_NOTAGS);
        $eventid  = optional_param('id', 0, PARAM_INT);

        // If editing, lets hide submit the id hidden.
        $mform->addElement('hidden', 'eventid', $eventid);
        $mform->setType('eventid', PARAM_INT);

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

        $mform->addElement('filemanager', 'logopath', get_string('logo', 'block_eventpage'), null,
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

        if ($action == 'edit') {

            // Load existing event.
            $eventpage = block_eventpage_get_page($eventid);
            $mform->setDefault('name', $eventpage->name);
            $mform->setDefault('description', $eventpage->description);
            $mform->setDefault('startdate', $eventpage->startdate);
            $mform->setDefault('starttime', $eventpage->starttime);
            $mform->setDefault('endtime', $eventpage->endtime);
            $mform->setDefault('themecolor', $eventpage->themecolor);
            $mform->setDefault('fontcolor', $eventpage->fontcolor);
            $mform->setDefault('linkcolor', $eventpage->linkcolor);
            $mform->setDefault('latitude', $eventpage->latitude);
            $mform->setDefault('longitude', $eventpage->longitude);
            $mform->setDefault('distance', $eventpage->distance);
            $mform->setDefault('street', $eventpage->street);
            $mform->setDefault('city', $eventpage->city);
            $mform->setDefault('zipcode', $eventpage->zipcode);
            $mform->setDefault('other', $eventpage->other);
        }

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

    function block_eventpage_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
        global $DB;
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return false;
        }
        require_login();
        if ($filearea != 'logopath') {
            return false;
        }
        $itemid = (int)array_shift($args);
        if ($itemid != 0) {
            return false;
        }
        $fs = get_file_storage();
        $filename = array_pop($args);
        if (empty($args)) {
            $filepath = '/';
        } else {
            $filepath = '/'.implode('/', $args).'/';
        }
        $file = $fs->get_file($context->id, 'block_eventpage', $filearea, $itemid, $filepath, $filename);
        if (!$file) {
            return false;
        }
        // finally send the file
        send_stored_file($file, 0, 0, true, $options); // download MUST be forced - security!
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

    // function get_data(){
    //     global $DB;

    //     $data = parent::get_data();

    //     if (!empty($data)) {
    //         $mform =& $this->_form;

    //         // Add the description properly to the $data object.
    //         if(!empty($mform->_submitValues['description'])) {
    //             // $data->description = $mform->_submitValues['description'];
    //         }

    //     }

    //     return $data;
    // }
}


// $namegroup = array();
// $namegroup[] =& $mform->createElement('text', 'name', '');
// $namegroup[] =& $mform->createElement('checkbox', 'name_enabled', '', get_string('disable'));
// $mform->addGroup($namegroup, 'namegroup', get_string('name', 'block_eventpage'), ' ', false);
// $mform->disabledIf('namegroup', 'starttime_enabled', 'checked');

// $starttimegroup = array();
// $starttimegroup[] =& $mform->createElement('text', 'starttime', '', array('placeholder' => 'Example: 5:00'));
// $starttimegroup[] =& $mform->createElement('checkbox', 'starttime_enabled', '', get_string('disable'));
// $mform->addGroup($starttimegroup, 'starttimegroup', get_string('starttime', 'block_eventpage'), ' ', false);
// $mform->disabledIf('starttimegroup', 'starttime_enabled', 'checked');

// $endtimegroup = array();
// $endtimegroup[] =& $mform->createElement('text', 'endtime', '', array('placeholder' => 'Example: 18:00'));
// $endtimegroup[] =& $mform->createElement('checkbox', 'endtime_enabled', '', get_string('disable'));
// $mform->addGroup($endtimegroup, 'endtimegroup', get_string('endtime', 'block_eventpage'), ' ', false);
// $mform->disabledIf('endtimegroup', 'endtime_enabled', 'checked');

// // $mform->addElement('header', 'appereance', get_string('appereance', 'block_eventpage'));

// $themecolorgroup = array();
// $themecolorgroup[] =& $mform->createElement('text', 'themecolor', get_string('themecolor', 'block_eventpage'));
// $themecolorgroup[] =& $mform->createElement('checkbox', 'themecolor_enabled', '', get_string('disable'));
// $mform->addGroup($themecolorgroup, 'themecolorgroup', get_string('themecolor', 'block_eventpage'), ' ', false);
// $mform->disabledIf('themecolorgroup', 'themecolor_enabled', 'checked');

// $fontcolorgroup = array();
// $fontcolorgroup[] =& $mform->createElement('text', 'fontcolor', '');
// $fontcolorgroup[] =& $mform->createElement('checkbox', 'fontcolor_enabled', '', get_string('disable'));
// $mform->addGroup($fontcolorgroup, 'fontcolorgroup', get_string('fontcolor', 'block_eventpage'), ' ', false);
// $mform->disabledIf('fontcolorgroup', 'fontcolor_enabled', 'checked');

// $linkcolorgroup = array();
// $linkcolorgroup[] =& $mform->createElement('text', 'linkcolor', '');
// $linkcolorgroup[] =& $mform->createElement('checkbox', 'linkcolor_enabled', '', get_string('disable'));
// $mform->addGroup($linkcolorgroup, 'linkcolorgroup', get_string('linkcolor', 'block_eventpage'), ' ', false);
// $mform->disabledIf('linkcolorgroup', 'linkcolor_enabled', 'checked');

// // $mform->addElement('header', 'googlemap', get_string('googlemap', 'block_eventpage'));

// $latitudegroup = array();
// $latitudegroup[] =& $mform->createElement('text', 'latitude', '');
// $latitudegroup[] =& $mform->createElement('checkbox', 'latitude_enabled', '', get_string('disable'));
// $mform->addGroup($latitudegroup, 'latitudegroup', get_string('latitude', 'block_eventpage'), ' ', false);
// $mform->disabledIf('latitudegroup', 'latitude_enabled', 'checked');

// $longitudegroup = array();
// $longitudegroup[] =& $mform->createElement('text', 'longitude', '');
// $longitudegroup[] =& $mform->createElement('checkbox', 'longitude_enabled', '', get_string('disable'));
// $mform->addGroup($longitudegroup, 'longitudegroup', get_string('longitude', 'block_eventpage'), ' ', false);
// $mform->disabledIf('longitudegroup', 'longitude_enabled', 'checked');

// $zoomgroup = array();
// $zoomgroup[] =& $mform->createElement('text', 'zoom', '');
// $zoomgroup[] =& $mform->createElement('checkbox', 'zoom_enabled', '', get_string('disable'));
// $mform->addGroup($zoomgroup, 'zoomgroup', get_string('zoom', 'block_eventpage'), ' ', false);
// $mform->disabledIf('zoomgroup', 'zoom_enabled', 'checked');

// // $mform->addElement('header', 'location', get_string('location', 'block_eventpage'));

// $streetgroup = array();
// $streetgroup[] =& $mform->createElement('text', 'street', '');
// $streetgroup[] =& $mform->createElement('checkbox', 'street_enabled', '', get_string('disable'));
// $mform->addGroup($streetgroup, 'streetgroup', get_string('street', 'block_eventpage'), ' ', false);
// $mform->disabledIf('streetgroup', 'street_enabled', 'checked');

// $citygroup = array();
// $citygroup[] =& $mform->createElement('text', 'city', '');
// $citygroup[] =& $mform->createElement('checkbox', 'city_enabled', '', get_string('disable'));
// $mform->addGroup($citygroup, 'citygroup', get_string('city', 'block_eventpage'), ' ', false);
// $mform->disabledIf('citygroup', 'city_enabled', 'checked');

// $zipcodegroup = array();
// $zipcodegroup[] =& $mform->createElement('text', 'zipcode', '');
// $zipcodegroup[] =& $mform->createElement('checkbox', 'zipcode_enabled', '', get_string('disable'));
// $mform->addGroup($zipcodegroup, 'zipcodegroup', get_string('zipcode', 'block_eventpage'), ' ', false);
// $mform->disabledIf('zipcodegroup', 'zipcode_enabled', 'checked');

// $othergroup = array();
// $othergroup[] =& $mform->createElement('text', 'other', '');
// $othergroup[] =& $mform->createElement('checkbox', 'other_enabled', '', get_string('disable'));
// $mform->addGroup($othergroup, 'othergroup', get_string('other', 'block_eventpage'), ' ', false);
// $mform->disabledIf('othergroup', 'other_enabled', 'checked');

// // $mform->addElement('filemanager', 'logopath_filemanager', get_string('logo', 'block_eventpage'));

// $mform->addElement('filemanager', 'logopath', get_string('logo', 'block_eventpage'), null,
//             array('subdirs' => 0, 'maxbytes' => 10485760, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
//                   'accepted_types' => array('.jpg', '.png')));

// $mform->addElement('editor', 'description',  get_string('description', 'block_eventpage'), 'wrap=virtual rows=10');
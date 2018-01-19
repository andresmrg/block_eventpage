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
 * This is a one-line short description of the file.
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    block_eventpage
 * @author     Andres Ramos
 * @copyright  2017 LMS Doctor
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function block_eventpage_get_course_info($courseid) {
    global $DB;
    return $DB->get_record('course', array('id' => $courseid));
}

function block_eventpage_get_page($eventid) {
    global $DB;
    return $DB->get_record('block_eventpage', array('id' => $eventid));
}

function block_eventpage_save_record($from) {
    global $DB, $USER;

    $data                   = new stdClass;
    $data->name             = $from->name;
    $data->description      = $from->description;
    $data->courseid         = $from->courseid;
    $data->startdate        = $from->startdate;
    $data->starttime        = $from->starttime;
    $data->endtime          = $from->endtime;
    $data->creatorid        = $USER->id;

    $data->themecolor       = $from->themecolor;
    $data->fontcolor        = $from->fontcolor;
    $data->linkcolor        = $from->linkcolor;
    $data->latitude         = $from->latitude;
    $data->longitude        = $from->longitude;
    $data->distance         = $from->distance;

    $data->street           = $from->street;
    $data->city             = $from->city;
    $data->zipcode          = $from->zipcode;
    $data->other            = $from->other;

    // $data->name             = 'Title';
    // $data->description      = 'Description here';
    // $data->courseid         = 2;
    // $data->startdate        = time();
    // $data->starttime        = '8:00';
    // $data->endtime          = '16:00';
    // $data->logopath         = '/path/to/file';
    // $data->creatorid        = $USER->id;

    // $data->themecolor       = '#CCC';
    // $data->fontcolor        = '#CCC';
    // $data->linkcolor        = '#CCC';
    // $data->latitude         = 0;
    // $data->longitude        = 0;
    // $data->distance         = 0;

    // $data->street           = 'Cra 19 #94-10';
    // $data->city             = 'Miami';
    // $data->zipcode          = '33230';
    // $data->other            = 'Building Luxury';

    $data->timecreated      = time();
    $data->timemodified     = 0;

    return $DB->insert_record('block_eventpage', $data);

}

function block_eventpage_update_record($from) {
    global $DB;
    return $DB->update_record('block_eventpage', $from);
}

function block_eventpage_delete_record($from) {
    global $DB;
    return $DB->delete_records('block_eventpage', array('id' => $from->id));
}


function block_eventpage_get_user_from_role($roleid, $contextid) {
    global $DB;

    $users = $DB->get_records(
        'role_assignments',
        array('roleid' => $roleid, 'contextid' => $contextid), 'timemodified', 'userid'
    );

    if (empty($users)) {
        return;
    }

    $userlist = array();
    foreach ($users as $user) {
        $userlist[] = $DB->get_record('user', array('id' => $user->userid), '*');
    }

    return $userlist;
}

function block_eventpage_get_role_name_list($list) {

    if (empty($list)) {
        return;
    }

    $temp = array();
    foreach ($list as $user) {
        $temp[] = fullname($user);
    }

    return implode(', ', $temp);
}

function block_eventpage_get_single_user($list) {

    if (empty($list)) {
        return;
    }

    return $list[0];
}


function block_eventpage_get_bio($roleid) {
    global $DB;
    $users = $DB->get_records('role_assignments', array('roleid' => $roleid), 'timemodified', 'userid');

    if (empty($users)) {
        return;
    }

    $userlist = array();
    foreach ($users as $user) {
        $userinfo = $DB->get_record('user', array('id' => $user->userid), '*');
        $userlist[] = fullname($userinfo);
    }

    $strusers = implode(', ', $userlist);
    return $strusers;
}

function block_eventpage_get_contextid($courseid, $level) {
    global $DB;
    return $DB->get_field('context', 'id', array('contextlevel' => $level, 'instanceid' => $courseid));
}

function block_eventpage_get_course_category($courseid, $linkcolor) {
    global $DB;

    $categoryid = $DB->get_field('course', 'category', array('id' => $courseid));
    $parentcategory = $DB->get_field('course_categories', 'parent', array('id' => $categoryid));

    $result = '';
    if (!empty($parentcategory)) {
        $url = new moodle_url('/course/index.php', array('categoryid' => $parentcategory));
        $result = html_writer::link($url, '<center>' . get_string('explorearea', 'block_eventpage') . '</center>', $linkcolor);
    }

    return $result;

}




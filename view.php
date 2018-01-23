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
require_once($CFG->dirroot . '/lib/filelib.php');
require_once('locallib.php');

global $DB, $CFG, $PAGE;

$CFG->additionalhtmlhead .= '
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
    .card {
        border: 0px !important;
        background-color: transparent;
    }
    .navbar-light {
        background-color: transparent;
        border-bottom: 0px !important;
    }
    #page-wrapper::after {
        min-height: 0px;
    }
    .nav-item {
        width: auto;
        position: absolute;
        right: 0;
        border: 1px solid #F4F4F4;
        padding: 10px 20px;
    }

    #page-header { display: none }
    #page-footer { display:none }
    .navbar-nav { display:none }
    #page { margin-top: 0px !important;
    #page.container-fluid { padding: 0px !important; }

</style>';

$PAGE->set_pagelayout('base');
$PAGE->requires->js(new moodle_url('/blocks/eventpage/js/jquery.min.js'));
$PAGE->requires->js(new moodle_url('/blocks/eventpage/js/locallib.js'));

$eventid = optional_param('id', 0, PARAM_INT);
$e       = block_eventpage_get_page($eventid);

$PAGE->set_url('/blocks/eventpage/view.php?id=' . $e->id);


$context   = context_course::instance($e->courseid);
$PAGE->set_context($context);
$contextid = block_eventpage_get_contextid($e->courseid, $context->contextlevel);
$PAGE->set_title($e->name);

// Get moderators and speakers users.
$moderatorid     = $DB->get_field('role', 'id', array('shortname' => 'moderator'));
$moderatorarray  = block_eventpage_get_user_from_role($moderatorid, $contextid);
$moderators      = block_eventpage_get_role_name_list($moderatorarray);

$speakerid       = $DB->get_field('role', 'id', array('shortname' => 'speaker'));
$speakerarray    = block_eventpage_get_user_from_role($speakerid, $contextid);
$speakers        = block_eventpage_get_role_name_list($speakerarray);

// Get main moderators and speakers
$mainmoderatorid     = $DB->get_field('role', 'id', array('shortname' => 'mainmoderator'));
$mainmoderatorarray  = block_eventpage_get_user_from_role($mainmoderatorid, $contextid);
$mainmoderator       = block_eventpage_get_single_user($mainmoderatorarray);

$mainspeakerid       = $DB->get_field('role', 'id', array('shortname' => 'mainspeaker'));
$mainspeakerarray    = block_eventpage_get_user_from_role($mainspeakerid, $contextid);
$mainspeaker         = block_eventpage_get_single_user($mainspeakerarray);

$emptybio         = (empty($mainspeaker) && empty($mainmoderator)) ? true : false;
$emptymap         = (empty($e->latitude) && empty($e->longitude)) ? true : false;

// Get course category.
$linkcolor = (isset($e->linkcolor)) ? array('style' => 'color:' .$e->linkcolor.';') : array();
$categorylink = block_eventpage_get_course_category($e->courseid, $linkcolor);

// Attributes of the main container.
$containerattr = array(
    'class' => 'container',
    'style' => "width: 1024px; margin:auto; border: 1px solid #CCC; padding: 30px; font-size: 18px; border-radius: 15px;"
);

// Style container according to the settings.
if (isset($e->themecolor)) {
    $containerattr['style'] .= "background: $e->themecolor;";
}

// Style font of the event page.
if (isset($e->fontcolor)) {
    $containerattr['style'] .= "color: $e->fontcolor";
}

$fs = get_file_storage();
if ($files = $fs->get_area_files($context->id, 'block_eventpage', 'intro', 0, null, false)) {

    // Look through each file being managed
    foreach ($files as $file) {
        $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
        $logo = html_writer::empty_tag('img', array('src' => $url, 'style' => 'height: 120px'));
    }

}

echo $OUTPUT->header();
echo html_writer::start_tag('div', $containerattr);
    // Header.
    echo html_writer::start_tag('header');

        // Logo and language.
        echo html_writer::start_tag('div', array('class' => 'row'));

            // Logo side.
            echo html_writer::start_tag('div', array('class' => 'col-sm-6'));
                echo html_writer::tag('div', $logo, array('style' => ''));
            echo html_writer::end_tag('div');

            // Language side.
            echo html_writer::start_tag('div', array('class' => 'col-sm-6', 'id' => 'languageselector'));
                echo html_writer::tag('div', '', array('style' => ''));
            echo html_writer::end_tag('div');

        echo html_writer::end_tag('div');

        echo html_writer::start_tag('div');
            echo html_writer::tag('h1', $e->name, array('style' => 'text-align: center; padding: 20px;'));
        echo html_writer::end_tag('div');

    echo html_writer::end_tag('header');

    echo html_writer::tag('br','');

    // Date time and moderator list.
    echo html_writer::start_tag('div', array('class' => 'row'));

        // Left side.
        echo html_writer::start_tag('div', array('class' => 'col-sm-7'));
            echo html_writer::tag('div', 'Date: ' . date('d F Y', $e->startdate), array('style' => ''));

            if (!empty($moderators)) {
                echo html_writer::tag('div', get_string('moderator', 'block_eventpage') . ': ' . $moderators, array('style' => ''));
            }

            if (!empty($speakers)) {
                echo html_writer::tag('div', get_string('speaker', 'block_eventpage') . ': ' . $speakers, array('style' => ''));
            }

        echo html_writer::end_tag('div');


        // Right side.
        echo html_writer::start_tag('div', array('class' => 'col-sm-5'));

            echo html_writer::tag('div', get_string('time', 'block_eventpage') . ': ' . $e->starttime . ' - ' . $e->endtime, array('style' => ''));
            echo html_writer::tag('br','');
            echo html_writer::tag('br','');
        echo html_writer::end_tag('div');

    echo html_writer::end_tag('div');


    // Location and bio.
    echo html_writer::start_tag('div', array('class' => 'row'));

        // If the map is not empty, then display the map and the main speaker and moderator.
        if (!$emptymap) {
            echo html_writer::start_tag('div', array('class' => 'col-sm-7'));
                echo html_writer::tag('div', get_string('location', 'block_eventpage') . ": {$e->city}, {$e->street}, {$e->other}", array('style' => ''));

                // Map.
                if (!$emptybio) {
                    $mapstyle = "width:500px; height:320px";
                } else {
                    $mapstyle = "width:960px; height:320px";
                }
                echo html_writer::tag('div', '', array('id' => 'map', 'style' => $mapstyle));

                echo '<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCU6NUmBX-LrGMxZQro0J8RcU4Sl4w7D84&callback=initMap"></script>';
                echo html_writer::tag(
                    'script',
                    '
                    $(\'#page\').css(\'margin-top\', \'0px\');
                    function initMap() {
                        var uluru = {lat: ' . $e->latitude . ', lng: ' . $e->longitude . '};
                        var map = new google.maps.Map(document.getElementById(\'map\'), {
                            zoom: ' . $e->distance . ',
                            center: uluru
                        });
                        var marker = new google.maps.Marker({
                            position: uluru,
                            map: map
                        });
                    }'
                );
            echo html_writer::end_tag('div');

              // Right side.
            echo html_writer::start_tag('div', array('class' => 'col-sm-5'));

                if (!empty($mainmoderator)) {
                    // Moderators Bio.
                    echo html_writer::start_tag('div', array('class' => 'row'));
                        echo html_writer::start_tag('div', array('class' => 'col-sm-4'));
                            echo $OUTPUT->user_picture($mainmoderator, array('size' => 120));
                        echo html_writer::end_tag('div');

                        echo html_writer::start_tag('div', array('class' => 'col-sm-8'));
                            echo html_writer::tag('div', fullname($mainmoderator), array('style' => ''));
                            echo html_writer::tag('small', $mainmoderator->description, array('style' => ''));
                        echo html_writer::end_tag('div');
                    echo html_writer::end_tag('div');
                }
                // Break space.
                echo html_writer::tag('br','');

                if (!empty($mainspeaker)) {
                    // Moderators Bio.
                    echo html_writer::start_tag('div', array('class' => 'row'));
                        echo html_writer::start_tag('div', array('class' => 'col-sm-4'));
                            echo $OUTPUT->user_picture($mainspeaker, array('size' => 120));
                        echo html_writer::end_tag('div');

                        echo html_writer::start_tag('div', array('class' => 'col-sm-8'));
                            echo html_writer::tag('div', fullname($mainspeaker), array('style' => ''));
                            echo html_writer::tag('small', $mainspeaker->description, array('style' => ''));
                        echo html_writer::end_tag('div');
                    echo html_writer::end_tag('div');
                }

            echo html_writer::end_tag('div');

        } else {

            // ... Display in the whole page main speaker and moderator.
            if (!empty($mainmoderator)) {
                echo html_writer::start_tag('div', array('class' => 'col-sm-6'));
                    // Moderators Bio.
                    echo html_writer::start_tag('div', array('class' => 'row'));
                        echo html_writer::start_tag('div', array('class' => 'col-sm-4'));
                            echo $OUTPUT->user_picture($mainmoderator, array('size' => 120));
                        echo html_writer::end_tag('div');

                        echo html_writer::start_tag('div', array('class' => 'col-sm-8'));
                            echo html_writer::tag('div', fullname($mainmoderator), array('style' => ''));
                            echo html_writer::tag('small', $mainmoderator->description, array('style' => ''));
                        echo html_writer::end_tag('div');
                    echo html_writer::end_tag('div');
                echo html_writer::end_tag('div');
            }

            if (!empty($mainspeaker)) {
                echo html_writer::start_tag('div', array('class' => 'col-sm-6'));
                    // Moderators Bio.
                    echo html_writer::start_tag('div', array('class' => 'row'));
                        echo html_writer::start_tag('div', array('class' => 'col-sm-4'));
                            echo $OUTPUT->user_picture($mainspeaker, array('size' => 120));
                        echo html_writer::end_tag('div');

                        echo html_writer::start_tag('div', array('class' => 'col-sm-8'));
                            echo html_writer::tag('div', fullname($mainspeaker), array('style' => ''));
                            echo html_writer::tag('small', $mainspeaker->description, array('style' => ''));
                        echo html_writer::end_tag('div');
                    echo html_writer::end_tag('div');
                echo html_writer::end_tag('div');
            }

        }

    echo html_writer::end_tag('div');

    // Break space.
    echo html_writer::tag('br','');

    // Access to course and description.
    echo html_writer::start_tag('div', array('class' => 'row'));
        echo html_writer::start_tag('div', array('class' => 'col-sm-12'));
            echo html_writer::link(
                new moodle_url('/course/view.php', array('id' => $e->courseid)),
                '<center>' . get_string('registerprogram', 'block_eventpage') . '</center>', $linkcolor
            );
            echo html_writer::tag('br','');
            echo html_writer::tag('div', $e->description, array('style' => 'text-align: justify;'));
            echo $categorylink;

        echo html_writer::end_tag('div');
    echo html_writer::end_tag('div');
echo html_writer::end_tag('div');
echo $OUTPUT->footer();
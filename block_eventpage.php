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
 * eventpage block caps.
 *
 * @package    block_eventpage
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_eventpage extends block_list {

    function init() {
        $this->title = get_string('pluginname', 'block_eventpage');
    }

    function get_content() {
        global $CFG, $OUTPUT, $DB;

        $courseid = optional_param('id', 0, PARAM_INT);

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        // Validate is already a course event page.
        // $exist = block_eventpage_pageexist($courseid);

        if (!$DB->record_exists('block_eventpage', array('courseid' => $courseid))) {
            $params = array('action' => 'add', 'courseid' => $courseid);
            $showlink = get_string('add', 'block_eventpage');
        } else {
            $pageid = $DB->get_field('block_eventpage', 'id', array('courseid' => $courseid), IGNORE_MULTIPLE);
            $params = array('action' => 'edit', 'id' => $pageid);
            $showlink = get_string('edit', 'block_eventpage');

            // View event link.
            $viewurl = new moodle_url('/blocks/eventpage/view.php', array('id' => $pageid));
            $this->content->items[] = html_writer::link($viewurl, get_string('view', 'block_eventpage'));
        }

        $processurl = new moodle_url('/blocks/eventpage/process.php', $params);

        $this->content->items[] = html_writer::link($processurl, $showlink);
        $this->content->items[] = html_writer::link(new moodle_url('/blocks/eventpage/list.php'), get_string('seeall', 'block_eventpage'));

        // user/index.php expect course context, so get one if page has module context.
        // $currentcontext = $this->page->context->get_course_context(false);

        // if (!empty($this->config->text)) {
        //     $this->content->text = $this->config->text;
        // }

        // $this->content = '';
        // if (empty($currentcontext)) {
        //     return $this->content;
        // }
        // if ($this->page->course->id == SITEID) {
        //     $this->content->text .= "site context";
        // }

        // if (!empty($this->config->text)) {
        //     $this->content->text .= $this->config->text;
        // }

        return $this->content;
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('all' => false,
                     'site' => true,
                     'site-index' => true,
                     'course-view' => true,
                     'course-view-social' => false,
                     'mod' => true,
                     'mod-quiz' => false);
    }

    public function instance_allow_multiple() {
          return true;
    }

    function has_config() {return true;}

    public function cron() {
            mtrace( "Hey, my cron script is running" );

                 // do something

                      return true;
    }
}

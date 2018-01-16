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
 * Table class to display selfstudy courses and allow users to request courses.
 *
 * @package     block_eventpage
 * @copyright   2015 Andres Ramos
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class list_table extends table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array(
            'name',
            'startdate',
            'starttime',
            'endtime',
            'address'
        );
        // Define the titles of columns to show in header.
        $headers = array(
            get_string('name', 'block_eventpage'),
            get_string('startdate', 'block_eventpage'),
            get_string('starttime', 'block_eventpage'),
            get_string('endtime', 'block_eventpage'),
            get_string('address', 'block_eventpage')
        );

        if (!$this->is_downloading()) {
            $columns[] = 'actions';
            $headers[] = 'Action';
        }

        global $DB;

        $this->sortable(true, 'startdate', SORT_ASC);
        $this->collapsible(false);
        $this->no_sorting('actions');

        $this->define_columns($columns);
        $this->define_headers($headers);

    }

    /**
     * This function is called for each data row to allow processing of the
     * username value.
     *
     * @param object $value Contains object with all the values of record.
     * @return $string Return username with link to profile or username only
     *     when downloading.
     */
    public function col_startdate($value) {
        // If the data is being downloaded than we don't want to show HTML.
        return date('F d Y', $value->startdate);
    }

    /**
     * Generate the display of the address 2.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_address($value) {
        global $DB;

        return "{$value->street} - {$value->city}, {$value->other}";
    }

    /**
     * Generate the display of the action links.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_actions($values) {
        if (!$this->is_downloading()) {

            $str = "";
            $editurl = new moodle_url('/blocks/eventpage/process.php', array('action' => 'edit', 'id' => $values->id));
            $str .= html_writer::link($editurl, "Edit", array('class' => 'btn btn-primary'));

            $str .= " ";

            // $deleteurl = new moodle_url('/blocks/eventpage/process.php', array('action' => 'del', 'id' => $values->id));
            $deleteurl = new moodle_url('/blocks/eventpage/process.php', array('action' => 'del', 'id' => $values->id));
            $str .= html_writer::link($deleteurl, "Delete", array('class' => 'btn btn-default deleteEventPage', 'onclick' => 'return check_confirm()'));

            return $str;
        }
    }
}
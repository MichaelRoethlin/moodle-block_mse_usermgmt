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
 * Present Moodle forms to confirm and transmit print configuration data
 *
 * @package    block
 * @subpackage bfh_printservice
 * @copyright  BFH-TI, Michael Röthlin michael.roethlin@bfh.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
global $CFG;
require_once($CFG->libdir . '/formslib.php');

class inscription_form extends moodleform {

    public function definition() {
        global $USER, $COURSE, $DB;

        $mform = &$this->_form;

        $mform->addElement('hidden', 'userid', $USER->id);
        $mform->setType('userid', PARAM_INT);

        $mform->addElement('hidden', 'courseid', $COURSE->id);
        $mform->setType('courseid', PARAM_INT);

        $modules_preselected = $this->_customdata['modules_preselected'];

        $debug = false;
        if ($debug) {
            echo "<pre>";
        }

        if (count($modules_preselected) > 0) {

            $file_table = '<table class="generaltable">';
            $file_table .= '<thead><tr>';

            $file_table .= '<th>' . 'Name' . '</th>';
            $file_table .= '<th>' . 'Seats' . '</th>';
            $file_table .= '<th>' . 'Status' . '</th>';

            $file_table .= '</tr></thead>';
            $file_table .= '<tbody>';

            // For each course with files in cart
            foreach ($modules_preselected as $module) {
                $file_table .= '<tr>';
                $file_table .= '<td>' . $module->modulecode . '</td>';
                $file_table .= '<td>' . 123 . '</td>';
                $moduletag = 'm-' . $module->modulecode;
                $file_table .= '<td>' . '<button type="submit" id="' . $moduletag . '"  >Inscribe</button>' . '</td>';
                // $mform->addElement('hidden', $moduletag);
                // $mform->setType('courseid', PARAM_TEXT);
                $file_table .= '</tr>';
            }

            $file_table .= '</tbody>';
            $file_table .= '</table>';
            $mform->addElement('html', $file_table);

            $mform->addElement('html', "<p>" . 'Period: closed - no changes possible' . "</p>");

            /*
            $mform->addElement('header', 'warnings', 'warnings');

            $mform->addElement('html', "<ul>");
            $mform->addElement('html', "<br />");
            $mform->addElement('html', '<li>' . 'abc' . '</li>');
            $mform->addElement('html', "<br />");
            $mform->addElement('html', '<li>' . 'abc' . '</li>');
            $mform->addElement('html', "</ul>");
*/
            $buttonarray = array();
            $buttonarray[] = &$mform->createElement('submit', 'submitbutton', 'Submit');
            $buttonarray[] = &$mform->createElement('cancel');
            $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
            $mform->closeHeaderBefore('buttonar');
        } else {
            $mform->addElement('html', '<p>Ihr Warenkorb ist leer!</p>');
        }
    }
}


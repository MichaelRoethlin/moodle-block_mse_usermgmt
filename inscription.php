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
 * Moodle function for confirming and sending print data
 *
 * @package    block
 * @subpackage mse_usermgmt
 * @copyright  BFH-TI, Michael Röthlin michael.roethlin@bfh.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
// require_once('lib.php');
require_once('inscription_form.php');

global $PAGE, $DB, $USER, $OUTPUT;

require_login();

$userid = required_param('userid', PARAM_TEXT);
$courseid = required_param('courseid', PARAM_INT);

$course = $DB->get_record('course', array('id' => $courseid));

if ($userid !== $USER->id) {
    print_error('no_permission', 'block_mse_usermgmt', '', $userid);
}

$context = get_context_instance(CONTEXT_COURSE, $courseid);

if (!$course) {
    print_error('no_course', 'block_mse_usermgmt', '', $courseid);
}

$has_permission = true;

if (!$has_permission) {
    print_error('no_permission', 'block_mse_usermgmt');
}

$blockname = get_string('pluginname', 'block_mse_usermgmt');
$header = 'Header';

$PAGE->set_pagelayout('incourse');
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->navbar->add($blockname);
$PAGE->navbar->add($header);
$PAGE->set_title($blockname . ': ' . $header);
$PAGE->set_heading($blockname . ': ' . $header);
$PAGE->set_url('/course/view.php', array('courseid' => $courseid));

// Print header
$PAGE->set_pagelayout('standard');

// Set_heading, otherwise Logo section breaks
// See what the logged-in user has already selected
$modules_preselected = $DB->get_records('block_mse_usermgmt_modules');
// ... otherwise stay in same windows

$form = new inscription_form(
    null, // $action
    array('modules_preselected' => $modules_preselected), // $customdata
    'post', //$method
    '', // $target, should be '_blank' in case of new window
    array('autocomplete' => 'off'), // $attributes
    true // $editable
);

$warnings = array();
$select = [];
$redirurl = null;

if ($form->is_cancelled()) {
    mtrace("mse_classe form cancelled");

    // Jump back to course
    redirect(new moodle_url('/course/view.php?id=' . $courseid));
} else {
    $data = $form->get_data();

    if ($data) {
        echo '<pre>';
        print_r($data);
        die();
        mtrace("mse_classe form received_data");

        if (isset($data->submitbutton) && ($data->submitbutton == 'goto_shop')) {

            add_to_log($course->id, 'mse_usermgmt', 'process', "inscription.php?userid=$USER->id&courseid=$course->id",
                "Start FTP transmission");
        }
        $select = $data;
    } else {
        // Form with no data: normal case
    }
}

$form->set_data($select);

if (empty($warnings)) {
    if (isset($select->send)) {
        redirect(new moodle_url('/blocks/mse_usermgmt/block_mse_usermgmt.php', array('courseid' => $course->id)));
    } else if (isset($select->draft)) {
        $warnings['success'] = get_string("changessaved");
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading($blockname);

foreach ($warnings as $type => $warning) {
    $class = ($type === 'success') ? 'notifysuccess' : 'notifyproblem';
    echo $OUTPUT->notification($warning, $class);
}

// echo html_writer::start_tag('div', array('class' => 'no-overflow'));

if (!$redirurl) {
    $form->display();
} else {
    redirect(new moodle_url('/course/view.php', array('id' => $courseid)), 'OK', 5);
}

//echo html_writer::end_tag('div');

echo $OUTPUT->footer();

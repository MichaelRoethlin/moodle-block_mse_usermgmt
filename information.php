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
 * Lists all the users within a given course.
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package core_user
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/notes/lib.php');
require_once($CFG->libdir . '/tablelib.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot . '/enrol/locallib.php');

define('DEFAULT_PAGE_SIZE', 20);
define('SHOW_ALL_PAGE_SIZE', 5000);

$page = optional_param('page', 0, PARAM_INT); // Which page to show.
$perpage = optional_param('perpage', DEFAULT_PAGE_SIZE, PARAM_INT); // How many per page.
$contextid = optional_param('contextid', 0, PARAM_INT); // One of this or.
$courseid = optional_param('id', 0, PARAM_INT); // This are required.
$newcourse = optional_param('newcourse', false, PARAM_BOOL);
$selectall = optional_param('selectall', false, PARAM_BOOL); // When rendering checkboxes against users mark them all checked.
$roleid = optional_param('roleid', 0, PARAM_INT);
$groupparam = optional_param('group', 0, PARAM_INT);

$PAGE->set_url('/block/mse_usermgmt/information.php', array(
    'page' => $page,
    'perpage' => $perpage,
    'contextid' => $contextid,
    'id' => $courseid,
    'newcourse' => $newcourse));

if ($contextid) {
    $context = context::instance_by_id($contextid, MUST_EXIST);
    if ($context->contextlevel !== CONTEXT_COURSE) {
        print_error('invalidcontext');
    }
    $course = $DB->get_record('course', array('id' => $context->instanceid), '*', MUST_EXIST);
} else {
    $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
    $context = context_course::instance($course->id, MUST_EXIST);
}

// Not needed anymore.
unset($contextid);
unset($courseid);

require_login($course);

$systemcontext = context_system::instance();
$isfrontpage = ($course->id === SITEID);

$frontpagectx = context_course::instance(SITEID);

if ($isfrontpage) {
    $PAGE->set_pagelayout('admin');
    course_require_view_participants($systemcontext);
    echo $OUTPUT->notification(get_string('notingroup'));
    echo $OUTPUT->footer();
    exit;
} else {
    $PAGE->set_pagelayout('incourse');
    course_require_view_participants($context);
}

// Trigger events.
user_list_view($course, $context);

$bulkoperations = has_capability('moodle/course:bulkmessaging', $context);

$PAGE->set_title("$course->shortname: " . get_string('participants'));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagetype('course-view-' . $course->format);
$PAGE->add_body_class('path-user');                     // So we can style it independently.

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('participants'));

echo html_writer::start_tag('div', array('class' => 'btn-group'));

for ($i = 0; $i<9;$i++) {
    echo html_writer::start_tag('div', array('class' => '123'));
    echo html_writer::tag('p', 'Text is '.$i);
    echo html_writer::end_tag('div', array('class' => '123'));

    //    echo html_writer::empty_tag('input', array('type' => 'button', 'id' => 'checkall', 'class' => 'btn btn-secondary',
//    'value' => $i, 'data-showallink' => '123'));
}


//echo $OUTPUT->notification(get_string('notingroup'));
echo $OUTPUT->footer();

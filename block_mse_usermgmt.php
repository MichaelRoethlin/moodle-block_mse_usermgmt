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
     * mse_usermgmt block caps.
     *
     * @package    block_mse_usermgmt
     * @copyright  Daniel Neis <danielneis@gmail.com>
     * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */

    defined('MOODLE_INTERNAL') || die();

    /**
     * @property  title Title of the plugin
     * @property stdClass content
     */
    class block_mse_usermgmt extends block_list {

        /**
         * @var stdClass
         */

        public function init() {
            $this->title = get_string('pluginname', 'block_mse_usermgmt');
        }

        public function get_content() {
            global $USER;

            if ($this->content !== null) {
                return $this->content;
            }

            $this->content = new stdClass;
            $this->content->items = array();
            $this->content->icons = array();
            $this->content->footer = 'Footer here ...';

            $coursename = $this->page->course->fullname;
            $courseid = $this->page->course->id;
            $coursecontext = context_course::instance($courseid);

            $txtelems = explode(' ', $coursename);
            if (count($txtelems) > 0) {
                $modulecode = $txtelems[0];
            } else {
                $modulecode = '';
            }

            $url1 = new moodle_url('/blocks/mse_usermgmt/bookings.php', array('courseid' => $courseid));
            $this->content->items[] = html_writer::tag('a', 'Master Office Students ', array('href' => 'some_file.php'));

            $url2 = new moodle_url('/blocks/mse_usermgmt/confirmation.php', array('id' => $courseid));
            $this->content->items[] = html_writer::link($url2, 'Presence Confirmation');

            $url3 = new moodle_url('/blocks/mse_usermgmt/inscription.php', array('userid' => $USER->id, 'courseid' => $courseid));
            $this->content->items[] = html_writer::link($url3, 'Inscriptions');

            if (has_capability('block/mse_usermgmt:manage', $coursecontext)) {
                $url4 = new moodle_url('/blocks/mse_usermgmt/information.php', array('userid' => $USER->id, 'id' => $courseid));
                $this->content->items[] = html_writer::link($url4, 'Configuration information');
            }

            // $this->content->icons[] = html_writer::empty_tag('img', array('src' => 'images/icons/1.gif', 'class' => 'icon'));

            // Add more list items here

            return $this->content;
        }

        function get_content2() {
            global $CFG, $OUTPUT;

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

            // user/index.php expect course context, so get one if page has module context.
            $currentcontext = $this->page->context->get_course_context(false);

            if (!empty($this->config->text)) {
                $this->content->text = $this->config->text;
            }

            $this->content = '';
            if (empty($currentcontext)) {
                return $this->content;
            }

            $this->content->text = 'Course ID: ' . $this->page->course->fullname;
            return ($this->content->text);

            if ($this->page->course->id === SITEID) {
                $this->content->text .= "site context";
            }

            if (!empty($this->config->text)) {
                $this->content->text .= $this->config->text;
            }

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

        function has_config() {
            return true;
        }

        public function cron() {
            mtrace("mse_classe cron script running");

            // do something

            return true;
        }
    }

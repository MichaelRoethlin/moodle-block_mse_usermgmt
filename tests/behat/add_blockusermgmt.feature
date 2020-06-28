@block @block_mse_usermgmt
Feature: Add a usermagement block
    In order to manage users from my UAS
    As a teacher
    I need to add a usermgmt block to the management course

    @javascript
    Scenario: Add a usermgmt block to a course without questions
        Given the following "users" exist:
            | username | firstname | lastname | email |
            | mo1      | Teacher | 1 | mo@example.com |
            | student1 | Student | 1 | student1@example.com |
        And the following "courses" exist:
            | fullname | shortname | category |
            | Course 1 | C1 | 0 |
        And the following "course enrolments" exist:
            | user | course | role |
            | mo1  | C1 | editingteacher |
            | student1 | C1 | student |
        And I log in as "teacher1"
        And I am on "Course 1" course homepage with editing mode on
        And I add a "Verbal feedback" to section "1" and I fill the form with:
            | Name | Test verbal feedback |
            | Description | Test verbal feedback description |
        And I log out
        And I log in as "student1"
        And I am on "Course 1" course homepage
        And I follow "Test verbal feedback"
        Then I should see "The verbal feedback activity is not yet ready. Please try again later."

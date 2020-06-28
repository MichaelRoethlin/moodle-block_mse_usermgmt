@block @block_mse_usermgmt
Feature: Installation succeeds
    In order to use this plugin
    As a user
    I need the installation to work

    Scenario: Check the Plugins overview for the name of this plugin
        Given I log in as "admin"
        And I navigate to "Plugins > Plugins overview" in site administration
        Then the following should exist in the "plugins-control-panel" table:
            | Plugin name        |
            | block_mse_usermgmt |

    Scenario: Check the value of each default setting
        Given I log in as "admin"
        When I navigate to "Plugins > Local plugins > MSE User Management" in site administration
        Then the field "Enabled" matches value "0"

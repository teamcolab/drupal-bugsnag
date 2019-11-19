# Bugsnag

Integrate Drupal with Bugsnag (http://www.bugsnag.com) to optionally report uncaught exceptions and PHP errors to Bugsnag.

This is a refactor based on the work done at https://drupal.org/project/bugsnag

## Installation

1. Register for an account at http://bugsnag.com.
2. Install this module using Composer. (If it is not installed using Composer, you will need to add the bugsnag/bugsnag:3.0 dependency yourself.)
3. Visit the configuration page at /admin/config/development/bugsnag

## Configuration

1. Configure at Administer > Configuration > Development > Bugsnag
2. Enter API key from Bugsnag dashboard
3. Configure other options. You must choose either error/exception handling or logging (or both) to send messages to Bugsnag.

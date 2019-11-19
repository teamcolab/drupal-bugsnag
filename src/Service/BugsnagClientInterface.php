<?php

namespace Drupal\bugsnag\Service;

/**
 *
 */
interface BugsnagClientInterface {

  /**
   * Get an instance of the Bugsnag client.
   *
   * @return \Bugsnag\Client
   */
  public function getClient();

}

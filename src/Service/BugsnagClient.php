<?php

namespace Drupal\bugsnag\Service;

use Bugsnag\Client;
use Bugsnag\Handler;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 *
 */
class BugsnagClient implements BugsnagClientInterface {

  /**
   * The account for the current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $accountProxy;

  /**
   * A configuration object containing Bugsnag log settings.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * The instantiated Bugsnag client.
   *
   * @var \Bugsnag\Client
   */
  protected $bugsnagClient;

  /**
   * Current request.
   *
   * @var Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * Constructs a BugsnagClient object.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $accountProxy
   *   The account proxy.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory object.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   */
  public function __construct(AccountProxyInterface $accountProxy, ConfigFactoryInterface $config_factory, RequestStack $requestStack) {
    $this->config = $config_factory->get('bugsnag.settings');
    $this->request = $requestStack->getCurrentRequest();
    $this->accountProxy = $accountProxy;
    $this->initializeBugsnag();
  }

  /**
   * Initialize the Bugsnag client.
   *
   * @return void
   */
  protected function initializeBugsnag() {
    $apikey = trim($this->config->get('bugsnag_apikey'));
    if (!empty($apikey)) {

      $user = $this->accountProxy->getAccount();

      $this->bugsnagClient = Client::make($apikey);

      $host = $this->request->getHost();
      if (!empty($host)) {
        $this->bugsnagClient->setHostname($host);
      }

      $release_stage = $this->config->get('release_stage') ?: 'development';
      $this->bugsnagClient->setReleaseStage($release_stage);

      if ($this->config->get('send_user_info') && $user->id()) {
        $this->bugsnagClient->registerCallback(
          function ($report) use ($user) {
              $report->setUser(
                  [
                    'id' => $user->id(),
                    'name' => $user->getAccountName(),
                    'email' => $user->getEmail(),
                  ]
              );
          }
        );
      }

      if ($this->config->get('bugsnag_log_exceptions')) {
        Handler::registerWithPrevious($this->bugsnagClient);
      }
    }
  }

  /**
   * @inheritDoc
   */
  public function getClient() {
    return $this->bugsnagClient;
  }

}

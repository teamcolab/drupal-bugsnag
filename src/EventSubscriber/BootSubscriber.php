<?php

namespace Drupal\bugsnag\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Provides a BootSubscriber.
 */
class BootSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['dummyHandler', 200];
    return $events;
  }

  /**
   * @param Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The Event to process.
   */
  public function dummyHandler(GetResponseEvent $event) {
    // This is a dummy event handler to ensure the Bugsnag client
    // is instantiated.
  }

}

<?php

namespace Drupal\bugsnag\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\RfcLogLevel;

/**
 *
 */
class BugsnagAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bugsnag_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('bugsnag.settings');

    $config
      ->set('bugsnag_apikey', $form_state->getValue('bugsnag_apikey'))
      ->set('release_stage', $form_state->getValue('release_stage'))
      ->set('bugsnag_log_exceptions', $form_state->getValue('bugsnag_log_exceptions'))
      ->set('bugsnag_logger', $form_state->getValue('bugsnag_logger'))
      ->set('send_user_info', $form_state->getValue('send_user_info'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['bugsnag.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bugsnag.settings');

    $form['bugsnag_apikey'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('API key'),
      '#description' => t('Bugsnag API key for the application.'),
      '#default_value' => $config->get('bugsnag_apikey'),
    ];

    $release_stage = $config->get('release_stage');
    $form['release_stage'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#title' => $this->t('Release Stage'),
      '#default_value' => (!empty($release_stage)) ? $release_stage : 'development',
      '#options' => [
        'development' => 'development',
        'production' => 'production',
      ],
    ];

    $form['user_details'] = [
      '#type' => 'fieldgroup',
      '#title' => $this->t('User details'),
    ];

    $form['user_details']['send_user_info'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Send logged in user details.'),
      '#description' => $this->t('The user id, username, and email address of the logged in email will be sent to Bugsnag if this is checked.'),
      '#default_value' => $config->get('send_user_info'),
    ];

    $form['exception_handling'] = [
      '#type' => 'fieldgroup',
      '#title' => $this->t('Exception handling'),
    ];

    $form['exception_handling']['bugsnag_log_exceptions'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Log unhandled errors and exceptions to Bugsnag.'),
      '#default_value' => $config->get('bugsnag_log_exceptions'),
    ];

    $levels = RfcLogLevel::getLevels();
    $level_options = [];
    foreach ($levels as $level => $label) {
      $level_options['severity-' . $level] = $label;
    }
    $form['bugsnag_logger'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Send log events of the selected severity to Bugsnag.'),
      '#options' => $level_options,
      '#default_value' => $config->get('bugsnag_logger') ?? [],
    ];

    return parent::buildForm($form, $form_state);
  }

}

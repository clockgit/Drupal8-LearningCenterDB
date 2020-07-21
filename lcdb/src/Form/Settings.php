<?php


namespace Drupal\lcdb\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class Settings extends ConfigFormBase {
  /** @var string Config settings */
  const SETTINGS = 'lcdb.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lcdb_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['year'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default Year'),
      '#default_value' => $config->get('year'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration
    $this->configFactory->getEditable(static::SETTINGS)
      // Set the submitted configuration setting
      ->set('year', $form_state->getValue('year'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}

//$config = \Drupal::config('lcdb.settings');
//$config->get('year');
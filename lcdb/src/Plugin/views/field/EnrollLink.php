<?php


/**
 * @file
 * Definition of Drupal\d8views\Plugin\views\field\EnrollLink
 */

namespace Drupal\lcdb\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("enroll_link")
 */
class EnrollLink extends FieldPluginBase {

  /**
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * @{inheritdoc}
   * @return bool
   */
  protected function allowAdvancedRender() {
    return TRUE;
  }

  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['link_text'] = array('default' => '');
    $options['prefill_text'] = array('default' => '');
    $options['msg_text'] = array('default' => '');
    return $options;
  }
  /**
   * Provide the options form.
   *
   * @param $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $form['link_text'] = array(
      '#title' => $this->t('Link'),
      '#description' => $this->t('You may use token substitutions from the rewriting section.'),
      '#type' => 'textfield',
      '#default_value' => $this->options['link_text'],
      '#maxlength' => 200,
    );
    $form['prefill_text'] = array(
      '#title' => $this->t('Value'),
      '#description' => $this->t('You may use token substitutions from the rewriting section.'),
      '#type' => 'textfield',
      '#default_value' => $this->options['prefill_text'],
      '#maxlength' => 200,
    );
    $form['msg_text'] = array(
      '#title' => $this->t('Copy Message'),
      '#description' => $this->t('You may use token substitutions from the rewriting section.'),
      '#type' => 'textfield',
      '#default_value' => $this->options['msg_text'],
      '#maxlength' => 200,
    );

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {
    $id = "enroll_" . $values->index ."_" . $values->id;
    $msg =  $this->viewsTokenReplace($this->options['msg_text'], $this->getRenderTokens($values));
    $prefill =  $this->viewsTokenReplace($this->options['prefill_text'], $this->getRenderTokens($values));
    $link =  $this->viewsTokenReplace($this->options['link_text'], $this->getRenderTokens($values));
    $text1 =  $link . $prefill;
    $text2 = '<button onclick="(function(){ var link = document.getElementById(\'' . $id . '\'); link.select(); link.setSelectionRange(0, 99999); document.execCommand(\'copy\'); alert(\'Copied ' . $msg . '\'); })();">Copy</button>';
    return [
      '#type' => 'inline_template',
      '#template' => '<strong>' . $prefill . '</strong><br /><input type="text" value="' . $text1 . '" id="' . $id . '">' . $text2,
    ];
  }
}

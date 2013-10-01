<?php
/**
 * @file
 * A contact form built on Perseus.
 */
namespace Perseus\Tools;

use Perseus\Form;

class ContactForm extends Form {
  // Constructor
  public function __construct($system, $settings = array()) {
    parent::__construct($system, $settings);

    // Build the form
    $this->createToField();
    $this->createEmailField();
  }

  /**
   * Create the basic fields.
   */
  private function createToField() {
    $data = array(
      'name' => 'to',
      'label' => 'To',
      'placeholder' => TRUE,
      'attributes' => array(
        'maxlength' => 128,
      ),
      'validators' => array('plain_text'),
    );
    $this->addItem('text', $data);
  }

  /**
   * Create the email field.
   */
  private function createEmailField() {
    $data = array(
      'name' => 'mail',
      'label' => 'E-mail',
      'placeholder' => TRUE,
      'attributes' => array(
        'maxlength' => 128,
      ),
      'validators' => array('email'),
    );
    $this->addItem('text', $data);
  }
}

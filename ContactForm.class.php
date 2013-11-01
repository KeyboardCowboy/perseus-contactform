<?php
/**
 * @file
 * A contact form built on Perseus.
 */
namespace Perseus\Extensions;

use Perseus\System as Perseus;
use Perseus\System\System;
use Perseus\Services\Form;
use Perseus\Services\Form\Item;
use Perseus\Services\PhpMail;

class ContactForm extends Form {
  public $to            = 'you@example.com';

  public $mail_template = '';

  public $mail_success  = 'Your message has been sent.';
  public $mail_fail     = 'Sorry, there was an error delivering your message.';

  // Constructor
  public function __construct(array $settings = array()) {
    parent::__construct($settings);

    // Build the From field
    $from = new Item\Text('from');
    $from->label    = 'Your Name';
    $from->required = TRUE;
    $from->wrap     = TRUE;
    $from->weight   = 0;
    $this->addChild('from', $from);

    // Build the email field
    $mail = new Item\Text\Email('mail');
    $mail->required = TRUE;
    $mail->wrap     = TRUE;
    $mail->weight   = 5;
    $this->addChild('mail', $mail);

    // Build the subject field.
    $sub = new Item\Text('subject');
    $sub->label    = 'Subject';
    $sub->required = TRUE;
    $sub->weight   = 10;
    $sub->wrap     = TRUE;
    $this->addChild('subject', $sub);

    // Build the message field.
    $message = new Item\Textarea('message');
    $message->label    = 'Your Message';
    $message->weight   = 15;
    $message->required = TRUE;
    $this->addChild('message', $message);

    // Build the Submit button
    $submit = new Item\Submit('op');
    $submit->default_value  = 'Send';
    $submit->weight         = 100;
    $submit->wrap           = TRUE;
    $this->addChild('submit', $submit);
  }

  // Validate the form
  public function validate() {
    parent::validate();
  }

  // Submit the form
  public function submit() {
    parent::submit();
    global $perseus;

    $mail = new PhpMail();
    $mail->from($this->data['mail'], $this->data['from']);
    $mail->addRecipient($this->to);
    $mail->subject($this->data['subject']);

    // Build the body.
    $args = array(
      'from'    => $this->data['from'],
      'mail'    => $this->data['mail'],
      'subject' => $this->data['subject'],
      'message' => $this->data['message'],
    );
    $body = $perseus->theme($this->mail_template, $args);
    if (!$body) {
      $body = $this->data['message'];
    }
    $mail->body($body);

    // Send the mail.
    if ($mail->send()) {
      System::setMessage($this->mail_success);
    }
    else {
      System::setMessage($this->mail_fail, SYSTEM_ERROR);
    }
  }
}

<?php

/**
 * Form class.
 */
class Form {

  protected $settings;
  protected $form_number = 1;

  function __construct($settings) {
    $this->settings = $settings;
  }

  function build() {
    $output = '';

    // For multiple forms, create a counter.
    $this->form_number++;

    // Check for submitted form and validate
    if (isset($_POST['action']) && $_POST['action'] == 'submit_' . $this->form_number) {
      if ($this->validate()) {
        $this->submit();
      }
    }

    // Loop through each form element and render it.
    foreach ($this->settings as $name => $settings) {
      $label = "<label>{$settings['title']}</label>";
      switch ($settings['type']) {
        case 'textarea':
          $input = "<textarea name='{$name}'></textarea>";
          break;

        case 'submit':
          $label = '';
          $input = "<input type='submit' name='{$name}' value='{$settings['title']}'>";
          break;

        default:
          $input = "<input type='{$settings['type']}' name='{$name}' />";
          break;
      }
      $output .= "{$label}<p>{$input}</p>";
    }

    // Wrap a form around the inputs.
    $output = <<<FORM
      <form action='{$_SERVER['PHP_SELF']}' method='post'>
        <input type='hidden' name='action' value='submit_{$this->form_number}' />
        {$output}
      </form>
FORM;

    // Return the form.
    return $output;
  }

  function validate() {
    foreach ($this->settings as $name => $settings) {
      $value = $_POST[$name];
      if (!isset($settings['validations'])) {
        continue;
      }

      foreach ($settings['validations'] as $validation) {
        switch ($validation) {
          case 'not_empty':
            if (trim($value) == '') {
              print "<span style='color:red'>{$name} is required.</span>";
              return FALSE;
            }
            break;

          case 'is_valid_email':
            if (filter_var($value, FILTER_VALIDATE_EMAIL) === FALSE) {
              print '<span style="color:red">Email unvalid.</span>';
              return FALSE;
            }
            break;
        }
      }
    }

    return TRUE;
  }

  function submit() {
    $output = '';
    foreach ($this->settings as $name => $settings) {
      $value = $_POST[$name];
      if ($settings['type'] == 'submit') {
        continue;
      }
      $output .= "<li>{$settings['title']}: {$value}</li>";
    }
    print "<p>You submitted the following:</p><ul>{$output}</ul><br/>";
  }

}

/**
 * Page class.
 */
class Page {

  protected $title;
  protected $settings;
  protected $output;

  function __construct($title, $settings) {
    $this->title = $title;
    $this->settings = $settings;
  }

  function build() {
    $this->output = '';
    foreach ($this->settings as $id => $values) {
      switch ($values['type']) {
        case 'html':
          $this->output .= "<div id='{$id}'>{$values['value']}</div>";
          break;
        case 'form':
          $form = new Form($values['value']);
          $this->output .= $form->build();
          break;
      }
    }
  }

  function theme() {
    return <<<HTML
    <html>
      <head>
      <title>{$this->title}</title>
      </head>
      <body>
        {$this->output}
      </body>
    </html>
HTML;
  }

}

/**
 * Model class.
 */
class Model {

  public static function contactFormElements() {
    return array(
      'name' => array(
        'title' => 'Name',
        'type' => 'text',
        'validations' => array('not_empty'),
      ),
      'email' => array(
        'title' => 'Email',
        'type' => 'email',
        'validations' => array('not_empty', 'is_valid_email'),
      ),
      'comment' => array(
        'title' => 'Comment',
        'type' => 'textarea',
        'validations' => array('not_empty'),
      ),
      'submit' => array(
        'title' => 'Submit',
        'type' => 'submit',
      ),
    );
  }

  public static function pageElements($contact_form) {
    return array(
      'header' => array(
        'type' => 'html',
        'value' => '<p>Please submit this form. You will make my day if you do.</p>',
      ),
      'contact_form' => array(
        'type' => 'form',
        'value' => $contact_form,
      ),
    );
  }

}

/**
 * Controller class.
 */
class Controller {

  public static function index($page_elements) {
    $page = new Page('Contact Us', $page_elements);
    $page->build();
    print $page->theme();
  }

}

// Create form elements.
$contact_elements = Model::contactFormElements();
// Create page element.
$page_elements = Model::pageElements($contact_elements);

// Render the page content.
Controller::index($page_elements);

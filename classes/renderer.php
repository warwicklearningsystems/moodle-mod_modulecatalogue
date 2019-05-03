<?php

namespace mod_modulecatalogue\output;

defined('MOODLE_INTERNAL') || die;

require_once(dirname(__FILE__) . '/../locallib.php');


class cataloguedata implements \templatable, \renderable {

  /**
   * Constructor
   * Defines class properties for course link list items.
   *
   * @param \stdClass $course A moodle course object
   */
  public function __construct($cataloguedata, $template) {

    $validproperties = array('principalaims', 'learningoutcomes');

    foreach($cataloguedata as $k => $v) {
      if ( in_array($k, $validproperties) ) {
        $this->$k = $v;
      }
    }

    $this->template = $template;
  }

  public function export_for_template(\renderer_base $output) {
    $data = array(
      'classes'     => '',
      'principalaims' => $this->principalaims,
      'learningoutcomes' => $this->learningoutcomes,
    );
    return $data;
  }
}


class renderer extends \plugin_renderer_base {

  public function render(cataloguedata $catdata) {
    $data = $catdata->export_for_template($this);

    switch($catdata->template) {
      case 'shortentry1':
        $template = 'mod_modulecatalogue/shortentry1';
        break;
      case 'fullentry1':
      default:
        $template = 'mod_modulecatalogue/fullentry1';
    }

    return $this->render_from_template($template, $data);
  }

}
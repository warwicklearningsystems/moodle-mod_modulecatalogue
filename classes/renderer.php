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

   
   // print_r($cataloguedata);
    $validproperties = array('code', 'name', 'aims', 'title', 'principal_aims', 'academicYear', 'creditValue', 'departmentname', 'facultyname', 
        'learningOutcomes', 'leadername', 'leaderemail', 'outlineSyllabus', 
        'indicativeReadingList', 'readingListUrl', 'studyAmountstype0', 'studyAmountstype1','subjectSpecificSkills',
        'studyAmountsrequiredDescription0', 'studyAmountsrequiredDescription0', 'studyAmountsrequiredDuration0',
        'studyAmountsrequiredDescription1','studyAmountsrequiredDuration1', 'totalStudyHours', 
        'assesmentGrpname0', 'assesmentGrpweighting0', 'assesmentGrpexam0','assesmentGrpdescription0', 'assesmentGrplength0', 'assesmentGrptype0',
        'assesmentGrpname1', 'assesmentGrpweighting1', 'assesmentGrpexam1', 'assesmentGrpdescription1', 'assesmentGrplength1', 'assesmentGrptype1',
        'assesmentGrpname2', 'assesmentGrpweighting2', 'assesmentGrpexam2','assesmentGrpdescription2', 'assesmentGrplength2', 'assesmentGrptype2',
        'assesmentGrpname3', 'assesmentGrpweighting3', 'assesmentGrpexam3','assesmentGrpdescription3', 'assesmentGrplength3', 'assesmentGrptype3',
        'assesmentGrpname4', 'assesmentGrpweighting4', 'assesmentGrpexam4','assesmentGrpdescription4', 'assesmentGrplength4', 'assesmentGrptype4',
        );
    

    foreach($validproperties as $p) {
      $this->$p = '';
    }
    
  //  $learningOutcome = list();
    $results = array();
    $x = 0;
   
   // print_r($p);
    foreach($cataloguedata as $k => $v) {
      if ( in_array($k, $validproperties) ) {
        $this->$k = $v;
      }
    }
    
    foreach($cataloguedata as $k => $v) {
        if(substr($k, 1, 0) == 'assesmentGrpname'){
            $x = $x + 1;
        }
    }

    $this->template = $template;
  }

  public function export_for_template(\renderer_base $output) {
      $results = array();
      $x = 0;
      
    $data = array(
      'classes'     => '',
      'principal_aims' => $this->aims,
      'department' => $this->departmentname,
      'facultyname' => $this->facultyname,
      'code' => $this->code,
      'name' => $this->title,
      'academic_year' => $this->academicYear,
      'creditValue' => $this->creditValue,
      'learningOutcomes' => explode(("<br />"),nl2br($this->learningOutcomes)),
      'leadername' => $this->leadername,
      'leaderemail' => $this->leaderemail,
      'outlineSyllabus' => $this->outlineSyllabus,
      'indicativeReadingList' => $this->indicativeReadingList,
      'readingListUrl' => $this->readingListUrl,
      'studyAmountstype0' => $this->studyAmountstype0,
      'studyAmountsrequiredDescription0' => $this->studyAmountsrequiredDescription0,
      'studyAmountsrequiredDuration0' => $this->studyAmountsrequiredDuration0,
      'studyAmountstype1' =>$this->studyAmountstype1,
      'studyAmountsrequiredDescription1' => $this->studyAmountsrequiredDescription1, 
      'studyAmountsrequiredDuration1' => $this->studyAmountsrequiredDuration1,
      'totalStudyHours' => $this->totalStudyHours,
      'assesmentGrptype0' => $this->assesmentGrptype0,
      'assesmentGrpname0' => $this->assesmentGrpname0,
      'assesmentGrpweighting0' => $this->assesmentGrpweighting0,
      'assesmentGrpexam0' => $this->assesmentGrpexam0,
      'assesmentGrpdescription0' => $this->assesmentGrpdescription0,
      'assesmentGrplength0' => $this->assesmentGrplength0,
        
      'assesmentGrptype1' => $this->assesmentGrptype1,
      'assesmentGrpname1' => $this->assesmentGrpname1,
      'assesmentGrpweighting1' => $this->assesmentGrpweighting1,
      'assesmentGrpexam1' => $this->assesmentGrpexam1,
      'assesmentGrpdescription1' => $this->assesmentGrpdescription1,
      'assesmentGrplength1' => $this->assesmentGrplength1,
        
      'assesmentGrptype2' => $this->assesmentGrptype2,
      'assesmentGrpname2' => $this->assesmentGrpname2,
      'assesmentGrpweighting2' => $this->assesmentGrpweighting2,
      'assesmentGrpexam2' => $this->assesmentGrpexam2,
      'assesmentGrpdescription2' => $this->assesmentGrpdescription2,
      'assesmentGrplength2' => $this->assesmentGrplength2,
        
      'assesmentGrptype3' => $this->assesmentGrptype3,
      'assesmentGrpname3' => $this->assesmentGrpname3,
      'assesmentGrpweighting3' => $this->assesmentGrpweighting3,
      'assesmentGrpexam3' => $this->assesmentGrpexam3,
      'assesmentGrpdescription3' => $this->assesmentGrpdescription3,
      'assesmentGrplength3' => $this->assesmentGrplength3,
        
      'assesmentGrptype4' => $this->assesmentGrptype4,
      'assesmentGrpname4' => $this->assesmentGrpname4,
      'assesmentGrpweighting4' => $this->assesmentGrpweighting4,
      'assesmentGrpexam4' => $this->assesmentGrpexam4,
      'assesmentGrpdescription4' => $this->assesmentGrpdescription4,
      'assesmentGrplength4' => $this->assesmentGrplength4,
        
      'subjectSpecificSkills' => $this->subjectSpecificSkills
    );
   
    return $data;

  }
}


class renderer extends \plugin_renderer_base {

  public function render(\renderable $catdata) {
    $data = $catdata->export_for_template($this);

    switch($catdata->template) {
      case 'shortentry1':
        $template = 'mod_modulecatalogue/shortentry1';
        break;
      case 'fancyentry':
        $template = 'mod_modulecatalogue/fancyentry';
        break;
      case 'catalogue1':
        $template = 'mod_modulecatalogue/catalogue1';
        break;
      case 'fullentry1':
      default:
        $template = 'mod_modulecatalogue/fullentry1';
    }

    return $this->render_from_template($template, $data);
  }

}
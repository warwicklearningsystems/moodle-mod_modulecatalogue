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

    
    $validproperties = array('code', 'name', 'aims', 'title', 'principal_aims', 'academicYear', 'creditValue', 'departmentname', 'facultyname', 
        'learningOutcomes', 'leadername', 'leaderemail', 'outlineSyllabus','locationname', 'duration', 
        'indicativeReadingList', 'readingListUrl', 'subjectSpecificSkills','levelname','transferableSkills',
        'totalStudyHours', 'adminname', 'adminemail','privateStudyDescription','introductoryDescription',   
        'assesmentGrpname0', 'assesmentGrpweighting0', 'assesmentGrpexam0','assesmentGrpdescription0', 'assesmentGrplength0', 'assesmentGrptype0', 'assesmentGrpComments0',
        'assesmentGrpname1', 'assesmentGrpweighting1', 'assesmentGrpexam1', 'assesmentGrpdescription1', 'assesmentGrplength1', 'assesmentGrptype1', 'assesmentGrpComments1',
        'assesmentGrpname2', 'assesmentGrpweighting2', 'assesmentGrpexam2','assesmentGrpdescription2', 'assesmentGrplength2', 'assesmentGrptype2', 'assesmentGrpComments2',
        'assesmentGrpname3', 'assesmentGrpweighting3', 'assesmentGrpexam3','assesmentGrpdescription3', 'assesmentGrplength3', 'assesmentGrptype3', 'assesmentGrpComments3',
        'assesmentGrpname4', 'assesmentGrpweighting4', 'assesmentGrpexam4','assesmentGrpdescription4', 'assesmentGrplength4', 'assesmentGrptype4', 'assesmentGrpComments4',
        'assesmentGrpname5', 'assesmentGrpweighting5', 'assesmentGrpexam5','assesmentGrpdescription5', 'assesmentGrplength5', 'assesmentGrptype5', 'assesmentGrpComments5',
        'assesmentGrpname6', 'assesmentGrpweighting6', 'assesmentGrpexam6','assesmentGrpdescription6', 'assesmentGrplength6', 'assesmentGrptype6', 'assesmentGrpComments6',
        'assesmentGrpname7', 'assesmentGrpweighting7', 'assesmentGrpexam7','assesmentGrpdescription7', 'assesmentGrplength7', 'assesmentGrptype7', 'assesmentGrpComments7',
        'assesmentGrpname8', 'assesmentGrpweighting8', 'assesmentGrpexam8','assesmentGrpdescription8', 'assesmentGrplength8', 'assesmentGrptype8', 'assesmentGrpComments8',
        'assesmentGrpname9', 'assesmentGrpweighting9', 'assesmentGrpexam9','assesmentGrpdescription9', 'assesmentGrplength9', 'assesmentGrptype9', 'assesmentGrpComments9',
        'assesmentGrpname10', 'assesmentGrpweighting10', 'assesmentGrpexam10','assesmentGrpdescription10', 'assesmentGrplength10', 'assesmentGrptype10', 'assesmentGrpComments10', 
        'assesmentGrpname11', 'assesmentGrpweighting11', 'assesmentGrpexam11','assesmentGrpdescription11', 'assesmentGrplength11', 'assesmentGrptype11', 'assesmentGrpComments11', 
        'assesmentGrpname12', 'assesmentGrpweighting12', 'assesmentGrpexam12','assesmentGrpdescription12', 'assesmentGrplength12', 'assesmentGrptype12', 'assesmentGrpComments12' ,
        'assesmentGrpname13', 'assesmentGrpweighting13', 'assesmentGrpexam13','assesmentGrpdescription13', 'assesmentGrplength13', 'assesmentGrptype13','assesmentGrpComments13',
        'assesmenttotalExamWeighting','assesmenttotalCourseworkWeighting',
        'studyAmounttype0', 'studyAmountrequiredDescription0', 'studyAmountrequiredDuration0',
        'studyAmounttype1', 'studyAmountrequiredDescription1', 'studyAmountrequiredDuration1',
        'studyAmounttype2', 'studyAmountrequiredDescription2', 'studyAmountrequiredDuration2',
        'studyAmounttype3', 'studyAmountrequiredDescription3', 'studyAmountrequiredDuration3',
        'studyAmounttype4', 'studyAmountrequiredDescription4', 'studyAmountrequiredDuration4',
        'studyAmounttype5', 'studyAmountrequiredDescription5', 'studyAmountrequiredDuration5',
        'preRequisiteModulecode0', 'preRequisiteModuletitle0', 'preRequisiteModulecode1', 'preRequisiteModuletitle1',
        'postRequisiteModulecode0', 'postRequisiteModuletitle0','postRequisiteModulecode0', 'postRequisiteModuletitle0',
        'totalExamWeighting','totalCourseworkWeighting','url', 'isprivate','resittotalExamWeighting','resittotalCourseworkWeighting',
        'resitGrpname0','resitGrptype0','resitGrpweighting0','resitGrpdescription0','resitGrplength0','resitGrpComments0',
        'resitGrpname1','resitGrptype1','resitGrpweighting1','resitGrpdescription1','resitGrplength1','resitGrpComments1',
        'resitGrpname2','resitGrptype2','resitGrpweighting2','resitGrpdescription2','resitGrplength2','resitGrpComments2','alert','alertmessage','urllink'
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
    
    //'alertmessage','urllink','alert', 
    
    $this->template = $template;
  }

  public function export_for_template(\renderer_base $output) {
      $results = array();
      $x = 0;
      $delimiter = '<br />';
      
      $alertmessage = get_config('mod_modulecatalogue', 'alertinformation');
      $applyAlert = get_config('mod_modulecatalogue', 'applyAlert');
      $all_urls = "";   //MOO-1983 initialize URL link extracted field.
      
    $data = array(
        'classes'     => '',
        'principal_aims' => explode(($delimiter), $this->aims),
        'department' => $this->departmentname,
        'facultyname' => $this->facultyname,
        'levelname' =>$this->levelname,
        'code' => $this->code,
        'name' => $this->title,
        'academicyear' => $this->academicYear,  /*MOO-1983 renamed to academicYear for consistency*/
        'creditValue' => $this->creditValue,           
        'learningOutcomes' => expand_array($this->learningOutcomes),
        'leadername' => $this->leadername,
        'leaderemail' => $this->leaderemail,
        'locationname' => $this->locationname,
        'url' => $this->url,
        'outlineSyllabus' => array_slice(explode(($delimiter), $this->outlineSyllabus), 4),
        'outlineSyllabusShort' => array_slice(explode(($delimiter), $this->outlineSyllabus), 0, 4),
        /*MOO-2143 Indicative reading list fix to implement as expanding button*/
        'indicativeReadingList' => array_slice(explode(($delimiter), $this->indicativeReadingList), 4),
        'indicativeReadingListSummary' => array_slice(explode(($delimiter), $this->indicativeReadingList), 0, 4),
        
        'readingListUrl' => $this->readingListUrl,
        'introductoryDescription' => explode(($delimiter), $this->introductoryDescription),
        'totalStudyHours' => $this->totalStudyHours,
        
        'assesmentGrptype0' => $this->assesmentGrptype0,
        'assesmentGrpname0' => $this->assesmentGrpname0,
        'assesmentGrpweighting0' => $this->assesmentGrpweighting0 .'%',
        'assesmentGrpexam0' => $this->assesmentGrpexam0,
        'assesmentGrpdescription0' => $this->assesmentGrpdescription0,
        'assesmentGrplength0' => $this->assesmentGrplength0,
        'assesmentGrpComments0' => $this->assesmentGrpComments0,
        
        'assesmentGrptype1' => $this->assesmentGrptype1,
        'assesmentGrpname1' => $this->assesmentGrpname1,
        'assesmentGrpweighting1' => $this->assesmentGrpweighting1 .'%',
        'assesmentGrpexam1' => $this->assesmentGrpexam1,
        'assesmentGrpdescription1' => $this->assesmentGrpdescription1,
        'assesmentGrplength1' => $this->assesmentGrplength1,
        'assesmentGrpComments1' => $this->assesmentGrpComments1,
        
        'assesmentGrptype2' => $this->assesmentGrptype2,
        'assesmentGrpname2' => $this->assesmentGrpname2,
        'assesmentGrpweighting2' => $this->assesmentGrpweighting2 .'%',
        'assesmentGrpexam2' => $this->assesmentGrpexam2,
        'assesmentGrpdescription2' => $this->assesmentGrpdescription2,
        'assesmentGrplength2' => $this->assesmentGrplength2,
        'assesmentGrpComments2' => $this->assesmentGrpComments2,
        
        'assesmentGrptype3' => $this->assesmentGrptype3,
        'assesmentGrpname3' => $this->assesmentGrpname3,
        'assesmentGrpweighting3' => $this->assesmentGrpweighting3 .'%',
        'assesmentGrpexam3' => $this->assesmentGrpexam3,
        'assesmentGrpdescription3' => $this->assesmentGrpdescription3,
        'assesmentGrplength3' => $this->assesmentGrplength3,
        'assesmentGrpComments3' => $this->assesmentGrpComments3,
        
        'assesmentGrptype4' => $this->assesmentGrptype4,
        'assesmentGrpname4' => $this->assesmentGrpname4,
        'assesmentGrpweighting4' => $this->assesmentGrpweighting4 .'%',
        'assesmentGrpexam4' => $this->assesmentGrpexam4,
        'assesmentGrpdescription4' => $this->assesmentGrpdescription4,
        'assesmentGrplength4' => $this->assesmentGrplength4,
        'assesmentGrpComments4' => $this->assesmentGrpComments4,
        
        'assesmentGrptype5' => $this->assesmentGrptype5,
        'assesmentGrpname5' => $this->assesmentGrpname5,
        'assesmentGrpweighting5' => $this->assesmentGrpweighting5 .'%',
        'assesmentGrpexam5' => $this->assesmentGrpexam5,
        'assesmentGrpdescription5' => $this->assesmentGrpdescription5,
        'assesmentGrplength5' => $this->assesmentGrplength5,
        'assesmentGrpComments5' => $this->assesmentGrpComments5,
        
        'assesmentGrptype6' => $this->assesmentGrptype6,
        'assesmentGrpname6' => $this->assesmentGrpname6,
        'assesmentGrpweighting6' => $this->assesmentGrpweighting6 .'%',
        'assesmentGrpexam6' => $this->assesmentGrpexam6,
        'assesmentGrpdescription6' => $this->assesmentGrpdescription6,
        'assesmentGrplength6' => $this->assesmentGrplength6,
        'assesmentGrpComments6' => $this->assesmentGrpComments6,
        
        'assesmentGrptype7' => $this->assesmentGrptype7,
        'assesmentGrpname7' => $this->assesmentGrpname7,
        'assesmentGrpweighting7' => $this->assesmentGrpweighting7 .'%',
        'assesmentGrpexam7' => $this->assesmentGrpexam7,
        'assesmentGrpdescription7' => $this->assesmentGrpdescription7,
        'assesmentGrplength7' => $this->assesmentGrplength7,
        'assesmentGrpComments7' => $this->assesmentGrpComments10,
        
        'assesmentGrptype8' => $this->assesmentGrptype8,
        'assesmentGrpname8' => $this->assesmentGrpname8,
        'assesmentGrpweighting8' => $this->assesmentGrpweighting8 .'%',
        'assesmentGrpexam8' => $this->assesmentGrpexam8,
        'assesmentGrpdescription8' => $this->assesmentGrpdescription8,
        'assesmentGrplength8' => $this->assesmentGrplength8,
        'assesmentGrpComments8' => $this->assesmentGrpComments8,
        
        'assesmentGrptype9' => $this->assesmentGrptype9,
        'assesmentGrpname9' => $this->assesmentGrpname9,
        'assesmentGrpweighting9' => $this->assesmentGrpweighting9 .'%',
        'assesmentGrpexam9' => $this->assesmentGrpexam9,
        'assesmentGrpdescription9' => $this->assesmentGrpdescription9,
        'assesmentGrplength9' => $this->assesmentGrplength9,
        'assesmentGrpComments9' => $this->assesmentGrpComments9,
        
        'assesmentGrptype10' => $this->assesmentGrptype10,
        'assesmentGrpname10' => $this->assesmentGrpname10,
        'assesmentGrpweighting10' => $this->assesmentGrpweighting10 .'%',
        'assesmentGrpexam10' => $this->assesmentGrpexam10,
        'assesmentGrpdescription10' => $this->assesmentGrpdescription10,
        'assesmentGrplength10' => $this->assesmentGrplength10,
        'assesmentGrpComments10' => $this->assesmentGrpComments10,
        
        'assesmentGrptype11' => $this->assesmentGrptype11,
        'assesmentGrpname11' => $this->assesmentGrpname11,
        'assesmentGrpweighting11' => $this->assesmentGrpweighting11 .'%',
        'assesmentGrpexam11' => $this->assesmentGrpexam11,
        'assesmentGrpdescription11' => $this->assesmentGrpdescription11,
        'assesmentGrplength11' => $this->assesmentGrplength11,
        
        'assesmentGrpComments12' => $this->assesmentGrpComments12,
        'assesmentGrptype12' => $this->assesmentGrptype12,
        'assesmentGrpname12' => $this->assesmentGrpname12,
        'assesmentGrpweighting12' => $this->assesmentGrpweighting12 .'%',
        'assesmentGrpexam12' => $this->assesmentGrpexam12,
        'assesmentGrpdescription12' => $this->assesmentGrpdescription12,
        'assesmentGrplength12' => $this->assesmentGrplength12,
        'assesmentGrpComments12' => $this->assesmentGrpComments12,
        
        'assesmentGrptype13' => $this->assesmentGrptype13,
        'assesmentGrpname13' => $this->assesmentGrpname13,
        'assesmentGrpweighting13' => $this->assesmentGrpweighting13 .'%',
        'assesmentGrpexam13' => $this->assesmentGrpexam13,
        'assesmentGrpdescription13' => $this->assesmentGrpdescription13,
        'assesmentGrplength13' => $this->assesmentGrplength13,
        'assesmentGrpComments13' => $this->assesmentGrpComments13,
        
        /* MOO-2373 added resit information*/
        'resitGrpname0' => $this->resitGrpname0,
        'resitGrptype0' => $this->resitGrptype0,
        'resitGrpweighting0' => $this->resitGrpweighting0,
        'resitGrpdescription0' => $this->resitGrpdescription0,
        'resitGrplength0' => $this->resitGrplength0,
        'resitGrpComments0' => $this->resitGrpComments0,
        
        'resitGrpname1' => $this->resitGrpname1,
        'resitGrptype1' => $this->resitGrptype1,
        'resitGrpweighting1' => $this->resitGrpweighting1,
        'resitGrpdescription1' => $this->resitGrpdescription1,
        'resitGrplength1' => $this->resitGrplength1,
        'resitGrpComments1' => $this->resitGrpComments1,
        
        'resitGrpname2' => $this->resitGrpname2,
        'resitGrptype2' => $this->resitGrptype2,
        'resitGrpweighting2' => $this->resitGrpweighting2,
        'resitGrpdescription2' => $this->resitGrpdescription2,
        'resitGrplength2' => $this->resitGrplength2,
        'resitGrpComments2' => $this->resitGrpComments2,
        
        'preRequisiteModulecode0'=> $this->preRequisiteModulecode0,
        'preRequisiteModuletitle0' => $this->preRequisiteModuletitle0,
        'preRequisiteModulecode1'=> $this->preRequisiteModulecode1,
        'preRequisiteModuletitle1' => $this->preRequisiteModuletitle1,
        'postRequisiteModulecode0' => $this->postRequisiteModulecode0,
        'postRequisiteModuletitle0' => $this->postRequisiteModuletitle0,
        
        'privateStudyDescription' => explode(($delimiter), $this->privateStudyDescription),
        'subjectSpecificSkills' => explode(($delimiter), $this->subjectSpecificSkills),
        'adminemail' => $this->adminemail,
        'adminname' => $this->adminname,
        /*MOO 2143 Changes to Study Hours: incorporated as these where not properly displayed*/
        'studyAmounttype0' => $this->studyAmounttype0,
        'studyAmountrequiredDescription0' =>extract_course_weightings($this->studyAmountrequiredDescription0, $this->totalStudyHours),
        
        'studyAmountrequiredDuration0' =>$this->studyAmountrequiredDuration0,  
        'studyAmounttype1' => $this->studyAmounttype1,
        'studyAmountrequiredDescription1' =>extract_course_weightings($this->studyAmountrequiredDescription1, $this->totalStudyHours),
        'studyAmountrequiredDuration1' =>$this->studyAmountrequiredDuration1,

        'studyAmounttype2' => $this->studyAmounttype2,
        'studyAmountrequiredDescription2' =>extract_course_weightings($this->studyAmountrequiredDescription2, $this->totalStudyHours) ,
        'studyAmountrequiredDuration2' =>$this->studyAmountrequiredDuration2,
  
        'studyAmounttype3' => $this->studyAmounttype3,
        'studyAmountrequiredDescription3' =>extract_course_weightings($this->studyAmountrequiredDescription3, $this->totalStudyHours),
        'studyAmountrequiredDuration3' =>$this->studyAmountrequiredDuration3,

        'studyAmounttype4' => $this->studyAmounttype4,
        'studyAmountrequiredDescription4' =>extract_course_weightings($this->studyAmountrequiredDescription4, $this->totalStudyHours),
        'studyAmountrequiredDuration4' =>$this->studyAmountrequiredDuration4,

        'studyAmounttype5' => $this->studyAmounttype5,
        'studyAmountrequiredDescription5' =>extract_course_weightings($this->studyAmountrequiredDescription5, $this->totalStudyHours),
        'studyAmountrequiredDuration5' =>$this->studyAmountrequiredDuration5,
        /*MOO-1983 Added alert message, alert and urlink */
        'alertmessage' => explode(($delimiter), $this->alertmessage), 
        'urllink' => $all_urls,
        'alert' => $applyAlert,
        /*MOO-1983 added duration, transferableskills, and exam weighting not added previously*/
        'duration' => $this->duration,
        /* MOO-2373 added resit information*/
        'isTranfereable' => $this->transferableSkills,
        'alert' => $this->alert,
        'transferableSkills' => explode(($delimiter), $this->transferableSkills),
        'assesmenttotalExamWeighting' => $this->assesmenttotalExamWeighting,
        'assesmenttotalCourseworkWeighting' => $this->assesmenttotalCourseworkWeighting,
        'resittotalExamWeighting' => $this->resittotalExamWeighting,
        'resittotalCourseworkWeighting' => $this->resittotalCourseworkWeighting,
        'id1' => 'col' .uniqid(),
        'id2' => 'col' .uniqid(),
        'id3' => 'col' .uniqid(),
        'id4' => 'col' .uniqid(),
        'id5' => 'col' .uniqid(),
        'id6' => 'col' .uniqid(),
        'id7' => 'col' .uniqid(),
        'id8' => 'col' .uniqid(),
        'id9' => 'col' .uniqid(),
        'id10' => 'col' .uniqid(),
        'id11' => 'col' .uniqid(),
        'id12' => 'col' .uniqid(),
        'id13' => 'col' .uniqid(),
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

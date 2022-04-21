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
        'totalStudyHours', 'adminname', 'adminemail','privateStudyDescription','introductoryDescription','studyAmounts',   
        'assessmentstotalCourseworkWeighting','assessmentstotalExamWeighting','assessments','resit',
        'totalExamWeighting','totalCourseworkWeighting','url', 'isprivate','resittotalExamWeighting','resittotalCourseworkWeighting',
        'alert','alertmessage','urllink','catalogueurl','postRequisiteModules','preRequisiteModules','otherPreRequisites','Optionalcomments',
        'Core','CoreOption', 'Optional','OptionListA','OptionListB','isOptional','isOptionalA','isOptionalB','isCore','isCoreOptional','CoreOptionListB','OptionListC','isOptionListC',
        'CoreOptionListA', 'CoreOptionListC','isCoreOptionListA','isCoreOptionListB','isCoreOptionListC','ispreRequisiteModules','ispostRequisiteModules','antiRequisiteModules','isantiRequisiteModules','availability',
        'CoreOptionListD','isCoreOptionListD','OptionListG','isOptionListG'
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
        
        if ($k == 'Optional'){                   
            if ((count(unserialize($v))) > 0){
                $this->isOptional = true;
            }
        }
        
        if ($k == 'OptionListB'){                 
            if ((count(unserialize($v))) > 0){
                $this->isOptionalB = true;
            }
        }
        
        if ($k == 'OptionListC'){
            if ((count(unserialize($v))) > 0){
                $this->isOptionListC = true;
            }
        }
        
        if ($k == 'OptionListA'){                 
            if ((count(unserialize($v))) > 0){
                $this->isOptionalA = true;
            }
        }
        
        if ($k == 'Core'){                   
            if ((count(unserialize($v))) > 0){
                $this->isCore = true;
            }
        }
        
        if ($k == 'CoreOption'){                   
            if ((count(unserialize($v))) > 0){
                $this->isCoreOptional = true;
            }
        }
        
        if ($k == 'CoreOptionListB'){                   
            if ((count(unserialize($v))) > 0){
                $this->isCoreOptionListB = true;
            }
        }
        
        if ($k == 'CoreOptionListC'){                   
            if ((count(unserialize($v))) > 0){
                $this->isCoreOptionListC = true;
            }
        }
        
        if ($k == 'preRequisiteModules'){                   
            if ((count(unserialize($v))) > 0){
                $this->ispreRequisiteModules = true;
            }
        }
        
         if ($k == 'postRequisiteModules'){                   
            if ((count(unserialize($v))) > 0){
                $this->ispostRequisiteModules = true;
            }
        }
        
        if ($k == 'antiRequisiteModules'){                   
            if ((count(unserialize($v))) > 0){
                $this->isantiRequisiteModules = true;
            }
        }
        
        if ($k == 'CoreOptionListA'){
            if ((count(unserialize($v))) > 0){
                $this->isCoreOptionListA = true;
            }
        }
            
        if ($k == 'CoreOptionListD'){
            if ((count(unserialize($v))) > 0){
                $this->isCoreOptionListD = true;
            }
        }
        
        if($k == 'OptionListG'){
            if ((count(unserialize($v))) > 0){
                $this->isOptionListG = true;
            }
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
        'studyAmounts' => unserialize($this->studyAmounts),       
        /* MOO-2373 added resit information*/
               
        'privateStudyDescription' => explode(($delimiter), $this->privateStudyDescription),
        'subjectSpecificSkills' => explode(($delimiter), $this->subjectSpecificSkills),
        'adminemail' => $this->adminemail,
        'adminname' => $this->adminname,
        /*MOO 2143 Changes to Study Hours: incorporated as these where not properly displayed*/
        
        /*MOO-1983 Added alert message, alert and urlink */
        'alertmessage' => explode(($delimiter), $this->alertmessage), 
        'urllink' => $all_urls,
        'alert' => $applyAlert,
        'catalogueurl' => $this->catalogueurl,
        /*MOO-1983 added duration, transferableskills, and exam weighting not added previously*/
        'duration' => $this->duration,
        /* MOO-2373 added resit information*/
        'isTranfereable' => $this->transferableSkills,
        'alert' => $this->alert,
        'transferableSkills' => explode(($delimiter), $this->transferableSkills),
        'assessmentstotalExamWeighting' => $this->assessmentstotalExamWeighting,
        'assessmentstotalCourseworkWeighting' => $this->assessmentstotalCourseworkWeighting,
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
        'assessments' => unserialize($this->assessments),
        'resit' => unserialize($this->resit),
        'postRequisiteModules' => unserialize($this->postRequisiteModules),
        'preRequisiteModules' => unserialize($this->preRequisiteModules),
        'otherPreRequisites' => $this->otherPreRequisites,
        'Core' => unserialize($this->Core),
        'CoreOption' => unserialize($this->CoreOption),
        'Optional' => unserialize($this->Optional),
        'OptionListB' => unserialize($this->OptionListB),
        'OptionListC' => unserialize($this->OptionListC),
        'isOptionListC' =>$this->isOptionListC,
        'isOptional' => $this->isOptional,
        'isOptionalB' => $this->isOptionalB,
        'isCore' => $this->isCore,
        'isCoreOptional' => $this->isCoreOptional,
        'OptionListA' => unserialize($this->OptionListA),
        'CoreOptionListA' => unserialize($this->CoreOptionListA),
        'CoreOptionListB' => unserialize($this->CoreOptionListB),
        'CoreOptionListC' => unserialize($this->CoreOptionListC),
        'isOptionalA' => $this->isOptionalA,
        'isCoreOptionListA' =>$this->isCoreOptionListA,
        'isCoreOptionListB' =>$this->isCoreOptionListB,
        'isCoreOptionListC' =>$this->isCoreOptionListC,
        'CoreOptionListA' => unserialize($this->CoreOptionListA),
        'ispreRequisiteModules' =>$this->ispreRequisiteModules,
        'ispostRequisiteModules' =>$this->ispostRequisiteModules,
        'antiRequisiteModules' => unserialize($this->antiRequisiteModules),
        'isantiRequisiteModules' => $this->isantiRequisiteModules,
        'availability' => $this->availability,
        'Optionalcomments' => $this->Optionalcomments,
        'CoreOptionListD' => unserialize($this->CoreOptionListD),
        'isCoreOptionListD' => $this->isCoreOptionListD,
        'OptionListG' => unserialize($this->OptionListG),
        'isOptionListG' => unserialize($this->OptionListG),
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

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DefaultCodes{
    public $moduleCode;
    public $academicYear;
    
    function set_moduleCode($moduleCode){
        $this->moduleCode = $moduleCode;
    }
    
    function get_moduleCode() {
        return $this->moduleCode;
    }
    
    function set_academicYear($academicYear){
        $this->academicYear = $academicYear;
    }
    
    function get_academicYear() {
        return $this->academicYear;
    }
    
}


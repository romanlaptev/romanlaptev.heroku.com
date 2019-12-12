<?php
////////////////////////////////////////////////////////////////////
/**
Class for navigating over multiple pages
*/
class PageNavigator{
  //data members
  var $pagename;
  var $totalpages;
  var $recordsperpage;
  var $maxpagesshown;
  var $currentstartpage;
  var $currentendpage;
  var $currentpage;
  //next and previous inactive
  var $spannextinactive;
  var $spanpreviousinactive;
  //first and last inactive
  var $firstinactivespan;
  var $lastinactivespan;  
  //must match $_GET['offset'] in calling page
  var $firstparamname = "offset";
  //use as "&name=value" pair for getting
  var $params;
  //css class names
  var $inactivespanname = "inactive";
  var $pagedisplaydivname = "totalpagesdisplay";
  var $divwrappername = "navigator";
  //text for navigation
  var $strfirst = "|&lt;";
  var $strnext = "Next";
  var $strprevious = "Prev";
  var $strlast = "&gt;|";
  //for error reporting
  var $errorstring;  
////////////////////////////////////////////////////////////////////
//constructor
////////////////////////////////////////////////////////////////////
   function PageNavigator($pagename, $totalrecords, $recordsperpage, $recordoffset, $maxpagesshown = 4, $params = ""){
    $this->pagename = $pagename;
    $this->recordsperpage = $recordsperpage;  
    $this->maxpagesshown = $maxpagesshown;
    //already urlencoded
    $this->params = $params;
    //check recordoffset a multiple of recordsperpage
    $this->checkRecordOffset($recordoffset, $recordsperpage) or
      die($this->errorstring);
    $this->setTotalPages($totalrecords, $recordsperpage);
    $this->calculateCurrentPage($recordoffset, $recordsperpage);
    $this->createInactiveSpans();
    $this->calculateCurrentStartPage();
    $this->calculateCurrentEndPage();
  }
////////////////////////////////////////////////////////////////////
// public methods
////////////////////////////////////////////////////////////////////
//give css class name to inactive span
////////////////////////////////////////////////////////////////////
   function setInactiveSpanName($name){
    $this->inactivespanname = $name;
    //call function to rename span
    $this->createInactiveSpans();  
  }
////////////////////////////////////////////////////////////////////
   function getInactiveSpanName(){
    return $this->inactivespanname;
  }
////////////////////////////////////////////////////////////////////
   function setPageDisplayDivName($name){
    $this->pagedisplaydivname = $name;    
  }
////////////////////////////////////////////////////////////////////
   function getPageDisplayDivName(){
    return $this->pagedisplaydivname;
  }
////////////////////////////////////////////////////////////////////
   function setDivWrapperName($name){
    $this->divwrappername = $name;    
  }
////////////////////////////////////////////////////////////////////
   function getDivWrapperName(){
    return $this->divwrappername;
  }
////////////////////////////////////////////////////////////////////
   function setFirstParamName($name){
    $this->firstparamname = $name;    
  }
////////////////////////////////////////////////////////////////////
   function getFirstParamName(){
    return $this->firstparamname;
  }
////////////////////////////////////////////////////////////////////
/**
Returns HTML code for the navigator
*/
   function getNavigator(){
    //wrap in div tag
    $strnavigator = "<div class=\"$this->divwrappername\">\n";
    //output movefirst button    
    if($this->currentpage == 0){
      $strnavigator .= $this->firstinactivespan;
    }else{
      $strnavigator .= $this->createLink(0, $this->strfirst);
    }
    //output moveprevious button
    if($this->currentpage == 0){
      $strnavigator .= $this->spanpreviousinactive;
    }else{
      $strnavigator.= $this->createLink($this->currentpage-1, $this->strprevious);
    }
    //loop through displayed pages from $currentstart
    for($x = $this->currentstartpage; $x < $this->currentendpage; $x++){
      //make current page inactive
      if($x == $this->currentpage){
        $strnavigator .= "<span class=\"$this->inactivespanname\">";
        $strnavigator .= $x+1;
        $strnavigator .= "</span>\n";
      }else{
        $strnavigator .= $this->createLink($x, $x+1);
      }
    }
    //next button    
    if($this->currentpage == $this->totalpages-1){
      $strnavigator .= $this->spannextinactive;      
    }else{
      $strnavigator .= $this->createLink($this->currentpage + 1, $this->strnext);
    }
    //move last button
    if($this->currentpage == $this->totalpages-1){
      $strnavigator .= $this->lastinactivespan;
    }else{
      $strnavigator .= $this->createLink($this->totalpages -1, $this->strlast);
    }
    $strnavigator .=  "</div>\n";
    $strnavigator .= $this->getPageNumberDisplay();
    return $strnavigator;
  }
////////////////////////////////////////////////////////////////////
// private methods
////////////////////////////////////////////////////////////////////
   function createLink($offset, $strdisplay ){
    $strtemp = "<a href=\"$this->pagename?$this->firstparamname=";
    $strtemp .= $offset;
    $strtemp .= "$this->params\">$strdisplay</a>\n";
    return $strtemp;
  }
////////////////////////////////////////////////////////////////////  
   function getPageNumberDisplay(){
    $str = "<div class=\"$this->pagedisplaydivname\">\nPage ";
    $str .= $this->currentpage+1;
    $str .= " of $this->totalpages";
    $str .= "</div>\n";
    return $str;
  }
////////////////////////////////////////////////////////////////////
   function setTotalPages($totalrecords, $recordsperpage){
    $this->totalpages = ceil($totalrecords/$recordsperpage);
  }
////////////////////////////////////////////////////////////////////
   function checkRecordOffset($recordoffset, $recordsperpage){
    $bln = true;
    if($recordoffset%$recordsperpage != 0){
      $this->errorstring = "Error - not a multiple of records per page.";
      $bln = false;  
    }
    return $bln;
  }
////////////////////////////////////////////////////////////////////  
   function calculateCurrentPage($recordoffset, $recordsperpage){
    $this->currentpage = $recordoffset/$recordsperpage;
  }
////////////////////////////////////////////////////////////////////
// not always needed but create anyway
////////////////////////////////////////////////////////////////////
   function createInactiveSpans(){
    $this->spannextinactive = "<span class=\"".
      "$this->inactivespanname\">$this->strnext</span>\n";
    $this->lastinactivespan = "<span class=\"".
      "$this->inactivespanname\">$this->strlast</span>\n";
    $this->spanpreviousinactive = "<span class=\"".
      "$this->inactivespanname\">$this->strprevious</span>\n";
    $this->firstinactivespan = "<span class=\"".
      "$this->inactivespanname\">$this->strfirst</span>\n";
  }
////////////////////////////////////////////////////////////////////
// find start page based on current page
////////////////////////////////////////////////////////////////////
   function calculateCurrentStartPage(){
    $temp = floor($this->currentpage/$this->maxpagesshown);
    $this->currentstartpage = $temp * $this->maxpagesshown;
  }
////////////////////////////////////////////////////////////////////
   function calculateCurrentEndPage(){
    $this->currentendpage = $this->currentstartpage+$this->maxpagesshown;
    if($this->currentendpage > $this->totalpages)
    {
      $this->currentendpage = $this->totalpages;
    }
  }
}//end class
////////////////////////////////////////////////////////////////////
?>

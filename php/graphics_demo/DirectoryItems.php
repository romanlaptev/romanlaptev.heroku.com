<?php
/////////////////////////////////////////////////////////////////
/**
Create an array of files in a directory
*/
/////////////////////////////////////////////////////////////////
class DirectoryItems{
	//data members
	var $filearray = array();
	var $replacechar;
	var $directory;
//////////////////////////////////////////////////////////////////
//constructor
/////////////////////////////////////////////////////////////////
   function DirectoryItems($directory, $replacechar = "_"){
		$this->directory = $directory;
		$this->replacechar = $replacechar;
		$d = "";
  	if(is_dir($directory)){
  		$d = opendir($directory) or die("Failed to open directory.");
  		while(false !== ($f = readdir($d))){
    		if(is_file("$directory/$f")){
					$title = $this->createTitle($f);
					$this->filearray[$f] = $title;
    		}
  		}
			closedir($d);
		}else{
			//error
			die("Must pass in a directory.");
		}
	}
//////////////////////////////////////////////////////////////////
//destructor - not invoked automatically
//////////////////////////////////////////////////////////////////
	 function __destruct(){
		unset($this->filearray);
	}
//////////////////////////////////////////////////////////////////
// public functions
//////////////////////////////////////////////////////////////////
	 function getDirectoryName(){
		return $this->directory;
	}
//////////////////////////////////////////////////////////////////
	 function indexOrder(){
		sort($this->filearray);
	}
//////////////////////////////////////////////////////////////////
	 function naturalCaseInsensitiveOrder(){
		natcasesort($this->filearray);
	}
//////////////////////////////////////////////////////////////////
//returns false if files are not all images of these types
//////////////////////////////////////////////////////////////////
	 function checkAllImages(){
		$bln = true;
		$extension = "";
		$types = array("jpg", "jpeg", "gif", "png");
		foreach ($this->filearray as $key => $value){
			$extension = substr($key,(strpos($key, ".")+1));
			$extension = strtolower($extension);
			if(!in_array($extension, $types)){
				$bln = false;
				break;
			}
		}
		return $bln;
	}
//////////////////////////////////////////////////////////////////
//returns false if not all specified extension 
//////////////////////////////////////////////////////////////////
	 function checkAllSpecificType($extension){
		$extension = strtolower($extension);
		$bln = true;
		$ext = "";
		foreach ($this->filearray as $key => $value){
			$ext = substr($key,(strpos($key, ".")+1));
			$ext = strtolower($ext);
			if($extension != $ext){
				$bln=false;
				break;
			}
		}
		return $bln;
	}
//////////////////////////////////////////////////////////////////
	 function getCount(){
		return count($this->filearray);
	}
//////////////////////////////////////////////////////////////////
	 function getFileArray(){
		return $this->filearray;
	}
//////////////////////////////////////////////////////////////////
//for use with navigator - Phase 3
/////////////////////////////////////////////////////////////////
	 function getFileArraySlice($start, $length){
		return array_slice($this->filearray, $start, $length);
	}
//////////////////////////////////////////////////////////////////
//eliminate all elements from array except specified extension - Phase 2
/////////////////////////////////////////////////////////////////
	 function filter($extension){
		$extension = strtolower($extension);
		foreach ($this->filearray as $key => $value){
			$ext = substr($key,(strpos($key, ".") + 1));
			$ext = strtolower($ext);
			if($ext != $extension){
				unset($this->filearray[$key]);
			}
		}
	}
//////////////////////////////////////////////////////////////////
//eliminate all elements from array except images - Phase 2
/////////////////////////////////////////////////////////////////
	 function imagesOnly(){
		$extension = "";
		$types = array("jpg", "jpeg", "gif", "png");
		foreach ($this->filearray as $key => $value){
			$extension = substr($key,(strpos($key, ".") + 1));
			$extension = strtolower($extension);
			if(!in_array($extension, $types)){
				unset($this->filearray[$key]);
			}
		}	
	}
//////////////////////////////////////////////////////////////////
//recreate array after filtering - Phase 2
/////////////////////////////////////////////////////////////////
	 function removeFilter(){
		unset($this->filearray);
		$d = "";
		$d = opendir($this->directory) or die($php_errormsg);
		while(false!==($f=readdir($d))){
  		if(is_file("$this->directory/$f")){
				$title = $this->createTitle($f);
				$this->filearray[$f] = $title;
  		}
		}
		closedir($d);
	}	
//////////////////////////////////////////////////////////////////
//private functions
/////////////////////////////////////////////////////////////////
	function createTitle($title){
		//strip extension
		$title = substr($title,0,strrpos($title, "."));
		//replace word separator
		$title = str_replace($this->replacechar, " ", $title);
		return $title;
	}
}//end class	
?>

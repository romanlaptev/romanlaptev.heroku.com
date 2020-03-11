<?php
//requires GD 2.0.1 or higher
class ThumbnailImage{
	var $image;
	//not applicable to gif
	var $quality = 100;
	var $mimetype;
	var $imageproperties;
	var $initialfilesize;
////////////////////////////////////////////////////////
//constructor
////////////////////////////////////////////////////////
	 function __construct($file, $thumbnailsize = 100){
		//check path
		is_file($file) or die ("File: $file doesn't exist.");
		$this->initialfilesize = filesize($file);
		$this->imageproperties = getimagesize($file) or die ("Incorrect file type.");
		// new function image_type_to_mime_type
		$this->mimetype = image_type_to_mime_type($this->imageproperties[2]);	
		//create image
		switch($this->imageproperties[2]){
			case IMAGETYPE_JPEG:
				$this->image = imagecreatefromjpeg($file);	
				break;
			case IMAGETYPE_GIF:	
				$this->image = imagecreatefromgif($file);
				break;
			case IMAGETYPE_PNG:
				$this->image = imagecreatefrompng($file);
				break;
			default:
				die("Couldn't create image.");
		}
		$this->createThumb($thumbnailsize);
	}
////////////////////////////////////////////////////////
//destructor - invoke directly in PHP 4
////////////////////////////////////////////////////////
	 function __destruct(){
		if(isset($this->image)){
			imagedestroy($this->image);			
		}
	}
////////////////////////////////////////////////////////
// public methods
////////////////////////////////////////////////////////
	 function getImage(){
		header("Content-type: $this->mimetype");
		switch($this->imageproperties[2]){
			case IMAGETYPE_JPEG:
				imagejpeg($this->image,"",$this->quality);
				break;
			case IMAGETYPE_GIF:
				imagegif($this->image);
				break;
			case IMAGETYPE_PNG:
				imagepng($this->image,"",$this->quality);
				break;
			default:
				die("Couldn't create image.");
		}
	}
////////////////////////////////////////////////////////
	 function getMimeType(){
  
		return $this->mimetype;
	}
////////////////////////////////////////////////////////
	 function getQuality(){
		$quality = null;
		if($this->imageproperties[2] == IMAGETYPE_JPEG	|| $this->imageproperties[2] == IMAGETYPE_PNG){
			$quality = $this->quality;
		}
		return $quality;
	}
////////////////////////////////////////////////////////
	 function setQuality($quality){
		if($quality > 100 || $quality  <  1){
			$quality = 75;
    }
		if($this->imageproperties[2] == IMAGETYPE_JPEG || $this->imageproperties[2] == IMAGETYPE_PNG){
			$this->quality = $quality;
		}
	}
////////////////////////////////////////////////////////
	 function getInitialFileSize(){	
		return $this->initialfilesize;
	}
////////////////////////////////////////////////////////
//private methods
////////////////////////////////////////////////////////
	function createThumb($thumbnailsize){
		//array elements
		$srcW = $this->imageproperties[0];
		$srcH = $this->imageproperties[1];
		//only adjust if larger than reduction size
		if($srcW >$thumbnailsize || $srcH > $thumbnailsize){
			$reduction = $this->calculateReduction($thumbnailsize);
			//get proportions
  		$desW = $srcW/$reduction;
  		$desH = $srcH/$reduction;								
			$copy = imagecreatetruecolor($desW, $desH);			
			imagecopyresampled($copy,$this->image,0,0,0,0,$desW, $desH, $srcW, $srcH)
				 or die ("Image copy failed.");			
			//destroy original
			imagedestroy($this->image);
			$this->image = $copy;			
		}
	}
////////////////////////////////////////////////////////
	function calculateReduction($thumbnailsize){
		//adjust
		$srcW = $this->imageproperties[0];
		$srcH = $this->imageproperties[1];
  	if($srcW < $srcH){
  		$reduction = round($srcH/$thumbnailsize);
  	}else{  			
  		$reduction = round($srcW/$thumbnailsize);
  	}
		return $reduction;
	}
}//end class
////////////////////////////////////////////////////////
?>

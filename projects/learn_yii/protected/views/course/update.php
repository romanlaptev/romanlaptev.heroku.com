<?php
/*
$this->breadcrumbs=array(
	'Lessons'=>array('index'),
	$model->title=>array('view','id'=>$model->lesson_id),
	'Update',
);
*/
?>

<h1>Edit course "<?php echo $model->title; ?>"</h1>

<?php 
	$this->renderPartial('_form', array('model'=>$model)); 
	echo CHtml::link("Add lesson", array("lesson/create","course_id"=>$model->course_id) );
?>


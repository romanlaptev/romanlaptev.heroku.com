<style>
.table
{
/*background:#2f96b4;*/
/*color: #ffffff;*/
font-size: 150%;
padding: 5px;
}	
.course-action
{
font-size: 75%;
}
</style>
<div class="list-courses">
<h3>List of courses</h3>
	<div>
<p>
	<?php
if ( !Yii::app()->user->isGuest )
{
	echo CHtml::link("add course", array("course/create") );
}
	?>
</p>
	</div>


	<div class="table">
<?php
	foreach ( $courses as $key=>$course )
	{
?>
		<div class="clearfix">
			<div  class="column">
<?php
	echo $key+1;
?>
			</div>
			<div  class="column">
<?php
	echo CHtml::link( $course->title, array("course/view",'id'=>$course->course_id) );
?>
			</div>

<?php
	if ( !Yii::app()->user->isGuest )
	{
?>
			<div  class="column action right">
<?php
	echo CHtml::link( "edit", array("course/update",'id'=>$course->course_id), array('target' => '', 'class' => 'action-link') );
echo " | ";
	echo CHtml::link( "delete course", array("course/delete",'id'=>$course->course_id), array('target' => '', 'class' => 'action-link') );
?>
			</div>
<?php
	}
?>
		</div>

		<div>
<?php
	echo $course->description;
?>
		</div>
<?php
	}//next
?>
	</div>
  
</div>

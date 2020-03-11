<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode(Yii::app()->name); //echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo">
			<h2><a href="<?php echo Yii::app()->baseUrl?>"><?php echo CHtml::encode(Yii::app()->name); ?></a></h2>
		</div>
	</div><!-- header -->
<?php
//echo  Yii::app()->controller->id;
//echo "<br>";
//echo  Yii::app()->controller->action->id;
//echo "<br>";
$visible_course_item = true;
if ( Yii::app()->controller->id == "course" && Yii::app()->controller->action->id == "index" ) {
	$visible_course_item = false;
}
?>
	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				//array('label'=>'Home', 'url'=>array('/site/index'), 'visible'=>Yii::app()->controller->action->id != 'index' ),
array(
	'label'=>'Courses', 
	'url'=>array('/course/index'), 
	'visible'=>$visible_course_item,
/*
	'items'=>array
		(
		  array('label'=>'Добавить курс', 'url'=>array('/course/add')),
		)
*/
),
				array( 'label'=>'Unsort lessons', 'url'=>array('/lesson/list'), 'visible'=>Yii::app()->user->isGuest ),
				array( 'label'=>'Unsort lessons', 'url'=>array('/lesson/admin'), 'visible'=>!Yii::app()->user->isGuest ),
				array( 'label'=>'Export (xml format)', 
						'url'=>array('/export/export'),//, 'view'=>'index'), 
						'visible'=>!Yii::app()->user->isGuest ),

				array( 'label'=>'Import (xml format)', 	'url'=>array('/import/index', 'view'=>'index'), 'visible'=>!Yii::app()->user->isGuest ),

array('label'=>'Users', 'url'=>array('/users'), 'visible'=>!Yii::app()->user->isGuest, 'itemOptions'=>array('class' => 'topmenu-link right') ),

array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest, 'itemOptions'=>array('class' => 'topmenu-link right') ),
array('label'=>'Exit ('.Yii::app()->user->name.')', 
			'url'=>array('/site/logout'), 
			'visible'=>!Yii::app()->user->isGuest, 
			'itemOptions'=>array('class' => 'topmenu-link right'))
			),
		)); ?>
	</div><!-- mainmenu -->

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
			'homeLink'=>false 
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer"></div><!-- footer -->

</div><!-- page -->

</body>
</html>

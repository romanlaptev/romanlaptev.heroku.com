#yiiroot=/mnt/disk2/documents/0_sites/frameworks/yii
yiiroot=/mnt/transcend/php/frameworks/yii

webroot=/mnt/disk2/documents/0_sites/my_sites
#webroot=/mnt/transcend/0_sites

#webapp=blog_yii
#webapp=albums_yii
webapp=app1_yii

cd $webroot
php $yiiroot/framework/yiic.php webapp $webapp


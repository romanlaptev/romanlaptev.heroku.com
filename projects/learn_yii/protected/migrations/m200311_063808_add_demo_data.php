<?php
class m200311_063808_add_demo_data extends CDbMigration
{
	public function up()
	{

		$this->insert('users', array(
		           'login' => 'admin',
		           'pass' => 'super',
		));

		$this->insert('courses', array(
		           'course_id' => '1',
		           'title' => 'Введение в Javascript',
		           'description' => 'Hexlet University',
		));

		$this->insert('courses', array(
		           'course_id' => '2',
		           'title' => 'Уроки Bootstrap верстки',
		           'description' => 'WebDesign Master',
		));

		$this->insert('lessons', array(
		           'course_id' => '1',
		           'url' => 'https://www.youtube.com/embed/r-W_BUG3EbI',
		           'title' => 'JavaScript, лекция 3 Функции. Замыкания',
		           'description' => 'Hexlet University',
		));


		$this->insert('lessons', array(
		           'course_id' => '1',
		           'url' => 'https://www.youtube.com/embed/-wY3IYmQzzc',
		           'title' => 'JavaScript, лекция 4. Наследование',
		           'description' => 'Hexlet University',
		));

		$this->insert('lessons', array(
		           'course_id' => '1',
		           'url' => 'https://www.youtube.com/embed/VCgAvj9meHg',
		           'title' => 'JavaScript, лекция 6 Регулярные выражения',
		           'description' => 'Hexlet University',
		));

		$this->insert('lessons', array(
		           'course_id' => '1',
		           'url' => 'https://www.youtube.com/embed/-jZS8p5_quo',
		           'title' => 'JavaScript, Сравнения, var, eval и заключение',
		           'description' => 'Hexlet University',
		));

		$this->insert('lessons', array(
		           'course_id' => '2',
		           'url' => 'https://www.youtube.com/embed/TZSY6rDUDrE',
		           'title' => 'Основы и установка',
		           'description' => 'Уроки Bootstrap верстки',
		));

		$this->insert('lessons', array(
		           'course_id' => '0',
		           'url' => 'https://www.youtube.com/embed/-wcy1Bsq-Ls',
		           'title' => 'Bootstrap: Как создаются современные адаптивные сайты',
		           'description' => 'WebDesign Master',
		));


		$this->insert('lessons', array(
		           'course_id' => '2',
		           'url' => 'https://www.youtube.com/embed/-A5LxeKi3Aw',
		           'title' => 'Верстка при помощи сеток (Grid-system)',
		           'description' => 'Уроки Bootstrap верстки',
		));

		$this->insert('lessons', array(
		           'course_id' => '2',
		           'url' => 'https://www.youtube.com/embed/EgMIr5gc-sI',
		           'title' => 'Система сеток и адаптивное меню',
		           'description' => 'Уроки Bootstrap верстки',
		));

	}//end up()

	public function down()
	{
		//echo "m150608_053808_create_table_cources does not support migration down.\n";
		//return false;
		$this->truncateTable('courses');
		$this->truncateTable('lessons');
		//$this->truncateTable('users');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}//end class


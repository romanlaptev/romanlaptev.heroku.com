CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `title` varchar(255) NOT NULL DEFAULT 'курс',
  `description` text
);

INSERT INTO `courses` (`course_id`, `title`, `description`) VALUES
(1, 'Javascript', 'Hexlet University'),
(4, 'HTML-верстка на Bootstrap', '');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `lessons` (
  `lesson_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `course_id` INTEGER(6) DEFAULT NULL,
  `url` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL DEFAULT 'урок',
  `description` text
);


INSERT INTO `lessons` (`lesson_id`, `course_id`, `url`, `title`, `description`) VALUES
(1, 1, 'http://mycomp/video/video_lessons/js_HexletUniversity/JavaScript,%20%D0%BB%D0%B5%D0%BA%D1%86%D0%B8%D1%8F%203%20%D0%A4%D1%83%D0%BD%D0%BA%D1%86%D0%B8%D0%B8.%20%D0%97%D0%B0%D0%BC%D1%8B%D0%BA%D0%B0%D0%BD%D0%B8%D1%8F.mp4', 'лекция 3 Функции. Замыкания', 'Hexlet University'),
(2, 1, 'http://mycomp/video/video_lessons/js_HexletUniversity/JavaScript, лекция 4 Наследование.mp4', 'лекция 4. Наследование', 'Hexlet University'),
(5, 1, 'http://mycomp/video/video_lessons/js_HexletUniversity/JavaScript, лекция 6 Регулярные выражения.mp4', 'лекция 6 Регулярные выражения', 'Hexlet University'),
(6, 1, 'http://mycomp/video/video_lessons/js_HexletUniversity/JavaScript, лекция 7 Сравнения, var, eval и заключение.mp4', 'лекция 7 Сравнения, var, eval и заключение', 'Hexlet University'),
(7, 4, 'http://mycomp/video/video_lessons/HTML-верстка на Bootstrap/1 Подключение Bootstrap.mp4', 'Подключение Bootstrap', ''),
(8, 0, 'https://www.youtube.com/embed/-wcy1Bsq-Ls', 'Bootstrap: Как создаются современные адаптивные сайты', 'test2'),
(9, 4, 'http://mycomp/video/video_lessons/HTML-верстка на Bootstrap/2 Container и Row - что это.mp4', 'Container и Row - что это', ''),
(10, 4, 'http://mycomp/video/video_lessons/HTML-верстка на Bootstrap/3 Navbar - разметка навигации для сайта.mp4', 'Navbar - разметка навигации для сайта', '');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` INTEGER(11) DEFAULT NULL
);


INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1433741630),
('m150608_053808_create_table_cources', 1433744571),
('m150608_053808_create_table_lessons', 1433744572),
('m150608_053808_create_table_users', 1433744572),
('m150608_063808_add_demo_data', 1433745007);

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `login` varchar(64) NOT NULL,
  `pass` varchar(64) NOT NULL
);


INSERT INTO `users` (`user_id`, `login`, `pass`) VALUES
(1, 'admin', 'super');


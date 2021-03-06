CREATE TABLE IF NOT EXISTS `term_data` (
  `tid` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `vid` INTEGER NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext,
  `weight` tinyint(4) NOT NULL DEFAULT '0'
);



INSERT INTO `term_data` (`tid`, `vid`, `name`, `description`, `weight`) VALUES
(4, 1, 'Иван Билибин', 'ivan_bilibin.jpg', 6),
(2, 1, 'Альфонс Муха', 'alphonse-mucha.jpg_.jpg', 2),
(216, 5, '1912', '', 33),
(5, 1, 'Обри Бердслей', 'berdsley_self.gif', 0),
(6, 1, 'Густав Климт', 'klimt.jpg', 1),
(7, 1, 'Уотерхаус', 'john-william-waterhouse.jpg', 0),
(8, 1, 'Симберг', 'simberg2.jpg', 3),
(224, 5, '1967', '', 49),
(10, 1, 'Michael Cheval', 'm.cheval.jpg', 4),
(11, 1, 'Anry Nemo', 'anry.jpg', 3),
(220, 5, '1989', '', 51),
(219, 5, 'США', '', 1),
(14, 1, 'художники 15 века', 'francesco_marmitta_-_virgin_and_child.jpg', 0),
(63, 1, 'Allaert Claesz', '', 0),
(17, 1, 'Эрте', 'erte_portrait.jpg', 3),
(226, 5, 'абстракция', '', 0),
(19, 1, 'Хокусай', 'hokusai_portrait.jpg', 0),
(20, 1, 'Рафал Олбински', 'rafal_olbinski_foto.jpg', 5),
(21, 1, 'Джозеф Лейендекер', '220px-jc_leyendecker.jpg\r\n', 3),
(22, 1, 'Питер Брейгель Старший', 'dominicus_lampsonius_-_portrait_of_pieter_bruegel_the_elder.jpg', 6),
(23, 1, 'Бальдунг', 'hans_baldung_self-portrait.jpg', 8),
(24, 1, 'Бургкмайр', 'hans_burgkmair.jpg', 11),
(61, 1, 'Амбергер', 'amberger_christoph.jpg', 7),
(64, 1, 'Йорг Брой Младший', 'breu_jorg_the_elder_1533_portrait_of_a_man.jpg', 3),
(58, 1, 'Альбрехт Дюрер', 'durer.jpg', 5),
(59, 1, 'Мартин Шонгауэр', 'martin_schongauer.jpg', 4),
(60, 1, 'Гольциус', '451px-hendrikgoltziusportrait.jpg', 12),
(83, 1, 'Библия Мациевского', 'bible_maciejowski_agag_is_brought_to_samuel.jpg', 3),
(84, 1, 'Русская миниатюра', 'drevnerusskaya_miniatyura_p5.jpg', 5),
(85, 1, 'карикатура', 'aubrey_beardsley_mendelson_1896.jpg', 61),
(86, 1, 'Манесский кодекс', 'codex_manesse_423_der_kanzler.jpg', 4),
(158, 1, 'художники', 'j.severini.jpg', 69),
(88, 1, 'Вермеер', '535px-johannesvermeer_-_de_koppelaarster.jpg', 0),
(89, 1, 'Менье', '', 2),
(90, 1, 'Комба', '', 1),
(91, 1, 'Петер Беренс', 'peter-behrens-1.jpg', 6),
(65, 1, 'Ганс Себальд Бехам', '250px-portrait_of_sebald_beham.jpg', 2),
(66, 1, 'Японская гравюра', 'cat_japan_pic09114_w300.jpg', 2),
(67, 1, 'Европейская гравюра XV-XVII', 'nikolaus_stor_1525-1540.jpg', 0),
(68, 1, 'Модерн', 'the-trappistine-1897.jpg', 62),
(69, 1, 'Nikolaus Stör', 'nikolaus_stor_1525-1540.jpg', 1),
(72, 1, 'миниатюра', 'how_alexander_the_great_laid_siege_to_the_town_of_tyre.jpg', 23),
(73, 1, 'иллюстрация', 'alice-37.jpg', 53),
(74, 1, 'Джон Тенниел', '220px-john_tenniel00.jpg', 1),
(75, 1, 'плакатная графика', 'steinlein-chatnoirt.-a._steynlen_chyornyy_kot._plakat_odnoimyonnogo_kabare.jpg', 40),
(77, 1, 'Конструктивизм', 'ksssrpost_0024.jpg', 3),
(78, 1, 'Хольвайн, Людвиг', 'hohlwein_portrait_2.jpg', 5),
(231, 1, 'Юлиус Клингер', '', 0),
(80, 1, 'Норман Рокуэлл', 'norman_rockwall.jpg', 4),
(82, 1, 'Стейнлен', 'steinlengat.jpg', 0),
(94, 5, 'стиль', '', 38),
(95, 5, 'жанр', '', 0),
(96, 5, 'техника', '', 65),
(97, 5, 'гравюра', '', 0),
(98, 5, 'ксилография', '', 4),
(99, 5, 'укиё-э', '', 1),
(100, 5, 'миниатюра', '', 9),
(101, 5, 'иллюстрация', '', 8),
(102, 5, 'плакат', '', 10),
(103, 5, 'литография', '', 6),
(104, 5, 'карикатура', '', 11),
(105, 5, 'модерн', '', 13),
(106, 5, 'рисунок', '', 2),
(107, 5, 'сецессион', '', 16),
(108, 5, 'компьютерная графика', 'http://ru.wikipedia.org/wiki/%D0%A6%D0%B8%D1%84%D1%80%D0%BE%D0%B2%D0%B0%D1%8F_%D0%B6%D0%B8%D0%B2%D0%BE%D0%BF%D0%B8%D1%81%D1%8C', 22),
(109, 5, 'Ар Нуво', '', 14),
(110, 5, 'кубизм', '', 17),
(111, 5, 'реклама', '', 7),
(112, 5, 'живопись', '', 1),
(113, 5, 'афиша', '', 6),
(114, 5, 'агитация', '', 1),
(115, 5, 'Art Deco', '', 15),
(116, 5, 'конструктивизм', '', 18),
(117, 1, 'Гарри Кларк', 'photo_harry_clarke.jpg\r\n', 2),
(120, 5, 'романтизм', '', 8),
(119, 5, 'Возрождение', '', 2),
(121, 5, 'символизм', '', 9),
(122, 5, 'акварель', '', 1),
(123, 5, 'масло', '', 0),
(124, 5, 'экспрессионизм', '', 12),
(125, 5, 'сюрреализм', '', 10),
(126, 5, 'неоклассицизм', '', 11),
(129, 5, 'магический реализм', 'http://ru.wikipedia.org/wiki/%D0%9C%D0%B0%D0%B3%D0%B8%D1%87%D0%B5%D1%81%D0%BA%D0%B8%D0%B9_%D1%80%D0%B5%D0%B0%D0%BB%D0%B8%D0%B7%D0%BC', 20),
(128, 5, 'футуризм', '', 19),
(130, 5, 'поп-арт', '', 21),
(131, 5, 'цифровая живопись', 'http://ru.wikipedia.org/wiki/%D0%A6%D0%B8%D1%84%D1%80%D0%BE%D0%B2%D0%B0%D1%8F_%D0%B6%D0%B8%D0%B2%D0%BE%D0%BF%D0%B8%D1%81%D1%8C', 25),
(132, 5, 'фэнтези', '', 5),
(133, 1, 'компьютерная графика', 'cg-art_3d_012.jpg', 48),
(144, 1, 'Гравюра XVIII-XIX', '', 1),
(151, 1, 'Лукас Кранах Старший', '220px-lucas_cranach_d._a._063.jpg', 9),
(136, 1, 'Ютака Кагайя', 'kagaya_portret_2.jpg', 2),
(137, 5, '3D', '', 23),
(138, 1, 'живопись', 'hey_zhan_dofin_karl_orleanskiy.jpg', 30),
(139, 1, 'гравюра', 'nikolaus_stor_1525-1540.jpg', 0),
(140, 5, 'импрессионизм', '', 6),
(141, 1, 'фрактальное искусство', 'satu_oli__spring_in_japan_by_magnusti781.jpg', 1),
(142, 5, 'фрактальное искусство', '', 24),
(143, 1, '3D', 'cg-art_11.jpg', 0),
(145, 1, 'Густав Доре', 'biopic.jpg', 0),
(146, 1, 'Ганс Гольбейн Младший', 'p003.jpg\r\n', 10),
(161, 1, 'Братья Лимбурги', '06_june.jpg', 0),
(148, 1, 'Пляска Смерти', '', 0),
(154, 1, 'Лукас Кранах  Младший', '220px-lucas_cranach_der_jungere.jpg', 1),
(155, 1, 'художники 18-19 веков', 'cleopatra_1888.jpg', 2),
(156, 1, 'Лабурер Жан-Эмиль', 'jean-emile_laboureur_self.jpg', 3),
(157, 1, 'Хроники Жана Фруассара', 'fruassar_j.-p001..jpg', 2),
(160, 1, 'Миниатюры из "Бабур-наме"', 'babur_namah.jpg', 1),
(152, 1, 'Уильям Хогарт', 'william_hogarth_006.jpg', 1),
(153, 1, 'художники 16-17 веков', 'margarita_elizaveta_fon_ansbah-bayroyt_1549.jpg', 1),
(150, 1, 'Жан Гранвиль', 'grandville_jj_selfportrait_02.gif', 0),
(149, 5, 'портрет', '', 4),
(147, 1, 'Ян ван Эйк', 'portrait-of-jan-van-eyck-by-lampsonius.jpg', 0),
(162, 5, 'шелкография', '', 3),
(163, 5, 'сюжет', '', 31),
(164, 5, 'Столетняя война', '', 5),
(240, 5, 'цветная гравюра', '', 0),
(238, 5, '1583', '', 10),
(167, 5, 'Куликовская битва', '', 2),
(168, 5, 'Реформация', '', 4),
(169, 5, 'Первая мировая война', '', 3),
(170, 5, 'офорт', '', 5),
(171, 1, 'Жак Калло', 'jacobus_callot_after_a._van_dyck.jpg', 13),
(172, 5, 'маньеризм', 'https://ru.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D1%8C%D0%B5%D1%80%D0%B8%D0%B7%D0%BC', 7),
(173, 5, 'хромолитография', '', 2),
(182, 5, 'политическая карикатура', '', 0),
(176, 5, 'шарж', '', 12),
(177, 5, 'христианские сюжеты', '', 1),
(179, 5, 'рококо', 'https://ru.wikipedia.org/wiki/%D0%A0%D0%BE%D0%BA%D0%BE%D0%BA%D0%BE', 5),
(178, 5, 'гротеск', '', 2),
(180, 1, 'Иоганн Исайя Нильсон', 'cartouches_modernes_a_compagnes_par_des_enfans.jpeg', 0),
(183, 5, 'классицизм', '', 4),
(184, 5, 'изображение', '', 16),
(185, 5, 'zoom', '', 0),
(186, 5, 'формат', '', 1),
(187, 5, 'календарь', '', 13),
(188, 5, 'страна', '', 19),
(189, 5, 'Россия', '', 8),
(190, 5, 'Германия', '', 4),
(191, 5, 'Франция', '', 9),
(192, 5, 'Италия', '', 5),
(193, 5, 'Англия', '', 0),
(194, 5, 'Япония', '', 10),
(195, 5, 'Китай', '', 6),
(196, 5, 'пейзаж', '', 3),
(197, 5, 'готика', '', 3),
(198, 1, 'Ганс Гольбейн Младший, живопись', '', 0),
(200, 5, 'дата завершения', '', 78),
(201, 5, '1883', '', 20),
(202, 5, '1914', '', 34),
(203, 5, '1815', '', 17),
(204, 5, '1634', '', 13),
(205, 5, '1626', '', 12),
(206, 5, 'Нидерланды', '', 3),
(207, 5, '1991', '', 52),
(208, 5, 'авангард', '', 0),
(209, 5, '1963', '', 48),
(210, 5, 'Швеция', '', 2),
(211, 5, '1890', '', 24),
(212, 5, '1551', '', 9),
(213, 5, '1935', '', 45),
(214, 5, '1919', '', 38),
(215, 5, '1955', '', 47),
(217, 5, '1924', '', 40),
(218, 5, '1950', '', 46),
(221, 5, '1998', '', 56),
(222, 5, '2004', '', 57),
(223, 5, '1994', '', 55),
(225, 5, '1974', '', 50),
(227, 5, '1896', '', 26),
(228, 5, '1904', '', 28),
(229, 5, '1909', '', 31),
(230, 5, '1910', '', 32),
(232, 5, '1475', '', 0),
(233, 5, '1723', '', 14),
(234, 5, '1741', '', 15),
(235, 5, '1926', '', 41),
(236, 5, '1908', '', 30),
(237, 5, '1920', '', 39),
(239, 5, '1587', '', 11),
(241, 5, '1766', '', 16),
(242, 5, '1844', '', 18),
(243, 5, '1934', '', 44),
(244, 5, '1888', '', 21),
(245, 5, '1887', '', 22),
(246, 5, 'историческая живопись', '', 0),
(247, 5, '1882', '', 19),
(248, 5, '1993', '', 53),
(249, 6, 'alphabetical list', '', 0),
(250, 6, 'алфавитный каталог', '', 0),
(251, 6, 'A', '', 0),
(252, 6, 'B', '', 0),
(253, 6, 'C', '', 0),
(254, 6, 'D', '', 0),
(255, 6, 'E', '', 0),
(256, 6, 'F', '', 0),
(257, 6, 'G', '', 0),
(258, 6, 'H', '', 0),
(259, 6, 'I', '', 0),
(260, 6, 'J', '', 0),
(261, 6, 'K', '', 0),
(262, 6, 'L', '', 0),
(263, 6, 'M', '', 0),
(264, 6, 'N', '', 0),
(265, 6, 'O', '', 0),
(266, 6, 'P', '', 0),
(267, 6, 'Q', '', 0),
(268, 6, 'R', '', 0),
(269, 6, 'S', '', 0),
(270, 6, 'T', '', 0),
(271, 6, 'V', '', 0),
(272, 6, 'W', '', 0),
(273, 6, 'Y', '', 0),
(274, 6, 'Z', '', 0),
(275, 6, 'А', '', 0),
(276, 6, 'Б', '', 0),
(277, 6, 'В', '', 0),
(278, 6, 'Г', '', 0),
(279, 6, 'Д', '', 0),
(280, 6, 'Е', '', 0),
(281, 6, 'Ж', '', 0),
(282, 6, 'З', '', 0),
(283, 6, 'И', '', 0),
(284, 6, 'К', '', 0),
(285, 6, 'Л', '', 0),
(286, 6, 'М', '', 0),
(287, 6, 'Н', '', 0),
(288, 6, 'О', '', 0),
(289, 6, 'П', '', 0),
(290, 6, 'Р', '', 0),
(291, 6, 'С', '', 0),
(292, 6, 'Т', '', 0),
(293, 6, 'У', '', 0),
(294, 6, 'Ф', '', 0),
(295, 6, 'Х', '', 0),
(296, 6, 'Ц', '', 0),
(297, 6, 'Ч', '', 0),
(298, 6, 'Ш', '', 0),
(299, 6, 'Щ', '', 0),
(300, 6, 'Э', '', 0),
(301, 6, 'Ю', '', 0),
(302, 6, 'Я', '', 0),
(303, 5, '1903', '', 27),
(304, 5, '1893', '', 25),
(305, 5, '1897', '', 23),
(306, 5, '1510', '', 6),
(307, 5, '1519', '', 8),
(308, 5, '1497', '', 4),
(309, 5, '1495', '', 3),
(310, 5, '1995', '', 54),
(311, 5, '1907', '', 29),
(312, 5, '1916', '', 36),
(313, 5, 'СССР', '', 7),
(314, 5, 'супрематизм', '', 1),
(315, 5, '1915', '', 35),
(316, 5, '1918', '', 37),
(317, 5, '1932', '', 42),
(318, 5, '1933', '', 43),
(319, 5, '1518', '', 7),
(320, 5, '1501', '', 5),
(321, 5, '1488', '', 1),
(322, 5, '1490', '', 2),
(323, 5, '1470', '', 0),
(324, 5, '1441', '', 0),
(325, 5, '1434', '', 0),
(326, 5, '1477', '', 0),
(327, 5, '1481', '', 0);

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `term_hierarchy` (
  `tid` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `parent` INTEGER NOT NULL
);


INSERT INTO `term_hierarchy` (`tid`, `parent`) VALUES
(2, 68),
(4, 73),
(5, 68),
(6, 68),
(7, 155),
(8, 138),
(10, 138),
(11, 133),
(14, 138),
(17, 68),
(19, 66),
(20, 73),
(21, 73),
(22, 67),
(23, 67),
(24, 67),
(58, 67),
(59, 67),
(60, 67),
(61, 67),
(63, 67),
(64, 67),
(65, 67),
(66, 139),
(67, 139),
(68, 0),
(69, 67),
(72, 0),
(73, 0),
(74, 73),
(75, 0),
(77, 75),
(78, 75),
(80, 73),
(82, 75),
(83, 72),
(84, 72),
(85, 0),
(86, 72),
(88, 153),
(89, 75),
(90, 75),
(91, 75),
(94, 0),
(95, 0),
(96, 0),
(97, 96),
(98, 97),
(99, 97),
(100, 95),
(101, 95),
(102, 95),
(103, 97),
(104, 95),
(105, 94),
(106, 96),
(107, 94),
(108, 94),
(109, 94),
(110, 94),
(111, 95),
(112, 96),
(113, 95),
(114, 95),
(115, 94),
(116, 94),
(117, 73),
(119, 94),
(120, 94),
(121, 94),
(122, 112),
(123, 112),
(124, 94),
(125, 94),
(126, 94),
(128, 94),
(129, 94),
(130, 94),
(131, 94),
(132, 95),
(133, 0),
(136, 133),
(137, 94),
(138, 0),
(139, 0),
(140, 94),
(141, 133),
(142, 94),
(143, 133),
(144, 139),
(145, 144),
(146, 67),
(147, 14),
(148, 146),
(149, 95),
(150, 73),
(151, 67),
(152, 144),
(153, 138),
(154, 153),
(155, 138),
(156, 139),
(157, 72),
(158, 0),
(160, 72),
(161, 72),
(162, 97),
(163, 0),
(164, 163),
(167, 163),
(168, 163),
(169, 163),
(170, 97),
(171, 67),
(172, 94),
(173, 97),
(176, 95),
(177, 163),
(178, 95),
(179, 94),
(180, 144),
(182, 104),
(183, 94),
(184, 0),
(185, 184),
(186, 184),
(187, 95),
(188, 0),
(189, 188),
(190, 188),
(191, 188),
(192, 188),
(193, 188),
(194, 188),
(195, 188),
(196, 95),
(197, 94),
(198, 153),
(200, 0),
(201, 200),
(202, 200),
(203, 200),
(204, 200),
(205, 200),
(206, 188),
(207, 200),
(208, 94),
(209, 200),
(210, 188),
(211, 200),
(212, 200),
(213, 200),
(214, 200),
(215, 200),
(216, 200),
(217, 200),
(218, 200),
(219, 188),
(220, 200),
(221, 200),
(222, 200),
(223, 200),
(224, 200),
(225, 200),
(226, 95),
(227, 200),
(228, 200),
(229, 200),
(230, 200),
(231, 75),
(232, 200),
(233, 200),
(234, 200),
(235, 200),
(236, 200),
(237, 200),
(238, 200),
(239, 200),
(240, 97),
(241, 200),
(242, 200),
(243, 200),
(244, 200),
(245, 200),
(246, 163),
(247, 200),
(248, 200),
(249, 0),
(250, 0),
(251, 249),
(252, 249),
(253, 249),
(254, 249),
(255, 249),
(256, 249),
(257, 249),
(258, 249),
(259, 249),
(260, 249),
(261, 249),
(262, 249),
(263, 249),
(264, 249),
(265, 249),
(266, 249),
(267, 249),
(268, 249),
(269, 249),
(270, 249),
(271, 249),
(272, 249),
(273, 249),
(274, 249),
(275, 250),
(276, 250),
(277, 250),
(278, 250),
(279, 250),
(280, 250),
(281, 250),
(282, 250),
(283, 250),
(284, 250),
(285, 250),
(286, 250),
(287, 250),
(288, 250),
(289, 250),
(290, 250),
(291, 250),
(292, 250),
(293, 250),
(294, 250),
(295, 250),
(296, 250),
(297, 250),
(298, 250),
(299, 250),
(300, 250),
(301, 250),
(302, 250),
(303, 200),
(304, 200),
(305, 200),
(306, 200),
(307, 200),
(308, 200),
(309, 200),
(310, 200),
(311, 200),
(312, 200),
(313, 188),
(314, 94),
(315, 200),
(316, 200),
(317, 200),
(318, 200),
(319, 200),
(320, 200),
(321, 200),
(322, 200),
(323, 200),
(324, 200),
(325, 200),
(326, 200),
(327, 200);


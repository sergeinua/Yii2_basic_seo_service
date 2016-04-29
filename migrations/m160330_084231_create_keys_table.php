<?php

use yii\db\Migration;

class m160330_084231_create_keys_table extends Migration
{
    public function up()
    {
        $this->createTable('keys', [
            'id' => $this->integer(11),
            'title' => $this->string(500)->notNull(),
            'status' => $this->integer(1)->notNull(),
            'date_added' => $this->integer(11)->notNull(),
            'date_modified' => $this->integer(11),
        ]);

        $this->execute("
        INSERT INTO `keys` (`id`, `title`, `status`, `date_added`, `date_modified`) VALUES
            (24, 'asdqrqwrfghfgh', 1, 0, 0),
            (25, 'ygkyuk', 1, 0, 0),
            (26, 'new', 1, 0, 0),
            (27, 'the first keyword\r', 1, 0, 0),
            (28, 'the second keyword', 1, 0, 0),
            (29, 'разработка интернет магазина автозапчастей', 1, 0, 0),
            (30, 'сайт под ключ киев', 1, 0, 0),
            (31, 'создание сайтов', 1, 0, 0),
            (32, 'разработка сайтов', 1, 0, 0),
            (33, 'купить кальян\r', 1, 0, 0),
            (34, 'кальян эми\r', 1, 0, 0),
            (35, 'магазин кальянов\r', 1, 0, 0),
            (36, 'кальян amy deluxe\r', 1, 0, 0),
            (37, 'кальяны киев\r', 1, 0, 0),
            (38, 'кальян цены\r', 1, 0, 0),
            (39, 'стеклянные кальяны\r', 1, 0, 0),
            (40, 'купить кальян киев\r', 1, 0, 0),
            (41, 'кальян одуман\r', 1, 0, 0),
            (42, 'купить стеклянный кальян\r', 1, 0, 0),
            (43, 'магазин вэйпов\r', 1, 0, 0),
            (45, 'бонги киев\r', 1, 0, 0),
            (46, 'кальян khalil mamoon\r', 1, 0, 0),
            (47, 'кальян embery\r', 1, 0, 0),
            (48, 'кальян oduman', 1, 0, 0),
            (49, 'кальян км', 1, 0, 0),
            (54, 'Кислородную розетку купить в Украине\r', 1, 0, 0),
            (55, 'Кисневу розетку купити в Україні\r', 1, 0, 0),
            (56, 'Відсмоктувач з акумулятором купити в Україні\r', 1, 0, 0),
            (57, 'Поїльник емальований купити в Україні\r', 1, 0, 0),
            (58, 'Весы РП-150 МГ купить в Украине\r', 1, 0, 0),
            (59, 'Ваги РП-150 МГ купити в Україні\r', 1, 0, 0),
            (60, 'Шприц Брауна купить в Украине\r', 1, 0, 0),
            (61, 'Шини іммобілізаційні надувні купити в Україні\r', 1, 0, 0),
            (62, 'Шини іммобілізаційні вакуумні купити в Україні\r', 1, 0, 0),
            (63, 'ловушки магнитные купить в Украине\r', 1, 0, 0),
            (64, 'Ветеринарний інструментарій купити в Україні\r', 1, 0, 0),
            (65, 'пастки магнітні купити в Україні\r', 1, 0, 0),
            (66, 'зонд магнітний купити в Україні\r', 1, 0, 0),
            (67, 'голки купити в Україні\r', 1, 0, 0),
            (68, 'Шприци ветеринарні купити в Україні\r', 1, 0, 0),
            (69, 'Центрифугу лабораторную Ц-80-1 купить в Украине\r', 1, 0, 0),
            (70, 'Центрифугу лабораторную Ц-80-2А купить в Украине\r', 1, 0, 0),
            (71, 'Центрифугу лабораторную Ц-800-1 купить в Украине\r', 1, 0, 0),
            (72, 'Центрифугу лабораторну Ц-80-1 купити в Україні\r', 1, 0, 0),
            (73, 'Центрифугу лабораторну Ц-80-2А купити в Україні\r', 1, 0, 0),
            (74, 'Центрифугу лабораторну Ц-800-1 купити в Україні\r', 1, 0, 0),
            (75, 'Центрифугу лабораторну Ц-800-Д купити в Україні\r', 1, 0, 0),
            (76, 'Ваги лабораторні з різновагами купити в Україні\r', 1, 0, 0),
            (77, 'Стерилизатор суховоздушный ШС-20 купить в Украине\r', 1, 0, 0),
            (78, 'Стерилізатор сухоповітряний ШС-20 купити в Україні\r', 1, 0, 0),
            (79, 'Бікси купити в Україні\r', 1, 0, 0),
            (80, 'коробки стерилізаційні круглі КСК-12 купити в Україні\r', 1, 0, 0),
            (81, 'коробки стерилізаційні круглі КСК-9 купити в Україні\r', 1, 0, 0),
            (82, 'коробки стерилізаційні круглі КСК-3 купити в Україні\r', 1, 0, 0),
            (83, 'коробки стерилізаційні круглі КСК-6 купити в Україні\r', 1, 0, 0),
            (84, 'коробки стерилізаційні круглі КСК-18 купити в Україні\r', 1, 0, 0),
            (85, 'коробки стерилизационные круглые КСК-12 купить в Украине\r', 1, 0, 0),
            (86, 'Стерилізатор сухоповітряний ГП-20 купити в Україні\r', 1, 0, 0),
            (87, 'Центрифугу лабораторну Ц-80-2 купити в Україні\r', 1, 0, 0),
            (88, 'Аппарат ДТ-50-3 \"Тонус\" купити в Києві та Україні\r', 1, 0, 0),
            (89, 'Аппарат \"Стимул\" купити в Києві та Україні\r', 1, 0, 0),
            (90, 'Аппарат \"Искра-1\" купити в Києві та Україні\r', 1, 0, 0),
            (91, 'комірець Шанса купити в Україні\r', 1, 0, 0),
            (92, 'Кислородную подушку купить в Украине\r', 1, 0, 0),
            (93, 'Кисневу подушку купити в Україні\r', 1, 0, 0),
            (94, 'Кисневий інгалятор купити в Україні\r', 1, 0, 0),
            (95, 'Отсасыватель с аккумулятором купить в Украине\r', 1, 0, 0),
            (96, 'Центрифугу лабораторную Ц-800-Д купить в Украине\r', 1, 0, 0),
            (97, 'Стерилизатор суховоздушный ГП-20 купить в Украине\r', 1, 0, 0),
            (98, 'Аппарат ДТ-50-3 \"Тонус\" купить в Украине\r', 1, 0, 0),
            (99, 'зонд магнитный купить в Украине\r', 1, 0, 0),
            (100, 'Кип’ятильник вогневий П-40 купити в Україні\r', 1, 0, 0),
            (101, 'коробки стерилизационные круглые КСК-3 купить в Украине\r', 1, 0, 0),
            (102, 'коробки стерилизационные круглые КСК-9 купить в Украине\r', 1, 0, 0),
            (103, 'Лабораторне обладнання купити в Україні\r', 1, 0, 0),
            (104, 'Аппарат \"Стимул\" купить в Украине\r', 1, 0, 0),
            (105, 'Протипролежневу подушку купити в Україні\r', 1, 0, 0),
            (106, 'Центрифугу лабораторную Ц-80-2 купить в Украине\r', 1, 0, 0),
            (107, 'кипятильник электрический Э-40 купить в Украине\r', 1, 0, 0),
            (108, 'коробки стерилизационные круглые КСК-6 купить в Украине\r', 1, 0, 0),
            (109, 'Кип’ятильник електричний Е-40 купити в Україні\r', 1, 0, 0),
            (110, 'Кислородный ингалятор купить в Украине\r', 1, 0, 0),
            (111, 'Кружку Эсмарха эмалированную купить в Украине\r', 1, 0, 0),
            (112, 'Кружку Есмарха емальовану купити в Україні\r', 1, 0, 0),
            (113, 'Аппарат УВЧ-30 купити в Україні\r', 1, 0, 0),
            (114, 'Аппарат УВЧ-80 купить в Украине\r', 1, 0, 0),
            (115, 'Аппарат УВЧ-80 купити в Україні\r', 1, 0, 0),
            (116, 'Аппарат \"Искра-1\" купить в Украине\r', 1, 0, 0),
            (117, 'Аппарат УЗТ-101Ф купить в Украине\r', 1, 0, 0),
            (118, 'шприц Шилова купити в Україні\r', 1, 0, 0),
            (119, 'Кипятильник огневой П-40 купить в Украине\r', 1, 0, 0),
            (120, 'Шприц Жане купити в Україні\r', 1, 0, 0),
            (121, 'Весы лабораторные с разновесами купить в Украине\r', 1, 0, 0),
            (122, 'Дистилятор ДЕ-5 купити в Україні\r', 1, 0, 0),
            (123, 'Биксы купить в Украине\r', 1, 0, 0),
            (124, 'ноші купити в Україні\r', 1, 0, 0),
            (125, 'Шприцы ветеринарные купить в Украине\r', 1, 0, 0),
            (126, 'Аппарат УВЧ-66 купити в Україні\r', 1, 0, 0),
            (127, 'Аппарат УВЧ-66 купить в Украине\r', 1, 0, 0),
            (128, 'Аппарат УЗТ-101Ф купити в Україні\r', 1, 0, 0),
            (129, 'коробки стерилизационные круглые КСК-18 купить в Украине\r', 1, 0, 0),
            (130, 'Кисневий концентратор купити в Україні\r', 1, 0, 0),
            (131, 'Шафу сушильну лабораторну купити в Україні\r', 1, 0, 0),
            (132, 'Стерилізатор сухоповітряний ГП-40 купити в Україні\r', 1, 0, 0),
            (133, 'Аппарат УВЧ-30 купить в Украине\r', 1, 0, 0),
            (134, 'Стерилизатор суховоздушный ГП-40 купить в Украине\r', 1, 0, 0),
            (135, 'Шприц Жанэ купить в Украине\r', 1, 0, 0),
            (136, 'Дистиллятор ДЭ-5 купить в Украине\r', 1, 0, 0),
            (137, 'Дистилятор ДЕ-10 купити в Україні\r', 1, 0, 0),
            (138, 'Кисневий балон купити в Україні\r', 1, 0, 0),
            (139, 'Ветеринарный инструментарий купить в Украине\r', 1, 0, 0),
            (140, 'носилки купить в Украине\r', 1, 0, 0),
            (141, 'Баню водяную лабораторную купить в Украине\r', 1, 0, 0),
            (142, 'Протипролежневий круг купити в Україні\r', 1, 0, 0),
            (143, 'Баню водяну лабораторну купити в Україні\r', 1, 0, 0),
            (144, 'Центрифугу лабораторну ОПН-8 купити в Україні\r', 1, 0, 0),
            (145, 'медичне обладнання купити в Україні\r', 1, 0, 0),
            (146, 'Дистилятор ДЕ-25 купити в Україні\r', 1, 0, 0),
            (147, 'Центрифугу лабораторную ОПН-8 купить в Украине\r', 1, 0, 0),
            (148, 'иглы купить в Украине\r', 1, 0, 0),
            (149, 'Весы для взвешивания людей купить в Украине\r', 1, 0, 0),
            (150, 'Дистиллятор ДЭ-25 купить в Украине\r', 1, 0, 0),
            (151, 'Негатоскоп купити в Україні\r', 1, 0, 0),
            (152, 'Кислородный концентратор купить в Украине\r', 1, 0, 0),
            (153, 'Негатоскоп купить в Украине\r', 1, 0, 0),
            (154, 'Ваги для зважування людей купити в Україні\r', 1, 0, 0),
            (155, 'чемодан для скорой помощи купить в Украине\r', 1, 0, 0),
            (156, 'сумку для скорой помощи купить в Украине\r', 1, 0, 0),
            (157, 'стетофонендоскоп Раппопорта купити в Україні\r', 1, 0, 0),
            (158, 'Отсасыватель медицинский купить в Украине\r', 1, 0, 0),
            (159, 'Противопролежневый круг купить в Украине\r', 1, 0, 0),
            (160, 'стетофонендоскоп Раппопорта купить в Украине\r', 1, 0, 0),
            (161, 'Кислородный баллон купить в Украине\r', 1, 0, 0),
            (162, 'Шкаф сушильный лабораторный купить в Украине\r', 1, 0, 0),
            (163, 'воротник Шанса купить в Украине\r', 1, 0, 0),
            (164, 'Стетоскоп купити в Україні\r', 1, 0, 0),
            (165, 'Протипролежневий матрац купити в Україні\r', 1, 0, 0),
            (166, 'Противопролежневую подушку купить в Украине\r', 1, 0, 0),
            (167, 'медицинское оборудование купить в Украине\r', 1, 0, 0),
            (168, 'небулайзер купити в Україні\r', 1, 0, 0),
            (169, 'фонендоскоп купить в Украине\r', 1, 0, 0),
            (170, 'фонендоскоп купити в Україні\r', 1, 0, 0),
            (171, 'інгалятор купити в Україні\r', 1, 0, 0),
            (172, 'небулайзер купить в Украине\r', 1, 0, 0),
            (173, 'Ингалятор купить в Украине\r', 1, 0, 0),
            (174, 'Стетоскоп купить в Украине\r', 1, 0, 0),
            (175, 'Лабораторное оборудование купить в Украине\r', 1, 0, 0),
            (176, 'Дистиллятор ДЭ-10 купить в Украине\r', 1, 0, 0),
            (177, 'Противопролежневый матрас купить в Украине', 1, 0, 0),
            (178, 'efwefwefew\r', 1, 0, 0),
            (179, 'wefwefwef', 1, 0, 0);

        ALTER TABLE `keys`
            ADD PRIMARY KEY (`id`);
        ");
    }

    public function down()
    {
        $this->dropTable('keys');
    }
}

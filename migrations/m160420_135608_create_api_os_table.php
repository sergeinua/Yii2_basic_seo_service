<?php

use yii\db\Migration;

class m160420_135608_create_api_os_table extends Migration
{
    public function up()
    {
        $this->createTable('api_os', [
            'id' => $this->integer(11),
            'visits' => $this->integer(11),
            'os' => $this->string(20),
            'project_id' => $this->integer(11)->notNull(),
            'date' => $this->integer(11)->notNull()
        ]);

        $this->execute("
        INSERT INTO `api_os` (`id`, `visits`, `os`, `project_id`, `date`) VALUES
            (87, 1, 'Bada', 7, 1460981696),
            (88, 1, 'Samsung', 7, 1460981696),
            (89, 2, 'BlackBerry', 7, 1460981697),
            (90, 12, 'Windows Phone', 7, 1460981697),
            (91, 24, '(not set)', 7, 1460981697),
            (92, 42, 'Linux', 7, 1460981697),
            (93, 75, 'Macintosh', 7, 1460981697),
            (94, 150, 'iOS', 7, 1460981697),
            (95, 286, 'Android', 7, 1460981697),
            (96, 2465, 'Windows', 7, 1460981697),
            (97, 1, 'Nokia', 6, 1460982116),
            (98, 1, 'Playstation Portable', 6, 1460982116),
            (99, 1, 'SymbianOS', 6, 1460982116),
            (100, 3, 'BlackBerry', 6, 1460982116),
            (101, 26, '(not set)', 6, 1460982116),
            (102, 36, 'Windows Phone', 6, 1460982116),
            (103, 232, 'Macintosh', 6, 1460982116),
            (104, 250, 'Linux', 6, 1460982116),
            (105, 1556, 'iOS', 6, 1460982116),
            (106, 1645, 'Android', 6, 1460982116),
            (107, 5645, 'Windows', 6, 1460982116),
            (108, 1, 'Chrome OS', 2, 1461679632),
            (109, 8, 'Windows Phone', 2, 1461679632),
            (110, 20, '(not set)', 2, 1461679632),
            (111, 185, 'Linux', 2, 1461679632),
            (112, 192, 'iOS', 2, 1461679632),
            (113, 299, 'Macintosh', 2, 1461679632),
            (114, 369, 'Android', 2, 1461679632),
            (115, 4438, 'Windows', 2, 1461679632);

        ALTER TABLE `api_os`
            ADD PRIMARY KEY (`id`);
        ");
    }

    public function down()
    {
        $this->dropTable('api_os');
    }
}

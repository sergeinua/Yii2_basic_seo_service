<?php

use yii\db\Migration;

class m160422_081530_create_prodvigator_data_table extends Migration
{
    public function up()
    {
        $this->createTable('prodvigator_data', [
            'id' => $this->string(50),
            'domain' => $this->string(50)->notNull(),
            'keywords' => $this->integer(11)->notNull(),
            'traff' => $this->integer(11),
            'new_keywords' => $this->integer(11),
            'out_keywords' => $this->integer(11),
            'rised_keywords' => $this->integer(11),
            'down_keywords' => $this->integer(11),
            'visible' => $this->float(),
            'cost_min' => $this->float(),
            'cost_max' => $this->float(),
            'ad_keywords' => $this->integer(11),
            'ads' => $this->integer(11),
            'date' => $this->string(11),
            'modified_at' => $this->integer(11)->notNull()
        ]);

        $this->execute("
            INSERT INTO `prodvigator_data` (`id`, `domain`, `keywords`, `traff`, `new_keywords`, `out_keywords`, `rised_keywords`, `down_keywords`, `visible`, `cost_min`, `cost_max`, `ad_keywords`, `ads`, `date`, `modified_at`) VALUES
                ('05483d03fcff795039f08e0f640099f9', 'http://medika.kiev.ua/', 257, 163, 67, 3, 27, 3, 0.02738, 0, 0, 0, 0, '2015-10-23', 1460977849),
                ('0b05bc313efad2fbf2348e017a1c026f', 'http://www.reclamare.ua/', 1026, 6780, 15, 27, 24, 57, 1.34105, 12, 21, 46, 22, '2015-05-30', 1461680013),
                ('0fcab97f9961cb23ed5f414f4c22a14d', 'http://medika.kiev.ua/', 74, 3, 17, 0, 9, 7, 0.000494513, 0, 0, 0, 0, '2015-04-20', 1460977849),
                ('154f26f25f3223fd681cc33c265e69b2', 'http://kalyanchik.com.ua/', 83, 0, 60, 0, 0, 0, 0, 0, 0, 0, 0, '2015-11-05', 1461675955),
                ('19481585fc3c32712cb4171d00cce935', 'http://medika.kiev.ua/', 78, 3, 4, 0, 3, 0, 0.000593415, 0, 0, 0, 0, '2015-05-08', 1460977849),
                ('1f142c32a338e4ff48115e7411ecfb60', 'http://www.reclamare.ua/', 1288, 4368, 120, 49, 83, 115, 0.73789, 0, 0, 1, 1, '2015-10-01', 1461680013),
                ('20940919d459a33e28bdc050de46d982', 'http://medika.kiev.ua/', 419, 221, 63, 23, 37, 36, 0.02898, 0, 0, 0, 0, '2016-01-12', 1460977850),
                ('21795cb021d41d5d5a75761ab96fa64d', 'http://kalyanchik.com.ua/', 268, 324, 48, 4, 10, 1, 0.06628, 0, 0, 0, 0, '2016-01-23', 1461675955),
                ('2904579453863c999ec95dfa3aef3b10', 'http://medika.kiev.ua/', 321, 175, 67, 33, 16, 39, 0.02889, 0, 0, 0, 0, '2015-11-28', 1460977849),
                ('317494ff23ab19bbaf7b58332a7a907e', 'http://www.reclamare.ua/', 1390, 3882, 117, 51, 141, 137, 0.64945, 0.33, 0.62, 1, 1, '2015-10-31', 1461680013),
                ('37f3566bb7836cb993713a606c96a498', 'http://www.reclamare.ua/', 1724, 4339, 93, 59, 221, 123, 0.65629, 0.33, 0.62, 1, 1, '2016-01-18', 1461680013),
                ('38f26897e3d51a3ab8f5769ce479ad2a', 'http://www.reclamare.ua/', 1622, 4078, 147, 13, 54, 14, 0.65519, 0.33, 0.62, 1, 1, '2015-12-19', 1461680013),
                ('3ebd51a1b9dfac521f8f791799d464d2', 'http://www.reclamare.ua/', 1735, 6469, 30, 24, 61, 99, 1.01581, 21.2, 41.78, 17, 15, '2016-03-24', 1461680014),
                ('449c6c1b7cbf88e9275c2ea004830cdd', 'http://www.reclamare.ua/', 1488, 4010, 111, 52, 188, 95, 0.66282, 0.33, 0.62, 1, 1, '2015-12-04', 1461680013),
                ('454897bcb3d1a7f6f7ab4103bbf14474', 'http://www.reclamare.ua/', 1690, 4124, 72, 4, 7, 6, 0.63066, 0.33, 0.62, 1, 1, '2016-01-03', 1461680013),
                ('46b085b0f3e3154138f52801146ff424', 'http://medika.kiev.ua/', 481, 335, 30, 7, 15, 8, 0.05429, 0, 0, 0, 0, '2016-02-14', 1460977850),
                ('48bde6396a771b15cbf6e803966a45f6', 'http://www.reclamare.ua/', 1217, 5314, 36, 23, 52, 65, 0.91481, 0.01, 0.01, 14, 9, '2015-09-16', 1461680013),
                ('49ce305f6571724cb96e109d9767c847', 'http://medika.kiev.ua/', 494, 336, 15, 2, 6, 2, 0.05427, 0, 0, 0, 0, '2016-02-29', 1460977850),
                ('49db9d1b32b4c8f29da369f2a05f7d23', 'http://kalyanchik.com.ua/', 534, 14904, 88, 8, 93, 32, 2.88375, 0, 0, 0, 0, '2016-03-28', 1461675955),
                ('53d56151a70f1c15685991ab8abb9f23', 'http://kalyanchik.com.ua/', 159, 12, 35, 0, 0, 0, 0.0019, 0, 0, 0, 0, '2015-12-23', 1461675955),
                ('5bc83aa7f715e242361f401c10a825ee', 'http://www.reclamare.ua/', 991, 4810, 36, 22, 52, 46, 0.940328, 0, 0, 36, 18, '2015-07-30', 1461680013),
                ('64e4fcde50607189a0019308a067b33e', 'http://www.reclamare.ua/', 1429, 3934, 93, 54, 100, 84, 0.65039, 0.33, 0.62, 1, 1, '2015-11-16', 1461680013),
                ('70f7254b9b7d949e6ea4788694748116', 'http://medika.kiev.ua/', 642, 1158, 139, 18, 105, 49, 0.18956, 0, 0, 0, 0, '2016-04-17', 1460977850),
                ('7108f5aa87780d622ef2f20e9ea8e306', 'http://medika.kiev.ua/', 193, 156, 36, 3, 15, 7, 0.02628, 0, 0, 0, 0, '2015-10-06', 1460977849),
                ('7695d294268bdad59d9a92fbe2418178', 'http://medika.kiev.ua/', 79, 4, 1, 0, 3, 2, 0.000791223, 0, 0, 0, 0, '2015-05-30', 1460977849),
                ('7a3270922a8f15dcd937651b82e68a7f', 'http://kalyanchik.com.ua/', 377, 5344, 110, 1, 47, 4, 1.13525, 0, 0, 0, 0, '2016-02-10', 1461675955),
                ('8125d68fe014b2da9d4e09e201661576', 'http://www.reclamare.ua/', 1741, 5118, 76, 59, 152, 88, 0.82794, 0.33, 0.62, 1, 1, '2016-02-02', 1461680013),
                ('83a6fc4149127031979136533f97fa39', 'http://www.reclamare.ua/', 1038, 6822, 23, 62, 46, 75, 1.34952, 14, 24, 55, 26, '2015-05-09', 1461680013),
                ('8f87aa8e1d63661e5d00121ce7d845a7', 'http://kalyanchik.com.ua/', 420, 5373, 44, 1, 19, 1, 1.13484, 0, 0, 0, 0, '2016-02-26', 1461675955),
                ('972be4aea9ea7da8a86750921e02c022', 'http://medika.kiev.ua/', 287, 175, 38, 8, 3, 7, 0.02893, 0, 0, 0, 0, '2015-11-12', 1460977849),
                ('a44744cd5476702e14f086913a8abf95', 'http://medika.kiev.ua/', 509, 369, 35, 20, 44, 54, 0.05651, 0, 0, 0, 0, '2016-03-17', 1460977850),
                ('a7d49faa711103b9671ce9ad5fdd1123', 'http://medika.kiev.ua/', 160, 142, 37, 3, 17, 9, 0.02436, 0, 0, 0, 0, '2015-09-20', 1460977849),
                ('adfe82ee9c3b8385bd39c2d48020c4e8', 'http://medika.kiev.ua/', 521, 601, 20, 8, 36, 26, 0.09333, 0, 0, 0, 0, '2016-04-02', 1460977850),
                ('b046d9a296499b6187b75196d7d9f5c9', 'http://kalyanchik.com.ua/', 609, 14873, 79, 4, 34, 13, 2.82969, 0, 0, 0, 0, '2016-04-13', 1461675955),
                ('b06b0dc4c4f8edb1fbb876a1be89dd0d', 'http://www.reclamare.ua/', 1780, 6340, 98, 78, 223, 271, 0.92307, 7.9, 15.44, 20, 19, '2016-04-24', 1461680014),
                ('b0d88364a90e38bea858d00ec356a965', 'http://www.reclamare.ua/', 1757, 5082, 73, 57, 81, 70, 0.83072, 0.33, 0.62, 1, 1, '2016-02-23', 1461680014),
                ('b2f9669d3a35f6a6b0715d866ee7a0b6', 'http://medika.kiev.ua/', 344, 179, 24, 1, 3, 1, 0.0291, 0, 0, 0, 0, '2015-12-13', 1460977849),
                ('b36296e9fbe3569dc15ed3b2430acb27', 'http://www.reclamare.ua/', 1324, 4609, 51, 15, 62, 56, 0.77656, 0, 0, 1, 1, '2015-10-16', 1461680013),
                ('ba552527a99858ca21a196c18cf0332f', 'http://kalyanchik.com.ua/', 454, 10122, 69, 35, 75, 23, 2.13538, 0, 0, 0, 0, '2016-03-13', 1461675955),
                ('bd892a33a9ce9e52c4cae038f56b3560', 'http://www.reclamare.ua/', 1760, 6899, 92, 67, 129, 190, 1.06392, 21.2, 41.78, 22, 20, '2016-04-09', 1461680014),
                ('c28f5a2c52c47c9ca58145171a32e015', 'http://www.reclamare.ua/', 1729, 3688, 57, 85, 70, 111, 0.51516, 19.59, 38.69, 12, 11, '2016-03-09', 1461680014),
                ('c698b534950c5fbcef4017c58962bb19', 'http://kalyanchik.com.ua/', 23, 0, 23, 0, 0, 0, 0, 0, 0, 0, 0, '2015-10-19', 1461675955),
                ('cb8d8515779d9c03d9901e79d2c345ab', 'http://medika.kiev.ua/', 106, 97, 2, 1, 8, 3, 0.0189636, 0, 0, 0, 0, '2015-07-29', 1460977849),
                ('dad22369b013af3c6e5a0c51545b7546', 'http://kalyanchik.com.ua/', 98, 0, 15, 0, 0, 1, 0, 0, 0, 0, 0, '2015-11-21', 1461675955),
                ('db3c6ef1e490707ee4bfe5e7f6075f87', 'http://kalyanchik.com.ua/', 124, 1, 27, 1, 4, 0, 0.00017, 0, 0, 0, 0, '2015-12-07', 1461675955),
                ('e9fef7f92869a7446e450501c634a13e', 'http://medika.kiev.ua/', 105, 3, 7, 1, 10, 1, 0.000494514, 0, 0, 0, 0, '2015-07-14', 1460977849),
                ('f0b02e194a4bbae343a2d749ba3e676a', 'http://medika.kiev.ua/', 379, 190, 36, 1, 0, 0, 0.02898, 0, 0, 0, 0, '2015-12-28', 1460977849),
                ('f13f7adb79bd04576b29140ec24d4b65', 'http://medika.kiev.ua/', 99, 2, 21, 1, 11, 0, 0.000395611, 0, 0, 0, 0, '2015-06-28', 1460977849),
                ('f241a0bbfb383159432d95bdbbca9a83', 'http://medika.kiev.ua/', 458, 223, 52, 13, 32, 24, 0.02898, 0, 0, 0, 0, '2016-01-27', 1460977850),
                ('f69184bb186149a6fa757cb09360e371', 'http://kalyanchik.com.ua/', 224, 147, 65, 0, 2, 2, 0.02787, 0, 0, 0, 0, '2016-01-08', 1461675955),
                ('fb5746599ee228c4e758c81e609385be', 'http://www.reclamare.ua/', 1204, 5261, 291, 78, 169, 220, 0.90569, 0.01, 0.02, 21, 11, '2015-09-01', 1461680013),
                ('fe77dced1668ef1abdeea3db90864241', 'http://www.reclamare.ua/', 977, 6164, 32, 40, 31, 84, 1.21934, 0, 0, 42, 21, '2015-07-15', 1461680013),
                ('fec48cf65aa46e020ff9d7430fed03f6', 'http://medika.kiev.ua/', 126, 132, 29, 9, 25, 26, 0.02264, 0, 0, 0, 0, '2015-09-04', 1460977849);

                ALTER TABLE `prodvigator_data`
                    ADD PRIMARY KEY (`id`);
        ");
    }

    public function down()
    {
        $this->dropTable('prodvigator_data');
    }
}

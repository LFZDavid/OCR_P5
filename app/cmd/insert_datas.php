<?php
require 'config.php';

for ($i = 0; $i < 10; $i++) {
    $randD = rand(1, 28);
    $randM = rand(1, 12);
    $randY = rand(2000, 2020);
    $date = new \DateTime();
    $date->setDate($randY, $randM, $randD);
    $date->setTime(0, 0);
    $randomDates[] = $date->format('Y-m-d H:i:s');
}

try {
    $db = new \PDO("mysql:host=" . $config['db_host'] . ";dbname=" . $config['db_name'] . ";charset=utf8", $config['db_user'], $config['db_pwd']);
    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    // $db->query($db_insert_demo_datas_query);
    // Insert users
    $db->exec("
    INSERT INTO `blog_p5`.`users` (
        `name`,
        `email`,
        `pwd`,
        `role`,
        `created_at`
        )
        VALUES('admin','admin@admin.com','$2y$10\$oiNmLZMeZwUKDAU6H0VFIedFx6uBp3d4kulH75XEAf6Zup9flINXW','admin','" . $randomDates[rand(1, 9)] . "'),
        ('Tony','stark@gmail.com','$2y$10\$OMo8C72.I0iyyHZr.WvXhOgY/55zMqMYVf3ESVuGWFmBmWWPSw8DO','user','" . $randomDates[rand(1, 9)] . "'),
        ('Steve','rodgers@gmail.com','$2y$10\$OMo8C72.I0iyyHZr.WvXhOgY/55zMqMYVf3ESVuGWFmBmWWPSw8DO','user','" . $randomDates[rand(1, 9)] . "'),
        ('Bruce','banner@gmail.com','$2y$10\$OMo8C72.I0iyyHZr.WvXhOgY/55zMqMYVf3ESVuGWFmBmWWPSw8DO','user','" . $randomDates[rand(1, 9)] . "'),
        ('Natasha','romanoff@gmail.com','$2y$10\$OMo8C72.I0iyyHZr.WvXhOgY/55zMqMYVf3ESVuGWFmBmWWPSw8DO','user','" . $randomDates[rand(1, 9)] . "'),
        ('Thor','odinsson@gmail.com','$2y$10\$OMo8C72.I0iyyHZr.WvXhOgY/55zMqMYVf3ESVuGWFmBmWWPSw8DO','user','" . $randomDates[rand(1, 9)] . "')
            
        ");
    // Insert posts
    $db->exec("INSERT INTO `blog_p5`.`posts`(
        `title`,
        `chapo`,
        `content`,
        `id_author`,
        `active`,
        `created_at`
        )
        VALUES('Lorem','Letraset sheets containing','Letraset sheets containing Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more', 1,1,'" . $randomDates[rand(1, 9)] . "'),
        ('Letraset','containing Lorem I','Letraset sheets containing Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more',1,1,'" . $randomDates[rand(1, 9)] . "'),
        ('sheets','Ipsum passages','Letraset sheets containing Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more',1,1,'" . $randomDates[rand(1, 9)] . "'),
        ('containing','Letraset containing','Letraset sheets containing Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more',1,1,'" . $randomDates[rand(1, 9)] . "'),
        ('passages','Ipsum passages, and more Letraset','Letraset sheets containing Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more', 1,1,'" . $randomDates[rand(1, 9)] . "'),
        ('more Letraset','Ipsum p Letraset sheets containing',' Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more', 1,0,'" . $randomDates[rand(1, 9)] . "')");

    // Insert comments
    $db->exec("INSERT INTO `blog_p5`.`comments`(
        `content`,
        `id_author`,
        `id_post`,
        `active`,
        `created_at`
        )
        VALUES('Ipsum p Letraset sheets containing',1,1,1,'" . $randomDates[rand(1, 9)] . "'),
            ('Letraset sheets containing',1,2,1,'" . $randomDates[rand(1, 9)] . "'),
            ('Iontaining',1,3,0,'" . $randomDates[rand(1, 9)] . "'),
            ('Lorem Ipsu Lontaining sdfsmzerz',1,3,1,'" . $randomDates[rand(1, 9)] . "'),
            ('Lpsum passages, and more etraset containing',1,3,1,'" . $randomDates[rand(1, 9)] . "'),
            ('E etraset contaiLpsum passages, and more psum passages, and more etraset containing',1,1,1,'" . $randomDates[rand(1, 9)] . "')");

    $db->exec("INSERT INTO `blog_p5`.`category_post`(
        `id_post`,
        `id_category`
    ) VALUES(1,1),(1,2),(2,1),(3,2),(4,3),(4,5),(4,6)
    ");
} catch (\PDOException $e) {
    echo 'La connexion à échoué.<br/>';
    echo 'Information : [', $e->getCode(), '] ', $e->getMessage();
}

<?php
require 'config.php';

try{
    $db = new \PDO("mysql:host=" .$config['db_host']. ";dbname=" .$config['db_name'].";charset=utf8",$config['db_user'],$config['db_pwd']);
    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    // $db->query($db_insert_demo_datas_query);
    // Insert users
    $db->exec("INSERT INTO `blog_p5`.`users` (
        `name`,
        `email`,
        `pwd`
        )
        VALUES('admin','admin@admin.com','test')");
    // Insert posts
    $db->exec("INSERT INTO `blog_p5`.`posts`(
        `title`,
        `chapo`,
        `content`,
        `id_author`,
        `active`
        )
        VALUES('Lorem','Letraset sheets containing','Letraset sheets containing Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more', 1,1),
        ('Letraset','containing Lorem I','Letraset sheets containing Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more',1,1),
        ('sheets','Ipsum passages','Letraset sheets containing Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more',1,1),
        ('containing','Letraset containing','Letraset sheets containing Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more',1,1),
        ('passages','Ipsum passages, and more Letraset','Letraset sheets containing Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more', 1,1),
        ('more Letraset','Ipsum p Letraset sheets containing',' Lorem Ipsum passages, and more Letraset sheets containing Lorem Ipsum passages, and more', 1,0)");

    // Insert comments
    $db->exec("INSERT INTO `blog_p5`.`comments`(
        `content`,
        `id_author`,
        `id_post`,
        `active`
        )
        VALUES('Ipsum p Letraset sheets containing',1,1,1),
            ('Letraset sheets containing',1,2,1),
            ('Iontaining',1,3,0),
            ('Lorem Ipsu Lontaining sdfsmzerz',1,3,1),
            ('Lpsum passages, and more etraset containing',1,3,1),
            ('E etraset contaiLpsum passages, and more psum passages, and more etraset containing',1,1,1)");

    // Insert categories
    $db->exec("INSERT INTO `blog_p5`.`categories`(
        `name`
    ) VALUES('new'),('Lorem'),('Ipsum'),('Letraset'),('passages'),('sheets')
    ");
    // Link categories to post
    $db->exec("INSERT INTO `blog_p5`.`category_post`(
        `id_post`,
        `id_category`
    ) VALUES(1,1),(1,2),(2,1),(3,2),(4,3),(4,5),(4,6)
    ");


}
catch (\PDOException $e){
    echo 'La connexion à échoué.<br/>';
    echo 'Information : [', $e->getCode(), '] ', $e->getMessage();
}


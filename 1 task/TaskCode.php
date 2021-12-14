<?php


class TaskCode {
    private $con;

    public function __construct() {
        $db = $this->con = mysqli_connect(
            "localhost",
            "root",
            "123123",
            "database"
        );

        mysqli_query($db,
            "CREATE TABLE `users` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) DEFAULT NULL,
                `gender` INT(11) NOT NULL COMMENT '0 - не указан, 1 - мужчина, 2 - женщина.',
                `birth_date` INT(11) NOT NULL COMMENT 'Дата в unixtime.',
              PRIMARY KEY (`id`)
            );"
        );

        mysqli_query($db,
            "CREATE TABLE `phone_numbers` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `phone` VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
            );"
        );
    }

    /**
     * Напишите запрос, возвращающий имя и число указанных телефонных номеров девушек в возрасте от 18 до 22 лет.
     *
     * @return array
     */
    public function getFemalesBetween18and22(): array {
        $result = mysqli_fetch_all(mysqli_query($this->con,
            "SELECT u.name, COUNT(pn.id) AS count_of_phone_numbers
                    FROM phone_numbers AS pn
                    LEFT JOIN users AS u ON u.id = pn.user_id 
                    WHERE gender = 2 AND 
                          birth_date 
                              BETWEEN 
                                UNIX_TIMESTAMP(NOW() - INTERVAL 22 YEAR) AND UNIX_TIMESTAMP(NOW() - INTERVAL 18 YEAR) 
                    GROUP BY pn.user_id;"
        ));

        mysqli_close($this->con);

        return $result;
    }
}
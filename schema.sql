CREATE DATABASE IF NOT EXISTS `practicum4` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `practicum4`;

CREATE TABLE IF NOT EXISTS `tickets` (
                                         `id` INT AUTO_INCREMENT PRIMARY KEY,
                                         `subject` VARCHAR(255) NOT NULL,
    `priority` VARCHAR(50) NOT NULL DEFAULT 'normal',
    `status` VARCHAR(50) NOT NULL DEFAULT 'open'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tickets` (`subject`, `priority`, `status`) VALUES
                                                            ('Не працює вхід в систему', 'urgent', 'open'),
                                                            ('Запит на оновлення пароля', 'normal', 'closed'),
                                                            ('Помилка при оплаті замовлення', 'urgent', 'open'),
                                                            ('Питання щодо нових тарифів', 'low', 'closed');
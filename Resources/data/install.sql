-- php bin/console doctrine:schema:update --dump-sql
CREATE TABLE IF NOT EXISTS `PREFIX_treatment` (id_treatment INT AUTO_INCREMENT NOT NULL, id_product INT UNSIGNED NOT NULL, id_attribute INT UNSIGNED DEFAULT NULL, name VARCHAR(128) NOT NULL, code VARCHAR(128) NOT NULL, price INT NOT NULL, active TINYINT(1) NOT NULL, deleted TINYINT(1) NOT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, PRIMARY KEY(id_treatment)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

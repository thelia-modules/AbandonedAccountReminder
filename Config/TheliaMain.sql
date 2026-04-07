
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- abandoned_account_reminder
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `abandoned_account_reminder`;

CREATE TABLE `abandoned_account_reminder`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `customer_id` INTEGER NOT NULL,
    `customer_email` VARCHAR(255),
    `locale` VARCHAR(5),
    `status` INTEGER(1) DEFAULT 0,
    `last_update` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `fi_abandoned_account_customer_id` (`customer_id`),
    CONSTRAINT `fk_abandoned_account_customer_id`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;

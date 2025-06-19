<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_tbldevices extends CI_Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `tbldevices` (
              `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
              `client_id` INT UNSIGNED NOT NULL,
              `contact_id` INT UNSIGNED DEFAULT NULL,
              `type` VARCHAR(100) DEFAULT NULL,
              `name` VARCHAR(150) DEFAULT NULL,
              `model` VARCHAR(150) DEFAULT NULL,
              `serial_number` VARCHAR(150) DEFAULT NULL,
              `device_password` VARCHAR(100) DEFAULT NULL,
              `description` TEXT DEFAULT NULL,
              `status` VARCHAR(50) DEFAULT 'Active',
              `last_maintenance` DATE DEFAULT NULL,
              `date_added` DATETIME DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              KEY `client_id` (`client_id`),
              KEY `contact_id` (`contact_id`),
              CONSTRAINT `fk_device_client` FOREIGN KEY (`client_id`) REFERENCES `tblclients`(`userid`) ON DELETE CASCADE,
              CONSTRAINT `fk_device_contact` FOREIGN KEY (`contact_id`) REFERENCES `tblcontacts`(`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `tbl_devices`;");
    }
}

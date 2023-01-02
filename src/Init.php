<?php

namespace Aponahmed\Uttaragedget\src;

/**
 * Description of init
 *
 * @author Apon
 */
class Init
{

    public static function active_plugin()
    {
        self::initDB();
    }

    public static function initDB()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //----------------------------------------------

        //Customer Table Creation
        $tableNameCustomer = "{$wpdb->prefix}customers";
        $customerTableSql = "CREATE TABLE IF NOT EXISTS `$tableNameCustomer` (
        `id` INT NOT NULL AUTO_INCREMENT , 
        `name` VARCHAR(256) NOT NULL , 
        `mobile` VARCHAR(20) NOT NULL , 
        `email` VARCHAR(256) NOT NULL , 
        `address` TEXT NOT NULL , 
        `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
        PRIMARY KEY (`id`), 
        UNIQUE `Email` (`email`), 
        UNIQUE `Mobile` (`mobile`)
        ) $charset_collate;";
        dbDelta($customerTableSql);

        //Sales Table Creation
        $tableNameSales = "{$wpdb->prefix}sales";
        $salesTableSql = "CREATE TABLE IF NOT EXISTS `$tableNameSales` ( 
            `id` INT NOT NULL AUTO_INCREMENT , 
            `customer_id` INT NOT NULL , 
            `sales_value` FLOAT NULL DEFAULT NULL , 
            `sales_value_receive` FLOAT NULL DEFAULT NULL , 
            `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
            PRIMARY KEY (`id`)
            ) $charset_collate;";
        dbDelta($salesTableSql);

        //Sales  Item Table
        $tableNameSalesItem = "{$wpdb->prefix}sales_items";
        $salesItemTableSql = "CREATE TABLE IF NOT EXISTS  `$tableNameSalesItem` ( 
            `id` INT NOT NULL AUTO_INCREMENT , 
            `sales_id` INT NOT NULL , 
            `product_id` INT NOT NULL , 
            `unit_price` FLOAT NOT NULL , 
            `qualtity` INT NOT NULL , 
            PRIMARY KEY (`id`),
            FOREIGN KEY (`sales_id`) REFERENCES `$tableNameSales`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) $charset_collate;";
        dbDelta($salesItemTableSql);

        //Exchange Table
        $tableNameExchange = "{$wpdb->prefix}exchange";
        $exchangeTableSql = "CREATE TABLE IF NOT EXISTS `$tableNameExchange` ( 
            `id` INT NOT NULL AUTO_INCREMENT , 
            `name` VARCHAR(256) NULL , 
            `mobile_number` VARCHAR(20) NOT NULL , 
            `email_address` VARCHAR(256) NULL , 
            `product_type` ENUM('Mobile','Tab','Laptop','Accessories') NULL DEFAULT NULL , 
            `type` ENUM('Sale','Exchange') NOT NULL , 
            `details_referance` TEXT  NULL , 
            `midia_referance` TEXT  NULL , 
            `status` ENUM('Pending','Processing','Deal Complete','Deal canceled') NOT NULL DEFAULT 'Pending' , 
            `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
            PRIMARY KEY (`id`)
            ) $charset_collate;";
        dbDelta($exchangeTableSql);
    }
}

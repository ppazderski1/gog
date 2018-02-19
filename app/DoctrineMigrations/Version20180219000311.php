<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180219000311 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("
        CREATE PROCEDURE updatePriceActivity()
            BEGIN

                SET SQL_SAFE_UPDATES=0;
            
                UPDATE `price` SET `is_active` = 0 WHERE `is_active` = 1;
            
                UPDATE 
                    `price` `p` 
                INNER JOIN 
                    (SELECT MAX(id) as `maxID` FROM gog.price  
                    WHERE `valid_from` < NOW() AND (`valid_to` > NOW() OR `valid_to` IS null) 
                    GROUP BY `product_id`, `currency_zone_id`) `p1`
                ON 
                    `p`.`id` = `p1`.`maxID`
                SET 
                    `is_active` = 1;
            
                SET SQL_SAFE_UPDATES=1;
            END;
        ");

        $this->addSql("
        CREATE EVENT updatePriceActivityEvent
            ON SCHEDULE EVERY 10 SECOND
            DO
              CALL updatePriceActivity();
        ");
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("DROP EVENT updatePriceActivityEvent;");
        $this->addSql("DROP PROCEDURE updatePriceActivity;");

    }
}

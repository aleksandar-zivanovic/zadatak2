<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231105175825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398E1E0DDFB');
        $this->addSql('DROP INDEX IDX_F5299398E1E0DDFB ON `order`');
        $this->addSql('ALTER TABLE `order` DROP ordered_items_id, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ordered_item DROP created_at, DROP updated_at, DROP price_per_unit, DROP subtotal, CHANGE quantity related_order_id INT NOT NULL');
        $this->addSql('ALTER TABLE ordered_item ADD CONSTRAINT FK_6927A43E2B1C2395 FOREIGN KEY (related_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_6927A43E2B1C2395 ON ordered_item (related_order_id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD27D5C145');
        $this->addSql('DROP INDEX IDX_D34A04AD27D5C145 ON product');
        $this->addSql('ALTER TABLE product DROP ordered_item_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD ordered_items_id INT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398E1E0DDFB FOREIGN KEY (ordered_items_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_F5299398E1E0DDFB ON `order` (ordered_items_id)');
        $this->addSql('ALTER TABLE ordered_item DROP FOREIGN KEY FK_6927A43E2B1C2395');
        $this->addSql('DROP INDEX IDX_6927A43E2B1C2395 ON ordered_item');
        $this->addSql('ALTER TABLE ordered_item ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD price_per_unit DOUBLE PRECISION NOT NULL, ADD subtotal DOUBLE PRECISION NOT NULL, CHANGE related_order_id quantity INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD ordered_item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD27D5C145 FOREIGN KEY (ordered_item_id) REFERENCES ordered_item (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD27D5C145 ON product (ordered_item_id)');
    }
}

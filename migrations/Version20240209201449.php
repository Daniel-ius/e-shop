<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209201449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE cart_items ADD quantity INT DEFAULT 1 NOT NULL, ADD total_price DOUBLE PRECISION NOT NULL, ADD item_id INT DEFAULT NULL, ADD carts_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF48445126F525E FOREIGN KEY (item_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF48445BCB5C6F5 FOREIGN KEY (carts_id) REFERENCES carts (id)');
        $this->addSql('CREATE INDEX IDX_BEF48445126F525E ON cart_items (item_id)');
        $this->addSql('CREATE INDEX IDX_BEF48445BCB5C6F5 ON cart_items (carts_id)');
        $this->addSql('ALTER TABLE carts ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD status VARCHAR(255) NOT NULL, ADD total DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE orders_history ADD user_id INT DEFAULT NULL, ADD cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_history ADD CONSTRAINT FK_D6CF0230A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE orders_history ADD CONSTRAINT FK_D6CF02301AD5CDBF FOREIGN KEY (cart_id) REFERENCES carts (id)');
        $this->addSql('CREATE INDEX IDX_D6CF0230A76ED395 ON orders_history (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D6CF02301AD5CDBF ON orders_history (cart_id)');
        $this->addSql('ALTER TABLE products ADD price DOUBLE PRECISION NOT NULL, ADD description LONGTEXT NOT NULL, ADD name VARCHAR(255) NOT NULL, ADD images VARCHAR(255) DEFAULT NULL, ADD categories_id INT NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AA21214B7 ON products (categories_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categories');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AA21214B7');
        $this->addSql('DROP INDEX IDX_B3BA5A5AA21214B7 ON products');
        $this->addSql('ALTER TABLE products DROP price, DROP description, DROP name, DROP images, DROP categories_id');
        $this->addSql('ALTER TABLE carts DROP created_at, DROP updated_at, DROP status, DROP total');
        $this->addSql('ALTER TABLE orders_history DROP FOREIGN KEY FK_D6CF0230A76ED395');
        $this->addSql('ALTER TABLE orders_history DROP FOREIGN KEY FK_D6CF02301AD5CDBF');
        $this->addSql('DROP INDEX IDX_D6CF0230A76ED395 ON orders_history');
        $this->addSql('DROP INDEX UNIQ_D6CF02301AD5CDBF ON orders_history');
        $this->addSql('ALTER TABLE orders_history DROP user_id, DROP cart_id');
        $this->addSql('ALTER TABLE cart_items DROP FOREIGN KEY FK_BEF48445126F525E');
        $this->addSql('ALTER TABLE cart_items DROP FOREIGN KEY FK_BEF48445BCB5C6F5');
        $this->addSql('DROP INDEX IDX_BEF48445126F525E ON cart_items');
        $this->addSql('DROP INDEX IDX_BEF48445BCB5C6F5 ON cart_items');
        $this->addSql('ALTER TABLE cart_items DROP quantity, DROP total_price, DROP item_id, DROP carts_id');
    }
}

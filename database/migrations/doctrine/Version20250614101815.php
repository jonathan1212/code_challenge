<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250614101815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE addresses (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, street_name VARCHAR(255) NOT NULL, street_number INT NOT NULL, city VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6FCA75169395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE coordinates (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, latitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9816D676F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, gender VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, cell VARCHAR(255) NOT NULL, nat VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_62534E21E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE addresses ADD CONSTRAINT FK_6FCA75169395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coordinates ADD CONSTRAINT FK_9816D676F5B7AF75 FOREIGN KEY (address_id) REFERENCES addresses (id)
        SQL);

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql(<<<'SQL'
            ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA75169395C3F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE coordinates DROP FOREIGN KEY FK_9816D676F5B7AF75
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE addresses
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE coordinates
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE customers
        SQL);
    }
}

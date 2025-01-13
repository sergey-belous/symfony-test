<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113185959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rate_service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, cost NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_offer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, rate_service_id INT NOT NULL, date_created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', email VARCHAR(255) NOT NULL, INDEX IDX_80A46AD9A76ED395 (user_id), INDEX IDX_80A46AD947F7E3FD (rate_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service_offer ADD CONSTRAINT FK_80A46AD9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service_offer ADD CONSTRAINT FK_80A46AD947F7E3FD FOREIGN KEY (rate_service_id) REFERENCES rate_service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service_offer DROP FOREIGN KEY FK_80A46AD9A76ED395');
        $this->addSql('ALTER TABLE service_offer DROP FOREIGN KEY FK_80A46AD947F7E3FD');
        $this->addSql('DROP TABLE rate_service');
        $this->addSql('DROP TABLE service_offer');
    }
}

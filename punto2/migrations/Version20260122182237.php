<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260122182237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contracts (id VARCHAR(255) NOT NULL, date DATETIME NOT NULL, total_amount DOUBLE PRECISION NOT NULL, months INT NOT NULL, payment_method VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE installments (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, due_date DATETIME NOT NULL, contract_id VARCHAR(255) NOT NULL, INDEX IDX_FE90068C2576E0FD (contract_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE installments ADD CONSTRAINT FK_FE90068C2576E0FD FOREIGN KEY (contract_id) REFERENCES contracts (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE installments DROP FOREIGN KEY FK_FE90068C2576E0FD');
        $this->addSql('DROP TABLE contracts');
        $this->addSql('DROP TABLE installments');
    }
}

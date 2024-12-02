<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127195916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE books (uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, total_quantity SMALLINT NOT NULL, available_quantity SMALLINT NOT NULL, author_first_name VARCHAR(255) NOT NULL, author_last_name VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('COMMENT ON COLUMN books.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE clients (uuid UUID NOT NULL, name TEXT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('COMMENT ON COLUMN clients.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE reservations (uuid UUID NOT NULL, book_id UUID NOT NULL, client_id UUID NOT NULL, status TEXT NOT NULL, date_from TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_to TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_4DA23916A2B381 ON reservations (book_id)');
        $this->addSql('CREATE INDEX IDX_4DA23919EB6921 ON reservations (client_id)');
        $this->addSql('COMMENT ON COLUMN reservations.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN reservations.book_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN reservations.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23916A2B381 FOREIGN KEY (book_id) REFERENCES books (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23919EB6921 FOREIGN KEY (client_id) REFERENCES clients (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reservations DROP CONSTRAINT FK_4DA23916A2B381');
        $this->addSql('ALTER TABLE reservations DROP CONSTRAINT FK_4DA23919EB6921');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE reservations');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190509205547 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE aliment ADD type_aliment_id INT NOT NULL');
        $this->addSql('ALTER TABLE aliment ADD CONSTRAINT FK_70FF972B5C584934 FOREIGN KEY (type_aliment_id) REFERENCES type_aliment (id)');
        $this->addSql('CREATE INDEX IDX_70FF972B5C584934 ON aliment (type_aliment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE aliment DROP FOREIGN KEY FK_70FF972B5C584934');
        $this->addSql('DROP INDEX IDX_70FF972B5C584934 ON aliment');
        $this->addSql('ALTER TABLE aliment DROP type_aliment_id');
    }
}

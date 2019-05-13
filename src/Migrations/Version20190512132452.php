<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190512132452 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //$this->addSql('ALTER TABLE aliment ADD CONSTRAINT FK_70FF972BEC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id)');
        //$this->addSql('CREATE INDEX IDX_70FF972BEC4A74AB ON aliment (unite_id)');
        /*$this->addSql('ALTER TABLE preparation DROP FOREIGN KEY FK_F9F0AAF43C43472D');
        $this->addSql('DROP INDEX IDX_F9F0AAF43C43472D ON preparation');
        $this->addSql('ALTER TABLE preparation DROP boite_id');*/
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //$this->addSql('ALTER TABLE aliment DROP FOREIGN KEY FK_70FF972BEC4A74AB');
        //$this->addSql('DROP INDEX IDX_70FF972BEC4A74AB ON aliment');
        /*$this->addSql('ALTER TABLE preparation ADD boite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE preparation ADD CONSTRAINT FK_F9F0AAF43C43472D FOREIGN KEY (boite_id) REFERENCES boite (id)');
        $this->addSql('CREATE INDEX IDX_F9F0AAF43C43472D ON preparation (boite_id)');*/
    }
}

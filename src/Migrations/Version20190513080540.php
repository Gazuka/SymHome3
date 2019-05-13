<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190513080540 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE preparation_boite');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE preparation_boite (preparation_id INT NOT NULL, boite_id INT NOT NULL, INDEX IDX_75C783B33DD9B8BA (preparation_id), INDEX IDX_75C783B33C43472D (boite_id), PRIMARY KEY(preparation_id, boite_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE preparation_boite ADD CONSTRAINT FK_75C783B33C43472D FOREIGN KEY (boite_id) REFERENCES boite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE preparation_boite ADD CONSTRAINT FK_75C783B33DD9B8BA FOREIGN KEY (preparation_id) REFERENCES preparation (id) ON DELETE CASCADE');
    }
}

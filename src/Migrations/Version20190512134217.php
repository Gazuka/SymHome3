<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190512134217 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE aliment (id INT AUTO_INCREMENT NOT NULL, type_aliment_id INT NOT NULL, unite_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_70FF972B5C584934 (type_aliment_id), INDEX IDX_70FF972BEC4A74AB (unite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE boite (id INT AUTO_INCREMENT NOT NULL, stockage_id INT DEFAULT NULL, preparation_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_7718EDEFDAA83D7F (stockage_id), INDEX IDX_7718EDEF3DD9B8BA (preparation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etape_recette (id INT AUTO_INCREMENT NOT NULL, recette_id INT NOT NULL, descriptif LONGTEXT NOT NULL, position INT NOT NULL, INDEX IDX_12D2C04B89312FE9 (recette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etape_recette_ingredient (etape_recette_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_815D47EBB9A9C3B5 (etape_recette_id), INDEX IDX_815D47EB933FE08C (ingredient_id), PRIMARY KEY(etape_recette_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etape_recette_ustensile (etape_recette_id INT NOT NULL, ustensile_id INT NOT NULL, INDEX IDX_F59A4B9FB9A9C3B5 (etape_recette_id), INDEX IDX_F59A4B9FB78A4282 (ustensile_id), PRIMARY KEY(etape_recette_id, ustensile_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, aliment_id INT NOT NULL, recette_id INT NOT NULL, quantite DOUBLE PRECISION NOT NULL, INDEX IDX_6BAF7870415B9F11 (aliment_id), INDEX IDX_6BAF787089312FE9 (recette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preparation (id INT AUTO_INCREMENT NOT NULL, repas_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, date_preparation DATE DEFAULT NULL, INDEX IDX_F9F0AAF41D236AAA (repas_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preparation_recette (preparation_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_F5BF3F483DD9B8BA (preparation_id), INDEX IDX_F5BF3F4889312FE9 (recette_id), PRIMARY KEY(preparation_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, stockage_id INT DEFAULT NULL, aliment_id INT NOT NULL, nom VARCHAR(255) NOT NULL, quantite DOUBLE PRECISION DEFAULT NULL, date_achat DATE DEFAULT NULL, date_peremption DATE DEFAULT NULL, quantite_initiale DOUBLE PRECISION DEFAULT NULL, INDEX IDX_29A5EC27DAA83D7F (stockage_id), INDEX IDX_29A5EC27415B9F11 (aliment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, portion INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette_ustensile (recette_id INT NOT NULL, ustensile_id INT NOT NULL, INDEX IDX_613487D589312FE9 (recette_id), INDEX IDX_613487D5B78A4282 (ustensile_id), PRIMARY KEY(recette_id, ustensile_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repas (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repas_recette (repas_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_34D50B7C1D236AAA (repas_id), INDEX IDX_34D50B7C89312FE9 (recette_id), PRIMARY KEY(repas_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stockage (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_CABCB492727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_aliment (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ustensile (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aliment ADD CONSTRAINT FK_70FF972B5C584934 FOREIGN KEY (type_aliment_id) REFERENCES type_aliment (id)');
        $this->addSql('ALTER TABLE aliment ADD CONSTRAINT FK_70FF972BEC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id)');
        $this->addSql('ALTER TABLE boite ADD CONSTRAINT FK_7718EDEFDAA83D7F FOREIGN KEY (stockage_id) REFERENCES stockage (id)');
        $this->addSql('ALTER TABLE boite ADD CONSTRAINT FK_7718EDEF3DD9B8BA FOREIGN KEY (preparation_id) REFERENCES preparation (id)');
        $this->addSql('ALTER TABLE etape_recette ADD CONSTRAINT FK_12D2C04B89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE etape_recette_ingredient ADD CONSTRAINT FK_815D47EBB9A9C3B5 FOREIGN KEY (etape_recette_id) REFERENCES etape_recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etape_recette_ingredient ADD CONSTRAINT FK_815D47EB933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etape_recette_ustensile ADD CONSTRAINT FK_F59A4B9FB9A9C3B5 FOREIGN KEY (etape_recette_id) REFERENCES etape_recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etape_recette_ustensile ADD CONSTRAINT FK_F59A4B9FB78A4282 FOREIGN KEY (ustensile_id) REFERENCES ustensile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870415B9F11 FOREIGN KEY (aliment_id) REFERENCES aliment (id)');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787089312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE preparation ADD CONSTRAINT FK_F9F0AAF41D236AAA FOREIGN KEY (repas_id) REFERENCES repas (id)');
        $this->addSql('ALTER TABLE preparation_recette ADD CONSTRAINT FK_F5BF3F483DD9B8BA FOREIGN KEY (preparation_id) REFERENCES preparation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE preparation_recette ADD CONSTRAINT FK_F5BF3F4889312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27DAA83D7F FOREIGN KEY (stockage_id) REFERENCES stockage (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27415B9F11 FOREIGN KEY (aliment_id) REFERENCES aliment (id)');
        $this->addSql('ALTER TABLE recette_ustensile ADD CONSTRAINT FK_613487D589312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_ustensile ADD CONSTRAINT FK_613487D5B78A4282 FOREIGN KEY (ustensile_id) REFERENCES ustensile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE repas_recette ADD CONSTRAINT FK_34D50B7C1D236AAA FOREIGN KEY (repas_id) REFERENCES repas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE repas_recette ADD CONSTRAINT FK_34D50B7C89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stockage ADD CONSTRAINT FK_CABCB492727ACA70 FOREIGN KEY (parent_id) REFERENCES stockage (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870415B9F11');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27415B9F11');
        $this->addSql('ALTER TABLE etape_recette_ingredient DROP FOREIGN KEY FK_815D47EBB9A9C3B5');
        $this->addSql('ALTER TABLE etape_recette_ustensile DROP FOREIGN KEY FK_F59A4B9FB9A9C3B5');
        $this->addSql('ALTER TABLE etape_recette_ingredient DROP FOREIGN KEY FK_815D47EB933FE08C');
        $this->addSql('ALTER TABLE boite DROP FOREIGN KEY FK_7718EDEF3DD9B8BA');
        $this->addSql('ALTER TABLE preparation_recette DROP FOREIGN KEY FK_F5BF3F483DD9B8BA');
        $this->addSql('ALTER TABLE etape_recette DROP FOREIGN KEY FK_12D2C04B89312FE9');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF787089312FE9');
        $this->addSql('ALTER TABLE preparation_recette DROP FOREIGN KEY FK_F5BF3F4889312FE9');
        $this->addSql('ALTER TABLE recette_ustensile DROP FOREIGN KEY FK_613487D589312FE9');
        $this->addSql('ALTER TABLE repas_recette DROP FOREIGN KEY FK_34D50B7C89312FE9');
        $this->addSql('ALTER TABLE preparation DROP FOREIGN KEY FK_F9F0AAF41D236AAA');
        $this->addSql('ALTER TABLE repas_recette DROP FOREIGN KEY FK_34D50B7C1D236AAA');
        $this->addSql('ALTER TABLE boite DROP FOREIGN KEY FK_7718EDEFDAA83D7F');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27DAA83D7F');
        $this->addSql('ALTER TABLE stockage DROP FOREIGN KEY FK_CABCB492727ACA70');
        $this->addSql('ALTER TABLE aliment DROP FOREIGN KEY FK_70FF972B5C584934');
        $this->addSql('ALTER TABLE aliment DROP FOREIGN KEY FK_70FF972BEC4A74AB');
        $this->addSql('ALTER TABLE etape_recette_ustensile DROP FOREIGN KEY FK_F59A4B9FB78A4282');
        $this->addSql('ALTER TABLE recette_ustensile DROP FOREIGN KEY FK_613487D5B78A4282');
        $this->addSql('DROP TABLE aliment');
        $this->addSql('DROP TABLE boite');
        $this->addSql('DROP TABLE etape_recette');
        $this->addSql('DROP TABLE etape_recette_ingredient');
        $this->addSql('DROP TABLE etape_recette_ustensile');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE preparation');
        $this->addSql('DROP TABLE preparation_recette');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE recette_ustensile');
        $this->addSql('DROP TABLE repas');
        $this->addSql('DROP TABLE repas_recette');
        $this->addSql('DROP TABLE stockage');
        $this->addSql('DROP TABLE type_aliment');
        $this->addSql('DROP TABLE unite');
        $this->addSql('DROP TABLE ustensile');
    }
}

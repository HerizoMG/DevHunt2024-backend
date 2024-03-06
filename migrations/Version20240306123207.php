<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306123207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(150) NOT NULL, user_name VARCHAR(20) NOT NULL, path VARCHAR(255) NOT NULL, raison_social VARCHAR(100) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, password VARCHAR(100) NOT NULL, is_admin TINYINT(1) NOT NULL, is_novice TINYINT(1) NOT NULL, is_enseignant TINYINT(1) NOT NULL, is_administration TINYINT(1) NOT NULL, is_entreprise TINYINT(1) NOT NULL, is_materiel TINYINT(1) NOT NULL, is_elder TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `user`');
    }
}

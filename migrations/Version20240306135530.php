<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306135530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_cours DROP FOREIGN KEY FK_6AD100737ECF78B0');
        $this->addSql('ALTER TABLE post_cours DROP FOREIGN KEY FK_6AD100734B89032C');
        $this->addSql('DROP TABLE post_cours');
        $this->addSql('ALTER TABLE post ADD cours_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D7ECF78B0 ON post (cours_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_cours (post_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_6AD100734B89032C (post_id), INDEX IDX_6AD100737ECF78B0 (cours_id), PRIMARY KEY(post_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE post_cours ADD CONSTRAINT FK_6AD100737ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_cours ADD CONSTRAINT FK_6AD100734B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D7ECF78B0');
        $this->addSql('DROP INDEX IDX_5A8A6C8D7ECF78B0 ON post');
        $this->addSql('ALTER TABLE post DROP cours_id');
    }
}

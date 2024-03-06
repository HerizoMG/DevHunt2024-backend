<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306133949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, appreciation INT DEFAULT NULL, designation VARCHAR(50) NOT NULL, user_id INT DEFAULT NULL, post_id INT DEFAULT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE comment_pieces_jointes (comment_id INT NOT NULL, pieces_jointes_id INT NOT NULL, INDEX IDX_AA117552F8697D13 (comment_id), INDEX IDX_AA11755213F30CF8 (pieces_jointes_id), PRIMARY KEY(comment_id, pieces_jointes_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, matiere_id INT DEFAULT NULL, INDEX IDX_FDCA8C9CF46CD258 (matiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pieces_jointes (id INT AUTO_INCREMENT NOT NULL, link VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pieces_jointes_post (pieces_jointes_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_E6344E7613F30CF8 (pieces_jointes_id), INDEX IDX_E6344E764B89032C (post_id), PRIMARY KEY(pieces_jointes_id, post_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) DEFAULT NULL, description VARCHAR(255) NOT NULL, is_epingle TINYINT(1) NOT NULL, user_id INT DEFAULT NULL, cours_id INT DEFAULT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), INDEX IDX_5A8A6C8D7ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE post_tag (post_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_5ACE3AF04B89032C (post_id), INDEX IDX_5ACE3AF0BAD26311 (tag_id), PRIMARY KEY(post_id, tag_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_post (user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_200B2044A76ED395 (user_id), INDEX IDX_200B20444B89032C (post_id), PRIMARY KEY(user_id, post_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_matiere (user_id INT NOT NULL, matiere_id INT NOT NULL, INDEX IDX_C8194940A76ED395 (user_id), INDEX IDX_C8194940F46CD258 (matiere_id), PRIMARY KEY(user_id, matiere_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment_pieces_jointes ADD CONSTRAINT FK_AA117552F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_pieces_jointes ADD CONSTRAINT FK_AA11755213F30CF8 FOREIGN KEY (pieces_jointes_id) REFERENCES pieces_jointes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE pieces_jointes_post ADD CONSTRAINT FK_E6344E7613F30CF8 FOREIGN KEY (pieces_jointes_id) REFERENCES pieces_jointes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pieces_jointes_post ADD CONSTRAINT FK_E6344E764B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE post_tag ADD CONSTRAINT FK_5ACE3AF04B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_tag ADD CONSTRAINT FK_5ACE3AF0BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B2044A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B20444B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_matiere ADD CONSTRAINT FK_C8194940A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_matiere ADD CONSTRAINT FK_C8194940F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment_pieces_jointes DROP FOREIGN KEY FK_AA117552F8697D13');
        $this->addSql('ALTER TABLE comment_pieces_jointes DROP FOREIGN KEY FK_AA11755213F30CF8');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CF46CD258');
        $this->addSql('ALTER TABLE pieces_jointes_post DROP FOREIGN KEY FK_E6344E7613F30CF8');
        $this->addSql('ALTER TABLE pieces_jointes_post DROP FOREIGN KEY FK_E6344E764B89032C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D7ECF78B0');
        $this->addSql('ALTER TABLE post_tag DROP FOREIGN KEY FK_5ACE3AF04B89032C');
        $this->addSql('ALTER TABLE post_tag DROP FOREIGN KEY FK_5ACE3AF0BAD26311');
        $this->addSql('ALTER TABLE user_post DROP FOREIGN KEY FK_200B2044A76ED395');
        $this->addSql('ALTER TABLE user_post DROP FOREIGN KEY FK_200B20444B89032C');
        $this->addSql('ALTER TABLE user_matiere DROP FOREIGN KEY FK_C8194940A76ED395');
        $this->addSql('ALTER TABLE user_matiere DROP FOREIGN KEY FK_C8194940F46CD258');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_pieces_jointes');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE pieces_jointes');
        $this->addSql('DROP TABLE pieces_jointes_post');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user_post');
        $this->addSql('DROP TABLE user_matiere');
    }
}

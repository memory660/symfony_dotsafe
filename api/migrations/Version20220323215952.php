<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220323215952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contribution (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contribution_techno (contribution_id INT NOT NULL, techno_id INT NOT NULL, INDEX IDX_1C8D4F77FE5E5FBD (contribution_id), INDEX IDX_1C8D4F7751F3C1BC (techno_id), PRIMARY KEY(contribution_id, techno_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contribution_project (contribution_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_6E92A02FFE5E5FBD (contribution_id), INDEX IDX_6E92A02F166D1F9C (project_id), PRIMARY KEY(contribution_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contribution_member (contribution_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_55EE5BD3FE5E5FBD (contribution_id), INDEX IDX_55EE5BD37597D3FE (member_id), PRIMARY KEY(contribution_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE techno (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contribution_techno ADD CONSTRAINT FK_1C8D4F77FE5E5FBD FOREIGN KEY (contribution_id) REFERENCES contribution (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution_techno ADD CONSTRAINT FK_1C8D4F7751F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution_project ADD CONSTRAINT FK_6E92A02FFE5E5FBD FOREIGN KEY (contribution_id) REFERENCES contribution (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution_project ADD CONSTRAINT FK_6E92A02F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution_member ADD CONSTRAINT FK_55EE5BD3FE5E5FBD FOREIGN KEY (contribution_id) REFERENCES contribution (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution_member ADD CONSTRAINT FK_55EE5BD37597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contribution_techno DROP FOREIGN KEY FK_1C8D4F77FE5E5FBD');
        $this->addSql('ALTER TABLE contribution_project DROP FOREIGN KEY FK_6E92A02FFE5E5FBD');
        $this->addSql('ALTER TABLE contribution_member DROP FOREIGN KEY FK_55EE5BD3FE5E5FBD');
        $this->addSql('ALTER TABLE contribution_member DROP FOREIGN KEY FK_55EE5BD37597D3FE');
        $this->addSql('ALTER TABLE contribution_project DROP FOREIGN KEY FK_6E92A02F166D1F9C');
        $this->addSql('ALTER TABLE contribution_techno DROP FOREIGN KEY FK_1C8D4F7751F3C1BC');
        $this->addSql('DROP TABLE contribution');
        $this->addSql('DROP TABLE contribution_techno');
        $this->addSql('DROP TABLE contribution_project');
        $this->addSql('DROP TABLE contribution_member');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE techno');
    }
}

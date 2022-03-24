<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220324061625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contribution_member');
        $this->addSql('DROP TABLE contribution_project');
        $this->addSql('DROP TABLE contribution_techno');
        $this->addSql('ALTER TABLE contribution ADD technos VARCHAR(255) NOT NULL, ADD projects VARCHAR(255) NOT NULL, ADD members VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contribution_member (contribution_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_55EE5BD37597D3FE (member_id), INDEX IDX_55EE5BD3FE5E5FBD (contribution_id), PRIMARY KEY(contribution_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE contribution_project (contribution_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_6E92A02F166D1F9C (project_id), INDEX IDX_6E92A02FFE5E5FBD (contribution_id), PRIMARY KEY(contribution_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE contribution_techno (contribution_id INT NOT NULL, techno_id INT NOT NULL, INDEX IDX_1C8D4F7751F3C1BC (techno_id), INDEX IDX_1C8D4F77FE5E5FBD (contribution_id), PRIMARY KEY(contribution_id, techno_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE contribution_member ADD CONSTRAINT FK_55EE5BD3FE5E5FBD FOREIGN KEY (contribution_id) REFERENCES contribution (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution_member ADD CONSTRAINT FK_55EE5BD37597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution_project ADD CONSTRAINT FK_6E92A02FFE5E5FBD FOREIGN KEY (contribution_id) REFERENCES contribution (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution_project ADD CONSTRAINT FK_6E92A02F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution_techno ADD CONSTRAINT FK_1C8D4F77FE5E5FBD FOREIGN KEY (contribution_id) REFERENCES contribution (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution_techno ADD CONSTRAINT FK_1C8D4F7751F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contribution DROP technos, DROP projects, DROP members');
    }
}

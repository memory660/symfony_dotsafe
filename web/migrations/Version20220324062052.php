<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220324062052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA351E159452D61A ON contribution (technos)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA351E155C93B3A4 ON contribution (projects)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA351E1545A0D2FF ON contribution (members)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_EA351E159452D61A ON contribution');
        $this->addSql('DROP INDEX UNIQ_EA351E155C93B3A4 ON contribution');
        $this->addSql('DROP INDEX UNIQ_EA351E1545A0D2FF ON contribution');
    }
}

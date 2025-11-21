<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251117145437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donateur ADD roles JSON NOT NULL, DROP role, CHANGE email email VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9CD3DE50E7927C74 ON donateur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_9CD3DE50E7927C74 ON donateur');
        $this->addSql('ALTER TABLE donateur ADD role VARCHAR(255) DEFAULT NULL, DROP roles, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(80) NOT NULL');
    }
}

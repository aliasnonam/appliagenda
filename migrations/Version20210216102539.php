<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210216102539 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE telephone ADD contact_id INT NOT NULL');
        $this->addSql('ALTER TABLE telephone ADD CONSTRAINT FK_450FF010E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('CREATE INDEX IDX_450FF010E7A1254A ON telephone (contact_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE telephone DROP FOREIGN KEY FK_450FF010E7A1254A');
        $this->addSql('DROP INDEX IDX_450FF010E7A1254A ON telephone');
        $this->addSql('ALTER TABLE telephone DROP contact_id');
    }
}

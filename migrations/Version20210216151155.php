<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210216151155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe ADD contact_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21E7A1254A FOREIGN KEY (contact_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B98C21E7A1254A ON groupe (contact_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21E7A1254A');
        $this->addSql('DROP INDEX UNIQ_4B98C21E7A1254A ON groupe');
        $this->addSql('ALTER TABLE groupe DROP contact_id');
    }
}

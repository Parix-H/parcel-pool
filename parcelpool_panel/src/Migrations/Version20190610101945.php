<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190610101945 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE parcel_pool (id INT AUTO_INCREMENT NOT NULL, source_city_id INT NOT NULL, destination_city_id INT NOT NULL, agent_id_id INT DEFAULT NULL, number_of_parcels INT DEFAULT NULL, INDEX IDX_B99F42B9C273DAC7 (source_city_id), INDEX IDX_B99F42B9E5955DD7 (destination_city_id), INDEX IDX_B99F42B946EAB62F (agent_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE parcel_pool ADD CONSTRAINT FK_B99F42B9C273DAC7 FOREIGN KEY (source_city_id) REFERENCES source_city (id)');
        $this->addSql('ALTER TABLE parcel_pool ADD CONSTRAINT FK_B99F42B9E5955DD7 FOREIGN KEY (destination_city_id) REFERENCES destination_city (id)');
        $this->addSql('ALTER TABLE parcel_pool ADD CONSTRAINT FK_B99F42B946EAB62F FOREIGN KEY (agent_id_id) REFERENCES agent (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE parcel_pool');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210803163255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variant_product ADD product_id INT UNSIGNED NOT NULL, CHANGE variant_label_ids variant_mapping VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE variant_product ADD CONSTRAINT FK_BCEF29EB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_BCEF29EB4584665A ON variant_product (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variant_product DROP FOREIGN KEY FK_BCEF29EB4584665A');
        $this->addSql('DROP INDEX IDX_BCEF29EB4584665A ON variant_product');
        $this->addSql('ALTER TABLE variant_product DROP product_id, CHANGE variant_mapping variant_label_ids VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210801160028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE variant_label (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variant_label_variant_name (variant_label_id INT NOT NULL, variant_name_id INT NOT NULL, INDEX IDX_C6484D12AC696EB1 (variant_label_id), INDEX IDX_C6484D121EB2B190 (variant_name_id), PRIMARY KEY(variant_label_id, variant_name_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variant_name (id INT AUTO_INCREMENT NOT NULL, product_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_B1DBE8934584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variant_product (id INT AUTO_INCREMENT NOT NULL, price DOUBLE PRECISION DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, variant_label_ids VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE variant_label_variant_name ADD CONSTRAINT FK_C6484D12AC696EB1 FOREIGN KEY (variant_label_id) REFERENCES variant_label (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE variant_label_variant_name ADD CONSTRAINT FK_C6484D121EB2B190 FOREIGN KEY (variant_name_id) REFERENCES variant_name (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE variant_name ADD CONSTRAINT FK_B1DBE8934584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE picture ADD variant_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89B7059ACF FOREIGN KEY (variant_product_id) REFERENCES variant_product (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F89B7059ACF ON picture (variant_product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variant_label_variant_name DROP FOREIGN KEY FK_C6484D12AC696EB1');
        $this->addSql('ALTER TABLE variant_label_variant_name DROP FOREIGN KEY FK_C6484D121EB2B190');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89B7059ACF');
        $this->addSql('DROP TABLE variant_label');
        $this->addSql('DROP TABLE variant_label_variant_name');
        $this->addSql('DROP TABLE variant_name');
        $this->addSql('DROP TABLE variant_product');
        $this->addSql('DROP INDEX IDX_16DB4F89B7059ACF ON picture');
        $this->addSql('ALTER TABLE picture DROP variant_product_id');
    }
}

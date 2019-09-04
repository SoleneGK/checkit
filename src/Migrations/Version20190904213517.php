<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use App\Entity\Priority;
use App\Entity\Periodicity;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190904205838 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }

    public function postUp(Schema $schema) : void
    {
        $manager = $this->container->get('doctrine.orm.entity_manager');

        $priorities_list = ['optionnal', 'normal', 'important', 'critical'];

        foreach ($priorities_list as $name) {
            $priority = new Priority();
            $priority->setName($name);
            $manager->persist($priority);
        }
        
        // Initial priorities
        $periodicities_list = [
            'U' => 'unique',
            'D0' => 'quotidienne',
            'W0' => 'hebdomadaire',
            'M0' => 'mensuelle',
            'W1' => 'tous les lundis',
            'W2' => 'tous les mardis',
            'W3' => 'tous les mercredis',
            'W4' => 'tous les jeudis',
            'W5' => 'tous les vendredis',
            'W6' => 'tous les samedis',
            'W7' => 'tous les dimanches',
            'M1' => 'tous les 1ers du mois'
        ];

        for ($i = 2; $i <= 31 ; $i++) {
            $key = 'M' . $i;
            $periodicities_list[$key] = 'tous les ' . $i . ' du mois';
        }

        foreach ($periodicities_list as $key => $value) {
            $periodicity = new Periodicity();
            $periodicity->setCode($key);
            $periodicity->setName($value);
            $manager->persist($periodicity);
        }

        $manager->flush();
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    
    }
}
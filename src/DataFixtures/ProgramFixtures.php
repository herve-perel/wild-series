<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void

    {

        //Puis ici nous demandons à la Factory de nous fournir un Faker

        $faker = Factory::create();


        /**

         * L'objet $faker que tu récupère est l'outil qui va te permettre 

         * de te générer toutes les données que tu souhaites

         */


        for ($i = 0; $i < 5; $i++) {

            $program = new Program();

            //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base

            $program->setTitle($faker->text(5));

            $program->setYear($faker->year());

            $program->setSynopsis($faker->paragraphs(3, true));

            $program->setCountry($faker->numberBetween(1, 10));
            $program->setPoster($faker->numberBetween(1, 10));
            $program->setCategory($this->getReference('category_' . $i));
            $this->addReference('program_' . $i, $program);

            $manager->persist($program);
        }


        $manager->flush();
    }
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}

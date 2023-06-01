<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Actor;
use App\Entity\Program;
use App\DataFixtures\ProgramFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();

        for ($i = 0; $i < 12; $i++) {
            $actor = new Actor();
            $actor->setName($faker->text(10));
            $actor->addProgram($this->getReference('program_' . $i % 3));
            $this->addReference('actor_' . $i, $actor);

            $manager->persist($actor);
        }

        $manager->flush();
    }

    public function getDependencies(): array

    {

        return [

            ProgramFixtures::class,

        ];
    }
}

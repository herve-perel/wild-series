<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 250; $i++) {
            $episode = new episode();
            $episode->setNumber($faker->numberBetween(1, 10));
            $episode->settitle($faker->title());
            $episode->setSynopsis($faker->paragraphs(3, true));
            $episode->setSeason($this->getReference('season_' . $i % 25));

            $manager->persist($episode);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            SeasonFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void

    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {

            $program = new Program();
            $program->setTitle($faker->text(5));
            $program->setYear($faker->year());
            $program->setSynopsis($faker->paragraphs(3, true));
            $program->setCountry($faker->numberBetween(1, 10));
            $program->setPoster($faker->numberBetween(1, 10));
            $program->setCategory($this->getReference('category_' . $i));
            $this->addReference('program_' . $i, $program);
            $program->setSlug(strtolower($this->slugger->slug($program->getTitle())));
            
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

<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    public const PROGRAM = [
        'evil dead',
        'x-files',
        'the last of us',
        'the watcher',
        'slasher',
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAM as $key => $programTitle) {

            $program = new Program();

            $program->setTitle($programTitle);

            $program->setSynopsis('');

            $program->setCategory($this->getReference('category_Action'));

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

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Episode;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class EpisodeFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker=Faker\Factory::create('fr_FR');
        for ($i = 0;$i < 100; $i++) {
            $episode = new Episode();
            $episode->setNumber($faker->numberBetween(0,10));
            $episode->setTitle($faker->sentence());
            $episode->setSynopsis($faker->text());
            $episode->setSeason($this->getReference('season_'.rand(1,5)));
            $manager->persist($episode);
        }

        $manager->flush();
    }

    public function getDependencies()  

    {

        return [SeasonFixture::class];  

    }
}
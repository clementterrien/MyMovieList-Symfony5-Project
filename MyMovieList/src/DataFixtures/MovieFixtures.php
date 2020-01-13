<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Movie;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i <= 10; $i++) {
            $category = new Category();
            $category->setTitle($i);
            $movie = new Movie();
            $movie->setTitle("Titre du film n°$i")
                ->setImage("http://placehold.it/350x150")
                ->setSynopsis("Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                    Praesentium, aperiam eum blanditiis eligendi vero incidunt mollitia pariatur quia saepe atque! 
                    Architecto obcaecati dolore veritatis ea blanditiis pariatur ullam expedita. Reprehenderit quas 
                    quis ea modi cupiditate quam voluptate tempora veniam iusto! Voluptate veritatis vero ratione 
                    accusantium quo inventore necessitatibus, ab commodi optio dolor sapiente quas nemo placeat odio rerum officia in soluta! 
                    Vitae ullam officia, dolor mollitia quibusdam atque perspiciatis deserunt fuga aliquam debitis laborum ipsam repudiandae 
                    aspernatur velit, accusantium quia!")
                ->setReleaseAt(new \DateTime())
                ->setCategory($category);
            $manager->persist($category);
            $manager->persist($movie);
        }

        $manager->flush();
    }
}

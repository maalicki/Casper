<?php

namespace Polcode\CasperBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
Use Polcode\CasperBundle\Entity\User;
Use Polcode\CasperBundle\Entity\Event;

Class AppFixtures extends AbstractFixture {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $this->manager = $manager;
        $this->loadSampleUsers();
        $eventOwner = $this->loadUser();
        $lastEvent = $this->loadEvents($eventOwner);
        $this->joinUserToEvent($eventOwner, $lastEvent);
    }

    private function loadUser() {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('email@example.com');
        $userAdmin->setEnabled(true);
        $userAdmin->addRole('ROLE_USER');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setSex('m');
        $userAdmin->setBirthDate(new \DateTime('02.01.1999'));
        $this->manager->persist($userAdmin);
        $this->manager->flush();
        return $userAdmin;
    }

    private function loadSampleUsers() {

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setUsername("some user $i");
            $user->setEmail("some$i@example.com");
            $user->setEnabled(true);
            $user->addRole('ROLE_USER');
            $user->setPlainPassword("test$i");
            $user->setSex('m');
            $user->setBirthDate(new \DateTime('02.01.1999'));
            $this->manager->persist($user);
            $this->manager->flush();
        }
    }

    public function loadEvents(User $eventOwner) {

        $eventsList = [
            [
                'eventName' => 'Public concert',
                'description' => 'You can put here anything you want to!.',
                'location' => 'Olimpijska 11, Katowice',
                'latitude' => 50.26763895717659,
                'longitude' => 19.027242064476013,
                'eventStart' => ( new \DateTime('2015-05-20') ),
                'eventStop' => ( new \DateTime('2015-05-27') ),
                'signUp' => ( new \DateTime('2015-05-19') ),
                'guests' => null,
                'private' => false,
                'deleted' => false,
                'user' => $eventOwner,
            ],
            [
                'eventName' => 'Old Concert',
                'description' => 'You can put here anything you want to!.',
                'location' => 'Ratajczaka 1, Poznań, Polska',
                'latitude' => 52.40278543661038,
                'longitude' => 16.92344069480896,
                'eventStart' => ( new \DateTime('2015-02-12') ),
                'eventStop' => ( new \DateTime('2015-02-13') ),
                'signUp' => ( new \DateTime('2015-02-10') ),
                'guests' => 20,
                'private' => false,
                'deleted' => false,
                'user' => $eventOwner,
            ],
            [
                'eventName' => 'Private event',
                'description' => 'You can put here anything you want to!.',
                'location' => 'Łęczyńska 29, Lublin, Polska',
                'latitude' => 51.24007674714826,
                'longitude' => 22.584822177886963,
                'eventStart' => ( new \DateTime('2015-05-21') ),
                'eventStop' => ( new \DateTime('2015-05-23') ),
                'signUp' => ( new \DateTime('2015-05-21') ),
                'guests' => null,
                'private' => true,
                'deleted' => false,
                'user' => $eventOwner,
            ],
            [
                'eventName' => 'Second Concert',
                'description' => 'You can put here anything you want to!.',
                'location' => 'Warszawa, Wawelska 19',
                'latitude' => 52.21592940490216,
                'longitude' => 20.982331037521362,
                'eventStart' => ( new \DateTime('2015-05-01') ),
                'eventStop' => ( new \DateTime('2015-05-03') ),
                'signUp' => ( new \DateTime('2015-04-30') ),
                'guests' => 2,
                'private' => false,
                'deleted' => false,
                'user' => $eventOwner,
            ],
        ];

        foreach ($eventsList as $key => $name) {
            $event = new Event();

            $event->setEventName($name['eventName'])
                    ->setDescription($name['description'])
                    ->setLocation($name['location'])
                    ->setLatitude($name['latitude'])
                    ->setLongitude($name['longitude'])
                    ->setEventStart($name['eventStart'])
                    ->setEventStop($name['eventStop'])
                    ->setEventSignUpEndDate($name['signUp'])
                    ->setMaxGuests($name['guests'])
                    ->setPrivate($name['private'])
                    ->setDeleted($name['deleted'])
                    ->setUser($name['user']);

            $this->manager->persist($event);
        }

        $this->manager->flush();

        return $event;
    }

    public function joinUserToEvent(User $user, Event $event) {
        $user->joinToEvent($event);
        $this->manager->flush();
    }

}

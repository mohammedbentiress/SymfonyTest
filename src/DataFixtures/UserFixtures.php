<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setEmail('mohammedbenitress@gmail.com')
            ->setRoles(['ROLE_ADMIN '])
            ->setPassword($this->passwordEncoder->encodePassword(
                         $user,
                         'welcome'
                     ));
        $manager->persist($user);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group2'];
    }
}

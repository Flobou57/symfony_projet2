<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['admin@example.com', 'Admin', 'User', ['ROLE_ADMIN']],
            ['manager@example.com', 'Manager', 'User', ['ROLE_MANAGER']],
            ['user@example.com', 'User', 'Demo', ['ROLE_USER']],
        ];

        foreach ($users as [$email, $firstname, $lastname, $roles]) {
            $user = new User();
            $user->setEmail($email);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setRoles($roles);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            $manager->persist($user);
        }

        $clients = [
            ['Alice', 'Martin', 'alice.martin@example.com', '0601020304', '12 rue de Paris, 75001 Paris'],
            ['Bruno', 'Durand', 'bruno.durand@example.com', '0605060708', '8 avenue Victor Hugo, 75016 Paris'],
            ['Claire', 'Petit', 'claire.petit@example.com', '0611223344', '5 boulevard Voltaire, 75011 Paris'],
            ['David', 'Leroy', 'david.leroy@example.com', '0622334455', '22 rue Nationale, 59000 Lille'],
            ['Emma', 'Roux', 'emma.roux@example.com', '0633445566', '3 place Bellecour, 69002 Lyon'],
        ];

        foreach ($clients as [$firstname, $lastname, $email, $phone, $address]) {
            $client = new Client();
            $client->setFirstname($firstname);
            $client->setLastname($lastname);
            $client->setEmail($email);
            $client->setPhoneNumber($phone);
            $client->setAddress($address);

            $manager->persist($client);
        }

        $manager->flush();
    }
}

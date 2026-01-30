<?php

namespace App\Service;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

class ClientCreator
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function create(
        string $firstname,
        string $lastname,
        string $email,
        string $phoneNumber,
        string $address
    ): Client {
        $client = new Client();
        $client->setFirstname($firstname);
        $client->setLastname($lastname);
        $client->setEmail($email);
        $client->setPhoneNumber($phoneNumber);
        $client->setAddress($address);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client;
    }
}

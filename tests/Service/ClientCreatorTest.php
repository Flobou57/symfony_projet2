<?php

namespace App\Tests\Service;

use App\Entity\Client;
use App\Service\ClientCreator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ClientCreatorTest extends TestCase
{
    public function testCreatePersistsClient(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $persisted = null;
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (Client $client) use (&$persisted): bool {
                $persisted = $client;
                return true;
            }));

        $entityManager->expects($this->once())->method('flush');

        $creator = new ClientCreator($entityManager);
        $client = $creator->create(
            'Jean',
            'Dupont',
            'jean.dupont@example.com',
            '0612345678',
            '10 rue de Lyon'
        );

        $this->assertSame($client, $persisted);
        $this->assertSame('Jean', $client->getFirstname());
        $this->assertSame('Dupont', $client->getLastname());
        $this->assertSame('jean.dupont@example.com', $client->getEmail());
        $this->assertSame('0612345678', $client->getPhoneNumber());
        $this->assertSame('10 rue de Lyon', $client->getAddress());
        $this->assertInstanceOf(\DateTimeImmutable::class, $client->getCreatedAt());
    }
}

<?php

namespace App\Command;

use App\Entity\Client;
use App\Service\ClientCreator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:client:add',
    description: 'Add a client interactively.'
)]
class AddClientCommand extends Command
{
    public function __construct(
        private readonly ClientCreator $clientCreator,
        private readonly ValidatorInterface $validator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $firstname = $io->ask('Prenom', null, $this->requireValue(...));
        $lastname = $io->ask('Nom', null, $this->requireValue(...));
        $email = $io->ask('Email', null, $this->requireValue(...));
        $phone = $io->ask('Numero de telephone', null, $this->requireValue(...));
        $address = $io->ask('Adresse', null, $this->requireValue(...));

        $client = new Client();
        $client->setFirstname($firstname);
        $client->setLastname($lastname);
        $client->setEmail($email);
        $client->setPhoneNumber($phone);
        $client->setAddress($address);

        $errors = $this->validator->validate($client);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $io->error($error->getPropertyPath().': '.$error->getMessage());
            }

            return Command::FAILURE;
        }

        $this->clientCreator->create($firstname, $lastname, $email, $phone, $address);

        $io->success('Client ajoute.');

        return Command::SUCCESS;
    }

    private function requireValue(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            throw new \RuntimeException('Valeur obligatoire.');
        }

        return $value;
    }
}

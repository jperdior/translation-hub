<?php

declare(strict_types=1);

namespace App\TranslationComponent\Presentation\Command\Translation;

use App\TranslationComponent\Infrastructure\Messenger\SimpleCommandBus;
use App\TranslationComponent\Infrastructure\Messenger\SimpleQueryBus;
use App\TranslationComponent\Presentation\Swagger\TranslationSwagger;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Console\Input\InputArgument;
use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\TranslationComponent\Application\Command\QueueTranslationMessage;
use App\TranslationComponent\Application\Command\RequestExternalTranslationMessage;
use App\TranslationComponent\Application\Query\SearchTranslationMessage;

#[AsCommand(name: 'app:translate', description: 'Translate text')]
class TranslateCommand extends Command
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SimpleQueryBus $queryBus,
        private readonly SimpleCommandBus $commandBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to translate a text');
        $this->addArgument('sourceText', InputArgument::REQUIRED, 'The text to translate');
        $this->addArgument('sourceLanguage', InputArgument::REQUIRED, 'The source language');
        $this->addArgument('targetLanguage', InputArgument::REQUIRED, 'The target language');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $translationSwagger = new TranslationSwagger();
        $translationSwagger->sourceText = $input->getArgument('sourceText');
        $translationSwagger->sourceLanguage = $input->getArgument('sourceLanguage');
        $translationSwagger->targetLanguage = $input->getArgument('targetLanguage');

        $errors = $this->validator->validate($translationSwagger);

        if (count($errors) > 0) {
            throw new ValidationException(constraintViolationList: $errors);
        }

        $result = $this->queryBus->handle(query: new SearchTranslationMessage(translationSwagger: $translationSwagger));

        if ($result === null) {
            $this->commandBus->dispatch(new QueueTranslationMessage(translationSwagger: $translationSwagger));
        }

        if ($translationSwagger->status === TranslationSwagger::STATUS_QUEUED) {
            $this->commandBus->dispatch(new RequestExternalTranslationMessage(translationSwagger: $translationSwagger));
            $output->writeln('<fg=green>Translation is not available in the database so it has been queued, try again later</>');
            return Command::SUCCESS;
        }

        $output->writeln('<fg=green>Translated text: ' . $translationSwagger->translatedText . '</>');

        return Command::SUCCESS;
    }
}

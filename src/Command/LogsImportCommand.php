<?php

namespace App\Command;

use App\Exception\LogFileNotAccessibleException;
use App\Exception\LogFileNotFoundException;
use App\Service\LogFileImporter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Service\Contracts\LogFileImporterInterface;
use Throwable;

#[AsCommand(
    name: 'logs:import',
    description: 'Parse a log file and import each line as record in the `logs` table.',
)]
class LogsImportCommand extends Command
{
    public const ARGUMENT_FILE = 'file';

    protected static $defaultName = 'logs:import';

    public function __construct(
        private ContainerInterface $container,
        private LogFileImporterInterface $logFileImporter,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            self::ARGUMENT_FILE,
            InputArgument::REQUIRED,
            'Full log file name (with path relative to the root directory)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $rootDir = $this->container->getParameter('kernel.project_dir');
        $fileName = $input->getArgument(self::ARGUMENT_FILE);
        $filePath = $rootDir . DIRECTORY_SEPARATOR . $fileName;

        try {
            set_time_limit(0);

            $importResult = $this->logFileImporter->processFile($filePath);
            $message = match ($importResult) {
                LogFileImporter::STATUS_ALREADY_FINISHED => 'Log file already imported.',
                LogFileImporter::STATUS_FINISHED => 'Log file successfully imported.',
            };

            $io->success($message . PHP_EOL);

            return Command::SUCCESS;
        } catch (LogFileNotFoundException|LogFileNotAccessibleException $e) {
            $io->error($e->getMessage() . PHP_EOL);

            return Command::INVALID;
        } catch (Throwable $e) {
            $io->error(
                sprintf(
                    'Unexpected exception caught: %s Please retry command execution.',
                    $e->getMessage() . '.' . PHP_EOL . $e->getTraceAsString()
                )
            );

            return Command::FAILURE;
        }
    }
}

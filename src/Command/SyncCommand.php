<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\SyncService;

class SyncCommand extends Command
{
    private $syncService;

    public function __construct(SyncService $syncService)
    {
        $this->syncService = $syncService;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $crmOrders = $this->syncService->getCrmOrders();
        $this->syncService->addOrders($crmOrders);

        $io = new SymfonyStyle($input, $output);
        $io->success('Done');

        return 0;
    }

}

<?php

namespace App\Command;

use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlogCommand extends Command
{
    /**
     * @var BlogRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(BlogRepository $repo, EntityManagerInterface $manager)
    {
        $this->repository = $repo;
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:blog-command')
            ->setDescription('Displays the list of all orders')
            ->setHelp('This command allows you to get the list of all orders if no id specified in options')
            ->addOption('list', 'l', InputOption::VALUE_OPTIONAL, '', false)
            ->addOption('id', 'i', InputOption::VALUE_OPTIONAL, '', false)
            ->addOption('save', 's', InputOption::VALUE_OPTIONAL, '', false);
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listValue = $input->getOption('list');
        $listOption = (null === $listValue);
        $idValue = $input->getOption('id');
        $idOption = (null === $idValue) || (is_string($idValue));

        $table = new Table($output);
        $table->setHeaderTitle('Orders');
        $rows = [];

        if (true === $listOption || true === $idOption) {
            if (true === $listOption) {
                $blogs = $this->repository->FindAll();
                foreach ($blogs as $blog) {
                    $rows[] = [
                        $blog->getID(),
                        $blog->getTitle(),
                        $blog->getIntroduction(),
                        $blog->getCreatedAt()->format('Y-m-d H:i:s'),
                        $blog->getVisible(),
                        $blog->getPopular(),
                        $blog->getTrending(),
                    ];
                }
            }
            $table
            ->setHeaders(['ID', 'Title', 'Introduction', 'Created at', 'Visiible', 'Popular', 'Trending'])
            ->setRows($rows);
            $table->render();

            return 0;
        } else {
            $formattedLine = $formatter->formatSection(
                'OrderCommand',
                'This command allows you to get all orders, filtred by  by id, set the status of this order or name'
            );
            $output->writeln($formattedLine);

            return 0;
        }
    }
}

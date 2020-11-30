<?php declare(strict_types=1);

namespace Mac\DataTest\Command;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\MailTemplate\Aggregate\MailHeaderFooter\MailHeaderFooterDefinition;
use Shopware\Core\Content\MailTemplate\MailTemplateDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Content\Product\Aggregate\ProductManufacturer\ProductManufacturerDefinition;
use Shopware\Core\Content\Product\Aggregate\ProductReview\ProductReviewDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Content\ProductStream\ProductStreamDefinition;
use Shopware\Core\Content\Property\PropertyGroupDefinition;
use Shopware\Core\Framework\Adapter\Console\ShopwareStyle;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Demodata\DemodataRequest;
use Shopware\Core\Framework\Demodata\DemodataService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DataTestCommand extends Command
{
    const MAX_OF_ITEMS = 1000;

    protected static $defaultName = 'mac:datatest';

    /**
     * @var DemodataService
     */
    private $demodataService;

    public function __construct(DemodataService $demodataService) {
        parent::__construct();
        $this->demodataService = $demodataService;
    }

    protected function configure(): void
    {
        $this->addArgument('entity', InputArgument::REQUIRED, 'entity name');
        $this->addArgument('number', InputOption::VALUE_REQUIRED, 'Number of data', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = $input->getArgument('entity');
        $number = (int) $input->getArgument('number');
        $output->writeln("Entity name: $entity");
        if ($number > self::MAX_OF_ITEMS) {
            $output->writeln("Number of items to big, we're supporting for less than 1000 items");
            return 0;
        }

        $output->writeln("Number of items: $number");

        $io = new ShopwareStyle($input, $output);
        $io->title('Data test Generator');

        $context = Context::createDefaultContext();

        $request = new DemodataRequest();

        switch ($entity) {
            case MediaDefinition::ENTITY_NAME:
                $request->add(MediaDefinition::class, $number);
                break;
            case CustomerDefinition::ENTITY_NAME:
                $request->add(CustomerDefinition::class, $number);
                break;
            case PropertyGroupDefinition::ENTITY_NAME:
                $request->add(PropertyGroupDefinition::class, $number);
                break;
            case CategoryDefinition::ENTITY_NAME:
                $request->add(CategoryDefinition::class, $number);
                break;
            case ProductManufacturerDefinition::ENTITY_NAME:
                $request->add(ProductManufacturerDefinition::class, $number);
                break;
            case ProductDefinition::ENTITY_NAME:
                $request->add(ProductDefinition::class, $number);
                break;
            case ProductStreamDefinition::ENTITY_NAME:
                $request->add(ProductStreamDefinition::class, $number);
                break;
            case OrderDefinition::ENTITY_NAME:
                $request->add(OrderDefinition::class, $number);
                break;
            case ProductReviewDefinition::ENTITY_NAME:
                $request->add(ProductReviewDefinition::class, $number);
                break;
            case MailTemplateDefinition::ENTITY_NAME:
                $request->add(MailTemplateDefinition::class, $number);
                break;
            case MailHeaderFooterDefinition::ENTITY_NAME:
                $request->add(MailHeaderFooterDefinition::class, $number);
                break;
            default:
                $output->writeln('We did not supported this entity, please check again');

                return 0;
        }

        $demoContext = $this->demodataService->generate($request, $context, $io);

        $io->table(
            ['Entity', 'Items', 'Time'],
            $demoContext->getTimings()
        );

        return 0;
    }
}

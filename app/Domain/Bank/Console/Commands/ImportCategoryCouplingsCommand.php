<?php
/**
 * User: gmatk
 * Date: 21.06.2022
 * Time: 13:36
 */

namespace App\Domain\Bank\Console\Commands;

use App\Application\Aggregations\CategoryCoupling\CategoryCouplingAggregation;
use App\Application\Dto\CategoryCoupling\CategoryCouplingDto;
use App\Application\Repositories\BankRepository;
use App\Application\Repositories\CategoryCouplingRepository;
use App\Application\Repositories\CategoryRepository;
use App\Domain\Bank\Entities\Bank;
use App\Domain\Bank\Reports\ReportService;
use App\Domain\Category\Entities\Category;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 *
 */
class ImportCategoryCouplingsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'bank:import-category-couplings';
    /**
     * @var string
     */
    protected $description = 'Import some category couplings from banks';

    /**
     * @var Collection
     */
    protected Collection $categories;

    /**
     * @param BankRepository $bankRepository
     * @param CategoryRepository $categoryRepository
     * @param CategoryCouplingRepository $categoryCouplingRepository
     */
    public function handle(
        BankRepository $bankRepository,
        CategoryRepository $categoryRepository,
        CategoryCouplingRepository $categoryCouplingRepository
    ): void {
        /**
         * @var Collection $banks ;
         */
        $banks = $bankRepository->all();

        $bankChoice = $this->choice('Bank?', $banks->pluck('name')->toArray());
        $fileLocation = $this->ask('File?', storage_path('app/banks/mbank.json'));
        /**
         * @var Bank $bank
         */
        $bank = $banks->where('name', $bankChoice)->first();

        if (!File::exists($fileLocation)) {
            $this->error('File not exists');
            return;
        }

        if (!$strategy = $bank->getReportStrategy()) {
            $this->error('Bank strategy not exists');
            return;
        }

        $reportService = new ReportService($strategy);
        $couplings = $reportService->getCategories(
            $reportService->decode(File::get($fileLocation), File::extension($fileLocation))
        );

        if (count($couplings) === 0) {
            $this->warn('Categories not found!');
            return;
        }

        $this->categories = $categoryRepository->all();

        $this->handleCouplings($couplings, $categoryCouplingRepository);

        $this->info('Category couplings assigned');
    }

    /**
     * @param array $couplings
     * @param CategoryCouplingRepository $categoryCouplingRepository
     */
    protected function handleCouplings(
        array $couplings,
        CategoryCouplingRepository $categoryCouplingRepository
    ): void {
        DB::transaction(function () use ($couplings, $categoryCouplingRepository) {
            foreach ($couplings as $coupling) {
                $this->handleCoupling($coupling, $categoryCouplingRepository);
            }
        });
    }

    /**
     * @param string $coupling
     * @param CategoryCouplingRepository $categoryCouplingRepository
     */
    protected function handleCoupling(
        string $coupling,
        CategoryCouplingRepository $categoryCouplingRepository
    ): void {
        if ($categoryCouplingRepository->findByName($coupling)) {
            return;
        }

        /**
         * @var Category $category
         */
        if (!$category = $this->categories->where('name', $coupling)->first()) {
            $categoryChoice = $this->choice(
                sprintf('"%s" to couple with:', $coupling),
                $this->categories->pluck('name')->toArray()
            );
            $category = $this->categories->where('name', $categoryChoice)->first();
        }

        $uuid = (string)Str::uuid();
        CategoryCouplingAggregation::retrieve($uuid)
            ->create(
                new CategoryCouplingDto($uuid, $coupling, $category->getKey())
            )
            ->persist();

        $this->info(sprintf('Assigned %s with %s', $coupling, $category->name));
    }
}

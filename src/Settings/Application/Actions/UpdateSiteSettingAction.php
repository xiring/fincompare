<?php
namespace Src\Settings\Application\Actions;

use Src\Settings\Domain\Entities\SiteSetting;
use Src\Settings\Domain\Repositories\SiteSettingRepositoryInterface;
use Src\Settings\Application\DTOs\SiteSettingDTO;

class UpdateSiteSettingAction
{
    public function __construct(private readonly SiteSettingRepositoryInterface $repository)
    {
    }

    public function execute(SiteSettingDTO $dto): SiteSetting
    {
        return $this->repository->update($dto->toArray());
    }
}



<?php

declare(strict_types=1);

namespace App\Services\Application\InputData;

use App\Infrastructures\Models\Eloquent\GeneralSettingCategory;
use App\Infrastructures\Models\Eloquent\UserGeneralSetting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

final class SettingGeneralSaveRequest
{
    private $userGeneralSettings;

    public function __construct(
        \App\Http\Requests\Frontend\Setting\SaveGeneralRequest $saveRequest
    ) {
        $this->userGeneralSettings = new Collection();

        $generalSettingParameter = $saveRequest->request->all();
        $generalSettingCategories = GeneralSettingCategory::all();

        foreach ($generalSettingCategories as $generalSettingCategory) {
            $userGeneralSetting = new UserGeneralSetting([
                'user_id' => Auth::id(),
                'general_id' => $generalSettingCategory->id,
                'enabled' => $generalSettingParameter[$generalSettingCategory->name],
            ]);

            $this->userGeneralSettings->push($userGeneralSetting);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInput(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->userGeneralSettings;
    }
}

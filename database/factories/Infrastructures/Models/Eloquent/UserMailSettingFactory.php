<?php

declare(strict_types=1);

namespace Database\Factories\Infrastructures\Models\Eloquent;

use App\Infrastructures\Models\Eloquent\MailCategory;
use App\Infrastructures\Models\Eloquent\User;
use App\Infrastructures\Models\Eloquent\UserMailSetting;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserMailSettingFactory extends Factory
{
    protected $model = UserMailSetting::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'mail_category_id' => MailCategory::factory(),
            'notice_number_days' => $this->faker->numberBetween(1, 365),
            'is_received' => $this->faker->boolean,
            'updated_at' => now(),
            'created_at' => now(),
        ];
    }
}

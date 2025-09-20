<?php

namespace App\Console\Commands;

use App\Services\ExchangeSiteService;
use App\Models\ExchangeRate;
use Illuminate\Console\Command;

class UpdateExchangeRates extends Command
{
    protected $signature = 'exchange:update';
    protected $description = 'Обновляет курсы валют через ExchangeSiteService';

    public function handle()
    {
        $this->info('Получаю курсы валют...');

        try {
            $rates = ExchangeSiteService::getRates();

            foreach ($rates as $currency => $rate) {
                ExchangeRate::query()->updateOrCreate(
                    ['currency' => $currency],
                    [
                        'base' => 'USD',
                        'rate' => $rate
                    ]
                );

                $this->line("$currency: $rate сохранено в БД");
            }

            $this->info('Курсы валют успешно обновлены и сохранены в таблице.');
        } catch (\Exception $e) {
            $this->error('Ошибка при получении курсов: ' . $e->getMessage());
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Rate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateSeeder extends Seeder
{
    /**
     * Заполнить таблицу rate_information
     * @see Rate
     *
     * @return void
     */
    public function run(): void
    {
        $xml = simplexml_load_file(config('constants.source'));
        $rates = $xml->children()->Valute;
        foreach ($rates as $rate) {
            $attributes = [];
            $attributes[] = (string) $rate->attributes()->ID;
            $attributes[] = (string) $rate->children()->NumCode;
            $attributes[] = (string) $rate->children()->CharCode;
            $attributes[] = (int) $rate->children()->Nominal;
            $attributes[] = (string) $rate->children()->Name;
            // Сохранение в базу данных
            DB::table('rate_information')->insert([
                'id' => $attributes[0],
                'num_code' => $attributes[1],
                'char_code' => $attributes[2],
                'nominal' => $attributes[3],
                'name' => $attributes[4],
            ]);
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Seed the Currency table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            ['currency' => 'Austrālijas dolārs', 'code' => 'AUD'],
            ['currency' => 'Bulgārijas leva', 'code' => 'BGN'],
            ['currency' => 'Brazīlijas reāls', 'code' => 'BRL'],
            ['currency' => 'Kanādas dolārs', 'code' => 'CAD'],
            ['currency' => 'Šveices franks', 'code' => 'CHF'],
            ['currency' => 'Ķīnas juaņa renminbi', 'code' => 'CNY'],
            ['currency' => 'Čehijas krona', 'code' => 'CZK'],
            ['currency' => 'Dānijas krona', 'code' => 'DKK'],
            ['currency' => 'Lielbritānijas sterliņu mārciņa', 'code' => 'GBP'],
            ['currency' => 'Hongkongas dolārs', 'code' => 'HKD'],
            ['currency' => 'Horvātijas kuna', 'code' => 'HRK'],
            ['currency' => 'Ungārijas forints', 'code' => 'HUF'],
            ['currency' => 'Indonēzijas rūpija', 'code' => 'IDR'],
            ['currency' => 'Izraēlas šekelis', 'code' => 'ILS'],
            ['currency' => 'Indijas rūpija', 'code' => 'INR'],
            ['currency' => 'Islandes krona', 'code' => 'ISK'],
            ['currency' => 'Japānas jena', 'code' => 'JPY'],
            ['currency' => 'Dienvidkorejas vona', 'code' => 'KRW'],
            ['currency' => 'Meksikas peso', 'code' => 'MXN'],
            ['currency' => 'Malaizijas ringits', 'code' => 'MYR'],
            ['currency' => 'Norvēģijas krona', 'code' => 'NOK'],
            ['currency' => 'Jaunzēlandes dolārs', 'code' => 'NZD'],
            ['currency' => 'Filipīnu peso', 'code' => 'PHP'],
            ['currency' => 'Polijas zlots', 'code' => 'PLN'],
            ['currency' => 'Rumānijas leja', 'code' => 'RON'],
            ['currency' => 'Krievijas rublis', 'code' => 'RUB'],
            ['currency' => 'Zviedrijas krona', 'code' => 'SEK'],
            ['currency' => 'Singapūras dolārs', 'code' => 'SGD'],
            ['currency' => 'Taizemes bats', 'code' => 'THB'],
            ['currency' => 'Turcijas lira', 'code' => 'TRY'],
            ['currency' => 'ASV dolārs', 'code' => 'USD'],
            ['currency' => 'Dienvidāfrikas rends', 'code' => 'ZAR']
        ]);
    }
}

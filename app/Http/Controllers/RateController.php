<?php


namespace App\Http\Controllers;

use App\Rate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RateController extends Controller
{
    /**
     * Get and store currency rates from the RSS feed of the central bank of Latvia.
     */
    function getRates()
    {
        $rss = file_get_contents('https://www.bank.lv/vk/ecb_rss.xml');

        // Get RSS XML as an object and merge CDATA as text nodes.
        $rss = simplexml_load_string($rss, null, LIBXML_NOCDATA);

        // Iterate through all the items of the XML object.
        foreach ($rss->channel->item as $item) {
            $rate_date = date('Y-m-d H:i:s', strtotime($item->pubDate));
            $rates = explode(' ', trim($item->description));

            // Store currency rates for the day only once.
            if (DB::table('rates')->whereDate('rate_date', '=', date('Y-m-d', strtotime($rate_date)))->doesntExist()) {
                for ($i = 0; $i < count($rates); $i++) {
                    $rate = new Rate;

                    $rate->code = $rates[$i];
                    $rate->rate = $rates[++$i];
                    $rate->rate_date = $rate_date;

                    $rate->save();
                }
            }
        }
    }

    /**
     * Return the latest available currency rates to the rate view.
     *
     * @return View
     */
    function showRates()
    {
        $date = date('Y-m-d', time());

        // Fetch yesterday's rates if there are no rates available for today.
        if (DB::table('rates')->whereDate('rate_date', '=', $date)->doesntExist()) {
            $date = date('Y-m-d', time() - 24 * 60 * 60);
        }

        $rates = DB::table('rates')
            ->select('currencies.currency', 'rates.code', 'rates.rate')
            ->join('currencies', 'rates.code', '=', 'currencies.code')
            ->whereDate('rates.rate_date', '=', $date)
            ->orderBy('currencies.currency')
            ->paginate(10);

        return view('rates', ['rates' => $rates, 'date' => date('d.m.Y', strtotime($date))]);
    }
}

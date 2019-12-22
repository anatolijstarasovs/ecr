<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Eiro valūtas kursi</title>

    <!-- Styles -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<main class="flex-center">
    <div class="container flex-center">
        <h1>
            Eiro valūtas kursi
        </h1>
        <p>{{ $date }}</p>
        <table class="table table-bordered table-hover table-sm">
            <thead>
            <tr>
                <th scope="col">Valūta</th>
                <th scope="col">Kods</th>
                <th scope="col">Kurss</th>
            </tr>
            </thead>
            <tbody>
            @if (count($rates) > 0)
                @foreach ($rates as $rate)
                    <tr>
                        <td>{{ $rate->currency }}</td>
                        <td>{{ $rate->code }}</td>
                        <td>{{ $rate->rate }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3">Valūtas kursi vēl nav pieejami.</td>
                </tr>
            @endif
            </tbody>
        </table>
        @if (count($rates) > 0)
            {{ $rates->links() }}
        @endif
        <p>
            Avots: <a href="https://www.bank.lv/statistika/dati-statistika/valutu-kursi/aktualie" target="_blank">www.bank.lv</a>
        </p>
    </div>
</main>
</body>
</html>

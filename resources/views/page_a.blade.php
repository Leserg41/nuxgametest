<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page A</title>
</head>
<body>
    <h1>Page A</h1>
    <form method="POST" action="/registration/{{ $registration->unique_code }}/regenerate">
        @csrf
        <button type="submit">Regenerate Unique Number</button>
    </form>
    <form method="POST" action="/registration/{{ $registration->unique_code }}/deactivate">
        @csrf
        <button type="submit">Deactivate</button>
    </form>
    <form method="POST" action="/registration/{{ $registration->unique_code }}/lucky">
        @csrf
        <button type="submit">I'm feeling lucky</button>
    </form>
    <form method="GET" action="/registration/{{ $registration->unique_code }}/history" style="display:inline;">
        <button type="submit">View History</button>
    </form>
    @if(!empty($showHistory) && !empty($history) && $history->isNotEmpty())
        <h2>Last 3 Results</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Result</th>
                    <th>Win Sum</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $entry)
                    <tr>
                        <td>{{ $entry->random_number }}</td>
                        <td>{{ $entry->is_win ? 'Win' : 'Lost' }}</td>
                        <td>{{ $entry->win_sum !== null ? $entry->win_sum : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    @if(session('lucky'))
        <p>{{ session('lucky') }}</p>
    @endif
</body>
</html>
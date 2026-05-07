<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
</head>
<body>
    <h1>Game History</h1>
    <a href="/registration/{{ $registration->unique_code }}">Back to Page A</a>
    <table border="1">
        <thead>
            <tr>
                <th>Number</th>
                <th>Result</th>
                <th>Win Sum</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($history as $entry)
                <tr>
                    <td>{{ $entry->random_number }}</td>
                    <td>{{ $entry->is_win ? 'Win' : 'Lost' }}</td>
                    <td>{{ $entry->win_sum ? $entry->win_sum : '-' }}</td>
                    <td>{{ $entry->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
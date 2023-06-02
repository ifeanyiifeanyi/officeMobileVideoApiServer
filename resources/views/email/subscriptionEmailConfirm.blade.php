<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }

        h2 {
            color: #333333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table th,
        table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }

        table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Subscription Activation</h2>

    <p>Hello {{ $user->name }},</p>

    <p>Your subscription plan has been successfully activated. Here are the details:</p>

    <table>
        <tr>
            <th>Plan Name:</th>
            <td>{{ $plan_name }}</td>
        </tr>
        <tr>
            <th>Amount:</th>
            <td>{{ $amount }}</td>
        </tr>
        <tr>
            <th>Activation Date:</th>
            <td>{{ $activated_at }}</td>
        </tr>
    </table>

    <p>Thank you for subscribing to our service.</p>

    <p>Regards,<br>
    {{ config('app.name') }} </p>
</body>
</html>

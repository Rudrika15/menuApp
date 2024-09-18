<!DOCTYPE html>
<html>
<head>
    <title>StaffMail</title>
</head>
<body>
    <h1>{{ $mailData['title'] }}</h1>
    <p>{{ $mailData['body'] }}</p>

    Dear  <strong>{{ $mailData['name']}}</strong>,
    <p>Welcome to {{$mailData['restaurantName']}}.</p>
    <p>You Are Hired at Position of Staff<strong>{{$mailData['staffType']}}</strong></p>
    <table border="1" cellpadding="10" cellspacing="0" width="100%" style="border-collapse: collapse; text-align: center;">
    <tr>
        <td>Username</td>
        <td>{{$mailData['email']}}</td>
    </tr>
    <tr>
        <td>Password</td>
        <td>{{$mailData['password']}}</td>
    </tr>
    </table>
     
    <p>Thank you</p>
</body>
</html>
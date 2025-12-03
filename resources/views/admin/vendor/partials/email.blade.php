<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h2>Hello {{ $user->name }}</h2>
    <p>Your account has been created successfully.</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Password:</strong> {{ $randomPassword }}</p>
    <p>Please login and change your password.</p>
</body>
</html>

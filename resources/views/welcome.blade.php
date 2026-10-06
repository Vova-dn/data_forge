<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <div class="login">
        <form method="POST" action="/login">
            @csrf
            <input placeholder="Login" type="text" name="login">
            <input placeholder="Password" type="password" name="password">
            <button type="submit">Sign in</button>
        </form>

    </div>
</body>

</html>

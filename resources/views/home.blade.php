<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <form action="/datasets" method="POST">
        @csrf
        <input type="file" name="file" accept=".csv,text/plain" required>
        @error('file')
        <p>{{$message}}</p>
        @enderror
        <button type="submit">Загрузить</button>
    </form>

</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test API</title>
</head>
<body>
    @foreach ($files as $file)
        <img src="{{ $file['url'] }}" alt="" width="200">
    @endforeach
</body>
</html>
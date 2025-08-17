<!DOCTYPE html>
<html>
<head><title>{{ $player->first_name }}</title></head>
<body>
<h1>{{ $player->first_name }} {{ $player->last_name }}</h1>
<img src="{{ $player->photo_url }}" alt="Photo">
</body>
</html>
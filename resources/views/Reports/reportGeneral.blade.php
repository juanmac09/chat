<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Informe de Actividad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1,
        h2,
        p {
            margin: 0 0 10px;
        }

        h1 {
            font-size: 28px;
            color: #333;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            color: #555;
        }

        .group {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .group p {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .group h2 {
            margin-bottom: 15px;
            font-size: 20px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Informe de Actividad</h1>
        <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>

        <div class="group">
            <h2>Resumen de Actividad</h2>
            <p><strong>Usuarios Activos:</strong> {{ $activeUser }}</p>
            <p><strong>Usuarios Inactivos:</strong> {{ $inactiveUser }}</p>
            <p><strong>Grupos Activos:</strong> {{ $activeGroups }}</p>
            <p><strong>Grupos Inactivos:</strong> {{ $inactiveGroups }}</p>
        </div>

        <div class="group">
            <h2>Usuarios por Grupo</h2>
            @foreach ($groupParticipants as $group)
                <p><strong>{{ $group->name }}:</strong> {{ $group->users_quantity }}</p>
            @endforeach
        </div>
    </div>
</body>

</html>

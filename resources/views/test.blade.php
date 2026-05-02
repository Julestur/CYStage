<!DOCTYPE html>
<html>
<head>
    <title>Test BDD Laravel</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 300px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>

    <h1>Liste des classes (depuis la BDD)</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom de la classe</th>
            </tr>
        </thead>
        <tbody>
            {{-- On boucle sur les données envoyées par le contrôleur --}}
            @foreach($classes as $c)
                <tr>
                    <td>{{ $c->idClasse }}</td>
                    <td>{{ $c->nom }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
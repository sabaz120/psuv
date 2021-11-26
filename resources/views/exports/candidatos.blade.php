<table>
    <thead>
    <tr>
        <th colspan="4">Elección: {{$eleccion->nombre}}</th>
    </tr>
    <tr>
        <th>Cargo</th>
        <th>Municipio</th>
        <th>Nombre</th>
        <th>Apellido</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->cargo_eleccion }}</td>
            <td>{{ $result->municipio->nombre }}</td>
            <td>{{ $result->nombre }}</td>
            <td>{{ $result->apellido }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
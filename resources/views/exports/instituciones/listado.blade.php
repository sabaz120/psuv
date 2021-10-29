<table>
    <thead>
    <tr>
        <th>Institución</th>
        <th>Municipio</th>
        <th>Parroquia</th>
        <th>Código centro votación</th>
        <th>Centro votación</th>
        <th>Cedula</th>
        <th>Nombre trabajador</th>
        <th>Cargo</th>
        <th>Dirección</th>
        <th>Ejercio voto</th>
        <th>Movilización</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->institucion }}</td>
            <td>{{ $result->municipio }}</td>
            <td>{{ $result->parroquia }}</td>
            <td>{{ $result->codigo_centro_votacion }}</td>
            <td>{{ $result->centro_votacion }}</td>
            <td>{{ $result->cedula }}</td>
            <td>{{ $result->nombre_trabajador }}</td>
            <td>{{ $result->cargo }}</td>
            <td>{{ $result->direccion }}</td>
            <td>{{ $result->ejercio_voto }}</td>
            <td>{{ $result->movilizacion_nombre }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
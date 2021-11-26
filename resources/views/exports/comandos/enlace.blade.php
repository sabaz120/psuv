<table>
    <thead>
    <tr>
        <th>MUNICIPIO</th>
        <th>PARROQUIA</th>
        <th>CENTRO DE VOTACIÓN</th>
        <th>NOMBRE Y APELLIDO</th>
        <th>CÉDULA</th>
        <th>TELÉFONO</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->centroVotacion->parroquia->municipio->nombre }}</td>
            <td>{{ $result->centroVotacion->parroquia->nombre }}</td>
            <td>{{ $result->centroVotacion->nombre }}</td>
            <td>{{ $result->personalCaracterizacion->primer_nombre }} {{ $result->personalCaracterizacion->primer_apellido }}</td>
            <td>{{ $result->personalCaracterizacion->cedula }}</td>
            <td>{{ $result->personalCaracterizacion->telefono_principal }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
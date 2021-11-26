<table>
    <thead>
    <tr>
        <th>MUNICIPIO</th>
        <th>COMISIÓN DE TRABAJO</th>
        <th>RESPONSABILIDAD</th>
        <th>NOMBRE Y APELLIDO</th>
        <th>CÉDULA</th>
        <th>TELÉFONO</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->municipio->nombre }}</td>
            <td>{{ $result->comisionTrabajo->nombre_comision }}</td>
            <td>{{ $result->responsabilidadComando->nombre }}</td>
            <td>{{ $result->personalCaracterizacion->primer_nombre }} {{ $result->personalCaracterizacion->primer_apellido }}</td>
            <td>{{ $result->personalCaracterizacion->cedula }}</td>
            <td>{{ $result->personalCaracterizacion->telefono_principal }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
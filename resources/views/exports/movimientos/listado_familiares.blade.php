<table>
    <thead>
    <tr>
        <th>Movimiento</th>
        <th>Cedula trabajador</th>
        <th>Trabajador</th>
        <th>Teléfono trabajador</th>
        <th>Familiar</th>
        <th>Teléfono principal</th>
        <th>Municipio</th>
        <th>Parroquia</th>
        <th>Código centro votación</th>
        <th>Centro votación</th>
        <th>Ejercio voto</th>
        <th>Movilización</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->movimiento }}</td>
            <td>{{ $result->cedula_personal }}</td>
            <td>{{ $result->personal }}</td>
            <td>{{ $result->telefono_personal }}</td>
            <td>{{ $result->familiar }}</td>
            <td>{{ $result->telefono_principal }}</td>
            <td>{{ $result->municipio }}</td>
            <td>{{ $result->parroquia }}</td>
            <td>{{ $result->codigo_centro_votacion }}</td>
            <td>{{ $result->centro_votacion }}</td>
            @if($result->ejercio_voto=="f")
            <td>SI</td>
            @else
            <td>NO</td>
            @endif
            <td>{{ $result->movilizacion }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
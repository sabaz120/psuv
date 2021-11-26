<table>
    <thead>
    <tr>
        <th>Movimiento</th>
        <th>Municipio</th>
        <th>Parroquia</th>
        <th>Código centro votación</th>
        <th>Centro votación</th>
        <th>Cedula</th>
        <th>Nombre trabajador</th>
        <th>Cargo</th>
        <th>Área atención</th>
        <th>Nivel estructura</th>
        <th>Dirección</th>
        <th>Ejercio voto</th>
        <th>Movilización</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->movimiento }}</td>
            <td>{{ $result->municipio }}</td>
            <td>{{ $result->parroquia }}</td>
            <td>{{ $result->codigo_centro_votacion }}</td>
            <td>{{ $result->centro_votacion }}</td>
            <td>{{ $result->cedula }}</td>
            <td>{{ $result->personal }}</td>
            <td>{{ $result->cargo }}</td>
            <td>{{ $result->area_atencion }}</td>
            <td>{{ $result->nivel_estructura }}</td>
            <td>{{ $result->direccion }}</td>
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
<table>
    <thead>
    <tr>
        <th>Municipio</th>
        <th>Parroquia</th>
        <th>Comunidad</th>
        <th>Calle</th>
        <th>Cédula Jefe Calle</th>
        <th>Jefe Calle</th>
        <th>Cédula Jefe Familia</th>
        <th>Jefe Familia</th>
        <th>Cédula Familiar</th>
        <th>Familiar</th>
        <th>Teléfono Principal</th>
        <th>Centro Votación</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->municipio }}</td>
            <td>{{ $result->nombre }}</td>
            <td>{{ $result->comunidad }}</td>
            <td>{{ $result->calle }}</td>
            <td>{{ $result->cedula_jefe_calle }}</td>
            <td>{{ $result->nombre_jefe_calle }}</td>
            <td>{{ $result->cedula_jefe_familia }}</td>
            <td>{{ $result->nombre_jefe_familia }}</td>
            <td>{{ $result->cedula_familiar }}</td>
            <td>{{ $result->nombre_familiar }}</td>
            <td>{{ $result->telefono_principal }}</td>
            <td>{{ $result->centro_votacion }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
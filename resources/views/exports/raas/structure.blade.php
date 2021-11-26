<table>
    <thead>
    <tr>
        <th>Municipio</th>
        <th>Parroquia</th>
        <th>Código centro de votación</th>
        <th>Centro de votación</th>
        <th>Cédula Jefe UBCH</th>
        <th>Jefe UBCH</th> 
        <th>Teléfono Jefe UBCH</th>
        <th>Comunidad</th>
        <th>Cédula Jefe Comunidad</th>
        <th>Jefe Comunidad</th>
        <th>Teléfono Jefe Comunidad</th>
        <th>Calle</th>
        <th>Cédula Jefe Calle</th>
        <th>Jefe Calle</th>
        <th>Teléfono Jefe Calle</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->municipio }}</td>
            <td>{{ $result->parroquia }}</td>
            <td>{{ $result->codigo_ubch }}</td>
            <td>{{ $result->nombre_ubch }}</td>
            <td>{{ $result->cedula }}</td>
            <td>{{ $result->jefe_ubch }}</td>
            <td>{{ $result->telefono1_jefe_ubch }}</td>
            <td>{{ $result->comunidad }}</td>
            <td>{{ $result->cedula_jefe_comunidad }}</td>
            <td>{{ $result->jefe_comunidad }}</td>
            <td>{{ $result->telefono1_jefe_comunidad }}</td>
            <td>{{ $result->calle }}</td>
            <td>{{ $result->cedula_jefe_calle }}</td>
            <td>{{ $result->jefe_calle }}</td>
            <td>{{ $result->telefono1_jefe_calle }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
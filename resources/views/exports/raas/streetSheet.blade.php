<table>
    <thead>
    <tr>
        <th style='background-color: gray; color: white;'>Municipio</th>
        <th style='background-color: gray; color: white;'>Parroquia</th>
        <th style='background-color: gray; color: white;'>Código UBCH</th>
        <th style='background-color: gray; color: white;'>UBCH</th>
        <th style='background-color: gray; color: white;'>Comunidad</th>
        <th style='background-color: gray; color: white;'>Calle</th> 
        <th style='background-color: gray; color: white;'>Rol</th>
        <th style='background-color: gray; color: white;'>Cédula</th>
        <th style='background-color: gray; color: white;'>Nombre</th>
        <th style='background-color: gray; color: white;'>Género</th>
        <th style='background-color: gray; color: white;'>Teléfono</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->municipio }}</td>
            <td>{{ $result->parroquia }}</td>
            <td>{{ $result->codigo_ubch }}</td>
            <td>{{ $result->nombre_ubch }}</td>
            <td>{{ $result->comunidad }}</td>
            <td>{{ $result->calle }}</td>
            <td>{{ $result->roles }}</td>
            <td>{{ $result->cedula_equipo_comunidad }}</td>
            <td>{{ $result->equipo_comunidad }}</td>
            <td>{{ $result->genero }}</td>
            <td>{{ $result->telefono_equipo_comunidad }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<table>
    <thead>
    <tr>
        <th style='background-color: gray; color: white;'>Estructura de Base</th>
        <th style='background-color: gray; color: white;'>Municipio</th>
        <th style='background-color: gray; color: white;'>Parroquia</th>
        <th style='background-color: gray; color: white;'>UBCH</th>
        <th style='background-color: gray; color: white;'>Comunidad</th>
        <th style='background-color: gray; color: white;'>Calle</th> 
        <th style='background-color: gray; color: white;'>Cédula</th>
        <th style='background-color: gray; color: white;'>Nombre</th>
        <th style='background-color: gray; color: white;'>Teléfono</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->estructura_base }}</td>
            <td>{{ $result->municipio }}</td>
            <td>{{ $result->parroquia }}</td>
            <td>{{ $result->nombre_ubch }}</td>
            <td>{{ $result->comunidad??"" }}</td>
            <td>{{ $result->calle??"" }}</td>
            <td>{{ $result->cedula }}</td>
            <td>{{ $result->nombre }}</td>
            <td>{{ $result->telefono }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
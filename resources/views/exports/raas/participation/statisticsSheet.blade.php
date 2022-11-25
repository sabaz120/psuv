<table>
    <thead>
    <tr>
        <th style='background-color: gray; color: white;'>MUNICIPIO</th>
        <th style='background-color: gray; color: white;'>PARROQUIA</th>
        <th style='background-color: gray; color: white;'>CÓDIGO UBCH</th>
        <th style='background-color: gray; color: white;'>UBCH</th>
        <th style='background-color: gray; color: white;'>COMUNIDAD</th>
        <th style='background-color: gray; color: white;'>CALLE</th> 
        <th style='background-color: gray; color: white;'>TOTAL DE PARTICIPACIÓN</th>
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
            <td>{{ $result->total_participacion }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
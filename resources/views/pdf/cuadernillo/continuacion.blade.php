<style>

    #table-2 td{
        font-size: 10px;
    }

    #table-2{
        width: 100%;
    }
    #table-2, #table-2 th, #table-2 td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    #table-2 th,#table-2 td{
        padding-top: 10px;
        padding-bottom: 10px;
        padding-left: 10px;
    }

    #table-2 th{
        background-color: #eee;
    }

</style>

<table id="table-2">
    <thead>
        <tr>
            <th<b>Código</b></th>
            <th<b>Cédula</b></th>
            <th<b>Nombre</b></th>
            <th<b>Caracterizados</b></th>
            <th<b>Firma</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach($votaciones as $votacion)
            <tr>
                <td style="width: 60px;">{{ $votacion["codigo_cuadernillo"] }}</td>
                <td style="width: 100px;">{{ $votacion["cedula"] }}</td>
                <td>{{ $votacion["nombre_completo"] }}</td>
                <td  style="width: 100px;">@if($votacion["caracterizacion"] >= 1) Sí @endif</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>

</table>
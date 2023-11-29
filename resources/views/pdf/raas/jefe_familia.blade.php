<style>

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

    #table-2 td{
        font-size: 12px;
    }

</style>

<table style="width: 100%;">
    <tr>
        <td>
            <img src="{{ public_path('psuv.png') }}" style="width: 190px; float: right;">
        </td>
    </tr>
    <tr><td>
        <h1 style="visibility:hidden;">A</h1>
    </td></tr>
    <tr><td>
        <h1 style="visibility:hidden;">A</h1>
    </td></tr>
    <tr><td>
        <h1 style="visibility:hidden;">A</h1>
    </td></tr>
    <tr><td>
        <h1 style="visibility:hidden;">A</h1>
    </td></tr>
    <tr >
        <td>
            <h2 style="text-align:center;">Sistema Integral Regional de la Vicepresidencia de la Organización PSUV Falcón.</h2>
        </td>
    
    </tr>
    <tr><td>
        <h1 style="visibility:hidden;">A</h1>
    </td></tr>
    <tr><td>
        <h1 style="visibility:hidden;">A</h1>
    </td></tr>
    <tr><td>
        <h1 style="visibility:hidden;">A</h1>
    </td></tr>

    <tr>
        <td>
            <p style="text-align: right;">
                <b>Reporte 1 * Calle</b>
            </p>
        </td>
    </tr>

    <tr>
        <td>
            <p style="text-align: right;">Jefe de Calle</p>
        </td>
    </tr>


</table>
<div style="page-break-after: always;"></div>

<table>
    <tr>
        <td>
            <p>
                <b>Datos del Jefe de Calle</b> </span>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p>
                <b>Cédula:</b> <span style="text-decoration: underline;">{{ $datos->cedula }}</span>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p>
                <b>Nombre:</b> <span style="text-decoration: underline;">{{ $datos->nombre_completo }}</span>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p>
                <b>Municipio:</b> <span style="text-decoration: underline;">{{ $datos->municipio_nombre }}</span>
            </p>
        </td>
        <td>
            <p>
                <b>Parroquia:</b> <span style="text-decoration: underline;">{{ $datos->parroquia_nombre }}</span>
            </p>
        </td>
        <td>
            <p>
                <b>Comunidad:</b> <span style="text-decoration: underline;">{{ $datos->comunidad_nombre }}</span>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p>
                <b>Total de 1*Calle:</b> <span style="text-decoration: underline;">{{ $datos->cantidad_familiares }}</span>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p>
                <b style="text-decoration: underline;">Listado de 1*Calle</b>
            </p>
        </td>
    </tr>
</table>

<table id="table-2">
    <thead>
        <tr>
            <th><b>Cédula</b></th>
            <th><b>Nombre</b></th>
            <th><b>Teléfono</b></th>
            <th><b>Centro de Votación</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach($datos->familiares as $familiar)
            <tr>
                <td>{{ $familiar->cedula }}</td>
                <td>{{ $familiar->fullName }}</td>
                <td >{{ $familiar->primer_telefono }}</td>
                <td >{{ $familiar->centroVotacion->nombre }}</td>
            </tr>
        @endforeach
    </tbody>

</table>
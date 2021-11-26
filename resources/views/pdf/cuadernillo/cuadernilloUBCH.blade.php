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
        font-size: 10px;
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
            <h2 style="text-align:center;">ELECCIONES REGIONALES Y MUNICIPALES DE GOBERNADORES, ALCALDES, LEGISLADORES Y CONCEJALES.</h2>
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
            <p style="text-align: right;">Jefe de UBCH: <b>{{ $jefeUbch->personalCaracterizacion->getFullNameAttribute() }}</b></p>
        </td>
    </tr>
    <tr>
        <td>
            <p style="text-align: right;">Teléfono de UBCH: <b>{{ $jefeUbch->personalCaracterizacion->telefono_principal }}</b></p>
        </td>
    </tr>


</table>
<div style="page-break-after: always;"></div>

<table>
    <tr>
        <td>
            <p><b>Centro de votacion:</b> {{ $centroVotacion->nombre }}</p>
            <p><b>Municipio:</b> {{ $centroVotacion->parroquia->municipio->nombre }}</p>
            <p><b>Parroquia:</b> {{ $centroVotacion->parroquia->nombre }}</p>
        </td>
        <td>
            <img src="{{ public_path('psuv.png') }}" style="margin-left: 50px; width: 130px; ">
        </td>
    </tr>
</table>

<table id="table-2">
    <thead>
        <tr>
            <th<b>Comunidad</b></th>
            <th<b>Jefe</b></th>
            <th<b>Calle</b></th>
            <th<b>Jefe</b></th>
            <th<b>Jefe de familia</b></th>
            <th<b>Cédula familiar</b></th>
            <th<b>Familiar</b></th>
            <th<b>Voto</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $votacion)
            <tr>
                <td style="width: 100px;">{{ $votacion->comunidad }}</td>
                <td style="width: 100px;">{{ $votacion->jefe_comunidad }}</td>
                <td style="width: 60px;">{{ $votacion->calle }}</td>
                <td style="width: 60px;">{{ $votacion->jefe_calle }}</td>
                <td style="width: 60px;">{{ $votacion->jefe_familia }}</td>
                <td style="width: 60px;">{{ $votacion->cedula_familiar }}</td>
                <td style="width: 60px;">{{ $votacion->familiar }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
    

</table>

<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->text(270, 820, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 10);
        ');
    }
</script>
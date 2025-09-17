//Llenado de los campos
<h3>Ingrese los nuevos campos</h3>
<form action=""><input type="text" placeholder="nombre del formulario">
<div>
    
    <h4>Puntaje máximo
        @if($userType == '') <!-- usuario secretaria -->
            <input class="pmax text-white px-4 mt-3" id="puntajeMaximo" placeholder="40" readonly
                oninput="actualizarPuntajeMaximo(this.value);"">
                            <button class=" btn custom-btn printButtonClass"
                onclick="habilitarEdicion('puntajeMaximo')">Editar</button>
            <button class="btn custom-btn printButtonClass" onclick="guardarEdicion('puntajeMaximo')">Guardar</button>

        @else
            <span id="PuntajeMaximo"></span>
        @endif
    </h4>
</div>
<table>
<thead>
    <tr>
        <th scope="col"><input type="text" placeholder="Ingrese la actividad"></th>
        <th scope="col"><input type="text" placeholder="Ingrese el numero de "></th>
        @foreach ($headers as $header)
            <th class="table-ajust2" scope="col">{{ $header }}</th>
        @endforeach
        <th class="table-ajust2 cd" scope="col">Puntaje a evaluar</th>
        <th class="table-ajust2 cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
        <th class="table-ajust2" scope="col">Observaciones</th>
    </tr>
</thead>
<tbody>
    @foreach ($activities as $activity)
        <tr>
            <td>{{ $activity->name }}</td>
            @foreach ($activity->columns as $column)
                <td>{{ $column->value }}</td>
            @endforeach
            <td><input type="number" name="score_{{ $activity->id }}" value="0"></td>
            <td><input type="number" name="commission_score_{{ $activity->id }}" value="0"></td>
            <td><input type="text" name="observation_{{ $activity->id }}"></td>
        </tr>
    @endforeach
</tbody>

</table>
</form>


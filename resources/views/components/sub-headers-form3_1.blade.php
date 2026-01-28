@props([
    'canonical' => false,
])

<tr>
    <td class="p2" colspan="5">
        3.1 Participación en actividades de diseño curricular
    </td>

    <td
        @if($canonical ?? false)
            id="score3_1"
        @endif
        class="score3_1"
        style="white-space: nowrap;
               background-color: #0b5967;
               color: white;
               text-align: center;
               border: none;
               font-weight: bold;">
    </td>

    <td colspan="6"></td>
</tr>

<tr>
    <th class="actividades">Incisos</th>
    <th class="actividades">Documento</th>
    <th class="actividades">Actividad</th>
    <th class="actividades">Puntaje</th>
    <th class="actividades" id="cantidadForm3_1">Cantidad</th>
    <th class="actividades" colspan="2">Subtotal</th>
    <th class="actividades text-center">Observaciones</th>
</tr>

<!-- resources/views/components/table-section.blade.php -->

<tbody>
    @foreach($items as $item)
        <tr>
            <td class="info">{{ $item['label'] }}</td>
            <td class="p1">{{ $item['value'] }}</td>
            <td class="tdResaltado">
                <label class="p2" for="">{{ $item['comision'] }}</label>
            </td>
        </tr>
    @endforeach
</tbody>
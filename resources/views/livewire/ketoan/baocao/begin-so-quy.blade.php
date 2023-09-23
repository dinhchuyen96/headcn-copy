<tr>
    <td colspan="5">{{ $current_account_code }}</td>
    <td>
        @foreach ($data_begin as $item_begin)
        @if ($item_begin->account_code == $current_account_code)
         {{ $item_begin->begin_money }}
        @endif
        @endforeach
    </td>
    <td colspan="3"></td>
</tr>



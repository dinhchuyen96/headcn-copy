<tr>
    <td colspan="8"></td>
    <td>
        @foreach ($data_end as $item_end)
        @if ($item_end->account_code == $current_account_code)
         {{ $item_end->end_money }}
        @endif
        @endforeach
    </td>
</tr>



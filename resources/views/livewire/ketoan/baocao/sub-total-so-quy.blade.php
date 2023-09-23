<tr>
    <td colspan="5"></td>
    <td>SUBTOTAL</td>
    <td>{{ $data->where('account_code', $current_account_code)->sum('in_money') }}</td>
    <td>{{ $data->where('account_code', $current_account_code)->sum('out_money') }}</td>
    <td></td>
</tr>


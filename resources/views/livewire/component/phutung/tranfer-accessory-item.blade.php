<tr role="row" class="odd">
    <td>{{ $index }}</td>
    <td>
        {{ $accessory_code }}
    </td>
    <td>{{ $name }}</td>
    <td>{{ $position }}</td>
    <td>
        {{ $amount_in_warehouse }}
    </td>
    <td>
        <input wire:model.lazy="quatity_tranfer"  type="number" wire:change="quatityTranferChange"
            class="form-control" style="padding: 5px 4px" />
        @error('quatity_tranfer')
            @include('layouts.partials.text._error')
        @enderror
    </td>
    <td>
        {{ $remain }}
    </td>
    <td class="text-center">
        <a href="#" class="btn btn-danger delete-category btn-xs m-r-5" wire:click="remove({{ $index }})"
            data-toggle="tooltip" data-original-title="Xóa"><i class="fa fa-trash font-14"></i></a>
    </td>
</tr>


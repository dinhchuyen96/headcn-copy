<div id='table_input'>
    <input type="hidden" id="count_accessories" value="{{ $data ? $data->count() : 0 }}">
    <table class="table table-striped table-bordered readonly_input">
        <thead>
            <tr>
                <th>Tên vị trí</th>
                <th style="width:100px">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $key => $item)
                <tr>
                    <td>
                        <input type="text" class="form-control" value="{{ $item->name }}" readonly
                            @if ($itemEditID == $item->id) hidden @endif>
                        <input type="text" class="form-control" wire:model.lazy="PositionName" @if ($itemEditID != $item->id)  hidden @endif
                            {{ $isHVN ? 'readonly' : '' }}>
                        @if ($itemEditID == $item->id)
                            @error('PositionName')
                                @include('layouts.partials.text._error')
                            @enderror
                        @endif
                    </td>
                    <td {{ $status ? 'hidden' : '' }}>
                        <button class="edit border-0" @if ($itemEditID == $item->id) style="display:none" @else style="display:inline"  @endif data-original-title="Sửa"
                            wire:click="editItem({{ $item->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button @if ($itemEditID == $item->id) style="display:none" @endif class="delete border-0" data-original-title="Xóa"
                            wire:click="delete({{ $item->id }})">
                            <i class="fa fa-trash"></i>
                        </button>

                        <button class="add border-0" data-toggle="tooltip" @if ($itemEditID != $item->id) style="display:none" @else style="display:inline"  @endif
                            wire:click="updateItem({{ $item->id }})">
                            <i class="fa fa-check"></i>
                        </button>
                        <button @if ($itemEditID != $item->id) style="display:none" @endif class="delete border-0" data-original-title="Hủy"
                            wire:click="cancel"><i class="fa fa-remove"></i></button>
                    </td>
                </tr>
            @empty
                @if (!$addStatus)
                    <tr>
                        <td colspan="2" class="text-center text-danger">
                            Chưa có dữ liệu
                        </td>
                    </tr>
                @endif
            @endforelse
            @if ($addStatus)
                <tr>
                    <td>
                        <input type="text" class="form-control" wire:model.lazy="PositionName" id="PositionName">

                    </td>
                    <td>
                        <a class="add" data-toggle="tooltip" style="display: inline;"
                            data-original-title="Thêm" wire:click="addItem()">
                            <i class="fa fa-plus"></i></a>
                        <a class="edit" data-toggle="tooltip" style="display: none;"
                            data-original-title="Sửa">
                            <i class="fa fa-edit"></i></a>
                        <a class="delete" wire:click="cancelAdd()" data-toggle="tooltip"
                            data-original-title="Xóa">
                            <i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

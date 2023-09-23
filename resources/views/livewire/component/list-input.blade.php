<div>
    <input type="hidden" id="count_accessories" value="{{ $accessory_draft ? $accessory_draft->count() : 0 }}">

    <table class="table table-striped table-bordered readonly_input">
        <thead>
            <tr>
                <th>Mã phụ tùng</th>
                @if ($type == \App\Enum\EOrder::TYPE_NHAP)
                    <th>Mã đơn hàng</th>
                @endif
                <th>Tên phụ tùng</th>
                @if ($code == 'HVN' && $type == \App\Enum\EOrder::TYPE_NHAP)
                    <th>Số lượng thực nhận</th>
                    <th>Còn lại</th>
                @endif
                @if ($code != 'HVN')
                    <th style="width: 105px">Số lượng</th>
                @endif
                <th style="width:200px">Giá niêm yết (VND)</th>
                @if ($code != 'HVN')
                    <th @if ($type == \App\Enum\EOrder::TYPE_BANLE) style="display: none" @endif>Thành tiền = Số lượng * Đơn giá(VND)</th>
                @endif
                @if (in_array($type, [\App\Enum\EOrder::TYPE_BANBUON, \App\Enum\EOrder::TYPE_BANLE]))
                    <th>Giá in hóa đơn(VND)</th>
                    <th>Giá thực tế (VND)</th>
                @endif
                @if ($type == \App\Enum\EOrder::TYPE_NHAP)
                    <th>Ngày nhập</th>
                @endif
                <th>Thao tác</th>

            </tr>
        </thead>
        <tbody>
            @forelse($accessory_draft as $key=> $item)
                <tr>
                    <td>
                        <input type="text" class="form-control" value="{{ $item->code }}" readonly
                            @if ($itemEditID == $item->id) hidden @endif>
                        <input type="text"
                            class="form-control @error('accessaryNumberEdit')border border-danger @enderror"
                            wire:model.lazy="accessaryNumberEdit" @if ($itemEditID != $item->id) hidden @endif>
                        @error('accessaryNumberEdit') <span class="error text-danger">{{ $message }}</span>@enderror
                    </td>
                    <td @if ($type != \App\Enum\EOrder::TYPE_NHAP) style="display: none" @endif>
                        <input type="text" class="form-control" value="{{ $order_number }}" @if ($itemEditID == $item->id) hidden @endif
                            readonly autocomplete="off">
                        <input type="text" class="form-control " wire:model.lazy="orderNumberEdit"
                            @if ($itemEditID != $item->id) hidden @endif disabled>
                    </td>
                    <td><input type="text" class="form-control" wire:model.lazy="nameEdit" @if ($itemEditID != $item->id) hidden @endif
                            {{ ($code != 'HVN' && $type == \App\Enum\EOrder::TYPE_NHAP) || in_array($type, [\App\Enum\EOrder::TYPE_BANBUON, \App\Enum\EOrder::TYPE_BANLE]) ? 'disabled' : '' }}>
                        <input @if ($itemEditID == $item->id) hidden @endif type="text" class="form-control" value="{{ $item->name }}"
                            readonly>
                    </td>
                    <td @if ($type != \App\Enum\EOrder::TYPE_NHAP || $code != 'HVN') style="display: none" @endif>
                        <input type="text" class="form-control" value="{{ $item->quantity }}" @if ($itemEditID == $item->id) hidden @endif
                            readonly>
                        <input type="text" class="form-control " wire:model.lazy="quantityRequestedEdit"
                            @if ($itemEditID != $item->id) hidden @endif disabled>
                    </td>
                    <td @if ($type != \App\Enum\EOrder::TYPE_NHAP || $code != 'HVN') style="display: none" @endif>
                        <input type="text" class="form-control" value="{{ $item->back_order_qty ?? '' }}" readonly
                            @if ($itemEditID == $item->id) hidden @endif autocomplete="off">
                        <input type="text" class="form-control " wire:model.lazy="backQuantityEdit"
                            @if ($itemEditID != $item->id) hidden @endif disabled>
                    </td>
                    <td @if ($code == 'HVN' && $type == \App\Enum\EOrder::TYPE_NHAP) style="display: none" @endif>
                        <input type="text" class="form-control"
                            value="{{ $item->quantity ? number_format($item->quantity) : '' }}" readonly
                            @if ($itemEditID == $item->id) hidden @endif>
                        <input type="text" class="form-control" wire:model.lazy="quantityEdit"
                            @if ($itemEditID != $item->id) hidden @endif>
                        @error('quantityEdit')<span class="text-danger">{{ $message }}</span>
                @endif
                </td>
                <td><input type="text" class="format-number form-control" wire:model.lazy="listed_priceEdit"
                        @if ($itemEditID != $item->id) hidden @endif {{ $code == 'HVN' && $type == \App\Enum\EOrder::TYPE_NHAP ? 'disabled' : '' }}>
                    @error('listed_priceEdit')<span class="text-danger">{{ $message }}</span> @endif
                    <input type="text" class="form-control"
                        value="{{ $item->listed_price ? number_format($item->listed_price) : '' }}" readonly
                        @if ($itemEditID == $item->id) hidden @endif>

                </td>
                <td @if (($code == 'HVN' && $type == \App\Enum\EOrder::TYPE_NHAP) || $type == \App\Enum\EOrder::TYPE_BANLE) style="display: none" @endif>
                    <input type="text" class="form-control" @if ($itemEditID == $item->id) hidden
                    @endif
                    value="{{ $code == 'HVN' && $type == \App\Enum\EOrder::TYPE_NHAP ? '' : number_format($item->listed_price * $item->quantity) }}"
                    readonly>
                    <input type="text" class="form-control" wire:model="totalEdit" @if ($itemEditID != $item->id) hidden @endif readonly>
                </td>
                <td @if ($type == \App\Enum\EOrder::TYPE_NHAP) hidden @endif>
                    <input type="text" class="form-control" @if ($itemEditID == $item->id) hidden @endif
                        value="{{ $code == 'HVN' && $type == \App\Enum\EOrder::TYPE_NHAP ? '' : number_format($item->vat_price) }}"
                        readonly>
                    <input type="text" class="format_number form-control" wire:model.lazy="vat_priceEdit"
                        @if ($itemEditID != $item->id) hidden @endif>
                    @error('vat_priceEdit')<span class="text-danger">{{ $message }}</span> @endif
                </td>
                <td @if ($type == \App\Enum\EOrder::TYPE_NHAP) hidden @endif>
                    <input type="text" class="form-control" @if ($itemEditID == $item->id) hidden @endif
                        value="{{ $code == 'HVN' && $type == \App\Enum\EOrder::TYPE_NHAP ? '' : number_format($item->actual_price) }}"
                        readonly>
                    <input type="text" class="format_number form-control" wire:model.lazy="actual_priceEdit"
                        @if ($itemEditID != $item->id) hidden @endif>
                    @error('actual_priceEdit')<span class="text-danger">{{ $message }}</span> @endif
                </td>
                <td @if ($type != \App\Enum\EOrder::TYPE_NHAP) hidden @endif @if ($itemEditID != $item->id) hidden @endif>
                    <input type="date" class="form-control input-date-kendo-edit" id="buyDateEdit{{ $key }}"
                        max='{{ date('Y-m-d') }}' wire:model.lazy="buyDateEdit">
                </td>
                <td @if ($type != \App\Enum\EOrder::TYPE_NHAP) hidden @endif @if ($itemEditID == $item->id) hidden @endif>
                    <input type="text" class="form-control" value="{{ reFormatDate($item->buy_date, 'd/m/Y') }}" readonly>
                </td>
                <td>
                    <button class="edit border-0" @if ($itemEditID == $item->id)
                        style="display:none"
                    @else style="display:inline" @endif data-original-title="Sửa"
                        wire:click="editItem({{ $item->id }})" {{ $status ? 'disabled' : '' }}><i
                            class="fa fa-edit"></i></button>
                    <button {{ $status ? 'disabled' : '' }} @if ($itemEditID == $item->id) style="display:none"  @endif class="delete border-0"
                        data-original-title="Xóa" wire:click="delete({{ $item->id }})"><i class="fa fa-trash"></i>
                    </button>
                    <button class="add border-0" data-toggle="tooltip" @if ($itemEditID != $item->id) style="display:none"
                    @else style="display:inline" @endif wire:click="updateItem({{ $item->id }})">
                        <i class="fa fa-check"></i>
                    </button>
                    <button @if ($itemEditID != $item->id) style="display:none" @endif class="delete border-0" data-original-title="Hủy"
                        wire:click="cancel"><i class="fa fa-remove"></i></button>
                </td>
                </tr>
            @empty
                @if (!$addStatus)
                    <tr>
                        <td colspan="@if ($type == \App\Enum\EOrder::TYPE_NHAP && $code != 'HVN') 8 @elseif($type==\App\Enum\EOrder::TYPE_NHAP&&$code=='HVN') 8 @elseif($type==\App\Enum\EOrder::TYPE_BANBUON)8 @else 7 @endif" class="text-center text-danger"> Chưa có dữ liệu</td>
                    </tr>
                @endif
                @endforelse
                <tr @if (!$addStatus) style="display: none" @endif>
                    <td><input {{ !$statusInput ? 'disabled' : '' }} type="text"
                            class="form-control @error('accessaryNumber')border border-danger @endif"
                            wire:model.lazy="accessaryNumber" id="AccessaryNumber1">
                        @error('accessaryNumber') <span class="error text-danger">{{ $message }}</span>@enderror
                    </td>
                    @if ($type == \App\Enum\EOrder::TYPE_NHAP)
                        <td><input type="text" class="form-control" wire:model.lazy="order_number"
                                {{ $code == 'HVN' ? 'disabled' : '' }}></td>
                    @endif
                    <td><input type="text" class="form-control" wire:model.lazy="name"
                            {{ $code == 'HVN' || in_array($type, [\App\Enum\EOrder::TYPE_BANBUON, \App\Enum\EOrder::TYPE_BANLE]) ? 'disabled' : '' }}>
                    </td>
                    @if ($type == \App\Enum\EOrder::TYPE_NHAP && $code == 'HVN')
                        <td><input type="text" class="form-control" wire:model.lazy="quantityRequest" disabled></td>
                        <td><input type="text" class=" form-control" wire:model.lazy="backQuantity" disabled></td>
                    @endif
                    @if ($code != 'HVN')
                        <td><input {{ !$statusInput ? 'disabled' : '' }} type="text" class="format_number form-control"
                                wire:model.lazy="quantity">
                            @error('quantity')<span class="text-danger">{{ $message }}</span>
                        @endif
                        </td>
                        @endif
                        <td>
                            <input {{ !$statusInput ? 'disabled' : '' }} type="text" class="format_number form-control"
                                wire:model.lazy="listed_price" {{ $code == 'HVN' ? 'disabled' : '' }}>
                            @error('listed_price')<span class="text-danger">{{ $message }}</span> @endif
                        </td>
                        @if ($code != 'HVN')
                            <td @if ($type == \App\Enum\EOrder::TYPE_BANLE) style="display: none" @endif><input type="text" class="format_number form-control"
                                    wire:model="total" readonly=""></td>
                        @endif

                        <td @if ($type != \App\Enum\EOrder::TYPE_NHAP) style="display: none" @endif>
                            <input id="buyDate" name="TransactionDate" type="date" class="form-control input-date-kendo-now"
                                wire:model.lazy="buyDate" max='{{ now()->format('Y-m-d') }}'>
                        </td>
                        @if (in_array($type, [\App\Enum\EOrder::TYPE_BANBUON, \App\Enum\EOrder::TYPE_BANLE]))
                            <td><input type="text" class="format_number form-control" wire:model.lazy="vat_price"
                                    maxlength="10">
                                @error('vat_price')<span class="text-danger">{{ $message }}</span>
                            @endif
                            </td>
                            <td><input type="text" class="format_number form-control" wire:model.lazy="actual_price" maxlength="10">
                                @error('actual_price')<span class="text-danger">{{ $message }}</span> @endif
                            </td>
                            @endif
                            <td><button class="add" data-toggle="tooltip" style="display: inline;"
                                    data-original-title="Thêm" wire:click="addItem()">
                                    <i class="fa fa-plus"></i></button>
                                <button wire:click="cancelNew()" class="delete" data-toggle="tooltip"
                                    data-original-title="Xóa">
                                    <i class="fa fa-remove"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

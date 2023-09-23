<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin chăm sóc khách hàng</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">

                        <label for="searchInfoCustomer" class="col-1 col-form-label">Thông tin khách hàng</label>
                        <div wire:ignore class="col-3">
                            <select id="searchInfoCustomer" name="searchInfoCustomer"
                                data-ajax-url="{{ route('customers.getCustomerByPhoneOrName.index') }}"
                                class="custom-select form-control">
                            </select>
                        </div>

                        <label for="customerType" class="col-1 col-form-label">Phân loại</label>
                        <div class="col-3">
                            <select id="customerType" name="customerType" wire:model="customerType"
                                class="custom-select select2-box form-control">
                                <option value="1">--Tất cả--</option>
                                <option value="2">Quá 6 tháng chưa dùng dịch vụ</option>
                                <option value="3">KTĐK lần 1</option>
                                <option value="4">KTĐK lần 2</option>
                                <option value="5">KTĐK lần 3</option>
                                <option value="6">KTĐK lần 4</option>
                                <option value="7">KTĐK lần 5</option>
                                <option value="8">KTĐK lần 6</option>
                                <option value="9">Khách hàng sau sửa chữa</option>
                                <option value="10">Khách hàng mua xe</option>
                                <option value="11">Khách hàng có phụ tùng yếu kém cần thay thế</option>
                                <option value="12">Khách hàng hết bảo hành sau 3 năm</option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table class="table table-striped table-bordered dataTable no-footer"
                            id="category-table" cellspacing="0" width="100%" role="grid"
                            aria-describedby="category-table_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th style="width: 10px;">STT</th>
                                    <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 150.5px;" wire:click="sorting('name')">Họ và tên</th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 100.5px;">Số điện thoại</th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 200.5px;">Địa chỉ</th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 100.5px;">Trạng thái
                                    </th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">SK - SM
                                    </th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Biển số
                                    </th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Mã Model
                                    </th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Tên Model
                                    </th>
                                    <th tabindex="0" aria-controls="category-table" style="width: 50.5px;">Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <div wire:loading class="loader"></div>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr data-parent="" data-index="1" role="row" class="odd">
                                        <td>
                                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->address .(isset($item->wardCustomer) ? ', ' . $item->wardCustomer->name : '') .(isset($item->districtCustomer) ? ', ' . $item->districtCustomer->name : '') .(isset($item->provinceCustomer) ? ', ' . $item->provinceCustomer->name : '') }}
                                        </td>
                                        <td>
                                            @if ($item->contactHistories->isEmpty())
                                                <span class='badge badge-warning'>Chưa liên hệ</span>
                                            @else
                                                <span class='badge badge-success'>Đã liên hệ</span>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach ($item->motorbikes as $motorbike)
                                                <div>{{ $motorbike->chassic_no . ' - ' . $motorbike->engine_no }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($item->motorbikes as $motorbike)
                                                <div>{{ $motorbike->motor_numbers ?? '-' }}</div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($item->motorbikes as $motorbike)
                                                <div>
                                                    {{ getModelCodeOfMotorbike($motorbike->chassic_no, $motorbike->engine_no) }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($item->motorbikes as $motorbike)
                                                <div>{{ $motorbike->model_code ?? '-' }}</div>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('cskh.lich-su-lien-he-khach-hang.index', ['id' => $item->id]) }}"
                                                target="_blank" class="btn btn-primary btn-xs m-r-5"
                                                data-toggle="tooltip" data-original-title="Lịch sử"><i
                                                    class="fa fa-list-alt font-14"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-danger">Không có bản ghi nào.</td>
                                        <br />
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if (count($data) > 0)
                    {{ $data->links() }}
                @endif
            </div>
            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setSelect2Customer();
        $('#customerType').on('change', function(e) {
            var data = $('#customerType').select2("val");
            @this.set('customerType', data);
        });
    });
    document.addEventListener('select2Customer', function() {
        setSelect2Customer();
    });

    function setSelect2Customer() {
        let ajaxUrl = $('#searchInfoCustomer').data("ajaxUrl");
        $('#searchInfoCustomer').select2({
            ajax: {
                url: ajaxUrl,
                data: function(params) {
                    var query = {
                        search: params.term,
                    }
                    return query;
                },
                processResults: function(data) {
                    const itemFirst = {
                        id: "0",
                        text: "--Tất cả--"
                    };
                    data.unshift(itemFirst);
                    return {
                        results: data
                    };
                }
            },
            placeholder: 'Nhập tên hoặc SĐT để tìm kiếm',
        });
        $('#searchInfoCustomer').on('change', function(e) {
            var data = $('#searchInfoCustomer').select2("val");
            @this.set('searchInfoCustomer', data);
        });
    };
</script>

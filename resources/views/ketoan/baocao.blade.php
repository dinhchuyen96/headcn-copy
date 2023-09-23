@extends('layouts.master')

@section('title', 'Báo cáo thu chi')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    <div class="page-heading">
        <h1 class="page-title">Báo cáo thu chi</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Báo cáo thu chi</li>

        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Danh sách thu chi</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="CustomerName" class="col-2 col-form-label ">Họ và tên KH</label>
                        <div class="col-4">
                            <input id="CustomerName" name="CustomerName" type="text" class="form-control" value="">
                        </div>
                        <label for="CustomerAddress" class="col-2 col-form-label ">Địa chỉ</label>
                        <div class="col-4">
                            <input id="CustomerAddress" name="CustomerAddress" type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Type" class="col-2 col-form-label ">Phân loại</label>
                        <div class="col-4">
                            <select name="Type" id="Type" class="custom-select">
                                <option value="04022">Thu</option>
                                <option value="04727">Chi</option>

                            </select>
                        </div>
                        <label for="PayStatus" class="col-2 col-form-label ">Trạng thái thanh toán</label>
                        <div class="col-4">
                            <select name="PayStatus" id="PayStatus" class="custom-select">
                                <option value="1">Đã thanh toán</option>
                                <option value="5">Chưa thanh toán</option>
                                <option value="2">Chờ xử lý</option>
                                <option value="3">Đã hủy</option>
                                <option value="4">Chờ xử lý hủy</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Time" class="col-2 col-form-label ">Thời gian</label>
                        <div class="col-4 row pr-0">
                            <div class="col-5 pr-0">
                                <input type="date" class="form-control" name="fromDate">
                            </div>
                            <div class="col-2 justify-content-center align-items-center">
                                <p class="text-center pt-2">～</p>
                            </div>
                            <div class="col-5 pr-0 pl-0">
                                <input type="date" class="form-control" name="toDate">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-1">
                            <button name="submit" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tìm
                                kiếm</button>
                        </div>
                    </div>

                </form>

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="category-table_length"><label>Hiển thị <select
                                        name="category-table_length" aria-controls="category-table"
                                        class="form-control form-control-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select></label></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="category-table_filter" class="dataTables_filter">
                                <button name="submit" type="submit" class="btn btn-warning add-new"><i
                                        class="fa fa-file-excel-o"></i> Export file</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" aria-sort="ascending"
                                            aria-label="ID: activate to sort column descending" style="width: 164.5px;">ID
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 164.5px;">Tên khách hàng</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 164.5px;">Địa chỉ</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 164.5px;">Tổng tiền</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 164.5px;">Trạng thái</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 164.5px;">Phân loại</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 164.5px;">Ngày tạo</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 100.5px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < 10; $i++)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">ORDER_00{{ $i }}</td>
                                            <td>NGUYỄN VĂN A {{ $i }}</td>
                                            <td>HAI BÀ TRUNG , HÀ NỘI</td>
                                            <td>189,000,000</td>
                                            <td>
                                                @if ($i % 2 === 0)
                                                    <span class="badge badge-success"> Đã thanh toán</span>
                                                @else
                                                    <span class="badge badge-default"> Chờ xử lý </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($i % 2 === 0)
                                                    <span class="badge badge-primary">Thu</span>
                                                @else
                                                    <span class="badge badge-warning">Chi</span>
                                                @endif
                                            </td>
                                            <td>2021-09-22 15:38:12</td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-warning btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Xem"><i class="fa fa-eye font-14"></i></a>
                                                <a href="#" class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Sửa"><i class="fa fa-pencil font-14"></i></a>
                                                <a href="#" class="btn btn-danger delete-category btn-xs m-r-5"
                                                    data-toggle="tooltip" data-original-title="Xóa"><i
                                                        class="fa fa-trash font-14"></i></a>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" id="category-table_info" role="status" aria-live="polite">Hiển
                                thị 1-10
                                / 100 item</div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="category-table_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button page-item previous disabled" id="category-table_previous"><a
                                            href="#" aria-controls="category-table" data-dt-idx="0" tabindex="0"
                                            class="page-link">Trước</a></li>
                                    <li class="paginate_button page-item active"><a href="#" aria-controls="category-table"
                                            data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                                    <li class="paginate_button page-item next disabled" id="category-table_next"><a href="#"
                                            aria-controls="category-table" data-dt-idx="2" tabindex="0"
                                            class="page-link">Sau</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

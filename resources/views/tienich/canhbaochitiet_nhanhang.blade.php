@extends('layouts.master')

@section('title', 'Chi tiết cảnh báo')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Danh sách xe chưa xác nhận xe về đến head</div>
            </div>
            <div class="ibox-body">
                
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
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
                                            colspan="1" style="width: 164.5px;">Số khung</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 164.5px;">Số máy</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 164.5px;">Model</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 60px;">Màu xe</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 100px;">Số lượng</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 130px;">Số ngày chậm</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 164.5px;">Trạng thái</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 164.5px;">Nhà cung cấp</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1"
                                            colspan="1" style="width: 100.5px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < 10; $i++)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">IM_00{{ $i }}</td>
                                            <td>
                                                RLHJF8108AA0002158
                                            </td>
                                            <td>JF81E-1002158</td>
                                            <td>KF30 PCX150</td>
                                            <td>
                                                ÐEN
                                            </td>
                                            <td>{{ $i * 3 }}</td>
                                            <td>
                                                <span class="badge badge-warning">{{ $i % 5 }} ngày</span>
                                            </td>
                                            <td><span class="badge badge-danger"> Nhập muộn </span></td>

                                            <td>
                                                @if ($i % 2 === 0)
                                                    HVN
                                                @else
                                                    GS
                                                @endif
                                            </td>
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

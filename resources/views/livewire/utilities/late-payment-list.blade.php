<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Danh sách bỏ hàng, nộp tiền xe muộn</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="ClaimCode" class="col-1 col-form-label">Delivery_no</label>
                        <div class="col-3">
                            <input id="searchDelivery" name="ClaimCode" type="text" class="form-control" wire:model.debounce.1000ms="searchDelivery">
                        </div>
                        <label for="Time" class="col-1 col-form-label ">Thời gian</label>
                        @include('layouts.partials.input._inputDateRanger')
                    </div>
                    <div class="form-group row justify-content-center">
                        @include('layouts.partials.button._reset')
                    </div>

                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <button @if (count($result) == 0) disabled @endif name="submit" type="submit" class="btn btn-warning add-new"><i class="fa fa-file-excel-o"></i> Export file</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer" id="category-table" cellspacing="0" width="100%" role="grid" aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: activate to sort column descending" style="width: 164.5px;">ID
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1" colspan="1" style="width: 164.5px;">Delivery_no</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1" colspan="1" style="width: 164.5px;">Ngày giao</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table" rowspan="1" colspan="1" style="width: 164.5px;">Ngày nộp tiền</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    <?php $i=0;?>
                                    @foreach ($result as $value)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">IM_00{{++$i}}</td>
                                            <td>
                                                {{$value->hvn_lot_number}}
                                            </td>
                                            <td>{{$value->stock_out_date_time}}</td>
                                            <td>{{$value->credit_date}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

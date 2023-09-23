<div>
    <div wire:ignore.self class="modal fade" id="showModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-backdrop fade in" style="height: 100%;"></div>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel">Chi tiết phát sinh trong kỳ</h2>
                </div>
                <div wire:loading class="loader"></div>
                <div class="modal-body">
                    @if ($customerId != '')
                        <div class="page-content fade-in-up">
                            <div class="ibox">
                                <div class='row col-sm-12'>
                                    <p><strong>KHÁCH HÀNG: </strong>{{ $currentCustomer->name }}</p>
                                </div>
                                <div>
                                    <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                        <div class="row table-responsive">
                                            <table id="simple-table"
                                                class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center; color: black;"
                                                            class="hidden-480">Ngày</th>
                                                        <th style="text-align: center; color: black;">Nội dung</th>
                                                        <th style="text-align: center; color: black;">Giá trị bán</th>
                                                        <th style="text-align: center; color: black;">Giá trị thanh toán
                                                        </th>
                                                        <th style="text-align: center; color: black;">Dư nợ phải trả
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (count($currentCustomerOrder) > 0)
                                                        <tr>
                                                            <td colspan="4"><strong>Dư nợ đầu phải thu</strong></td>
                                                            <td><strong>{{ numberFormat($ordersUnPaidBefore) }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @forelse($currentCustomerOrder as $key => $value)
                                                        <tr>
                                                            <td>{{ reFormatDate($value->created_at) }}</td>
                                                            <td>{{ $value->note }}</td>
                                                            <td>{{ $value->type_table == 2 ? numberFormat($value->total_money) : '' }}
                                                            </td>
                                                            <td>{{ $value->type_table == 1 ? numberFormat($value->total_money) : '' }}
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center">Không có đơn hàng
                                                                trong kỳ</td>
                                                        </tr>
                                                    @endforelse
                                                    @if (count($currentCustomerOrder) > 0)
                                                        <tr>
                                                            <td colspan="4"><strong>Dư nợ cuối còn phải thu</strong>
                                                            </td>
                                                            <td><strong>{{ numberFormat($currentCustomer->ordersUnPaid($toDateAfter)) }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                    @if (count($currentCustomerOrder) > 0)
                                        {{ $currentCustomerOrder->links() }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary close-btn" data-dismiss="modal"
                        wire:click='clickCancel'>Quay lại</button>
                </div>
            </div>
        </div>
    </div>
</div>

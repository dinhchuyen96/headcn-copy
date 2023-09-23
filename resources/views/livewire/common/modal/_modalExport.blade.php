<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Tải file excel xuống</h2>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xuất file không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-basic" data-dismiss="modal">Quay lại</button>
                <button type="button" wire:click="export" class="btn btn-primary" data-dismiss="modal" id='btn-upload-film'>Đồng ý</button>
            </div>
        </div>
    </div>
</div>
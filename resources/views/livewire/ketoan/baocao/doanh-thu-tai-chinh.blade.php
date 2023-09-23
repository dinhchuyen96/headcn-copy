<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Doanh thu tài chính</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox" style="min-height: 500px">
            <div class="ibox-body">

                    <div class="form-group row">
                        <label for="Time" class="col-1 col-form-label ">Thời gian 1</label>
                        <div class='col-1'>
                            <select wire:model="yearStart" name='yearStart' id="yearStart"
                                class="custom-select select2-box">
                                {{ $last = date('Y') - 120 }}
                                {{ $now = date('Y') }}
                                @for ($i = $now; $i >= $last; $i--)
                                    <option value="{{ $i }}">
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class='col-1'>
                            <select wire:model="monthStart" name='monthStart' id="monthStart"
                                class="custom-select select2-box">
                                {{ $from = 1 }}
                                {{ $to = 12 }}
                                @for ($j = $from; $j <= $to; $j++)
                                    <option
                                        value="{{ $j }}">{{ $j < 10 ? '0' . $j : $j }}</option>
                                @endfor
                            </select>
                        </div>
                        <label for="Time" class="col-1 col-form-label ">Thời gian 2</label>
                        <div class='col-1'>
                            <select wire:model="yearEnd" name='yearEnd' id="yearEnd" class="custom-select select2-box">
                                {{ $last = date('Y') - 120 }}
                                {{ $now = date('Y') }}
                                @for ($i = $now; $i >= $last; $i--)
                                    <option value="{{ $i }}">
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class='col-1'>
                            <select wire:model="monthEnd" name='monthEnd' id="monthEnd"
                                class="custom-select select2-box">
                                {{ $from = 1 }}
                                {{ $to = 12 }}
                                @for ($i = $from; $i <= $to; $i++)
                                    <option value="{{ $i }}">
                                        {{ $i < 10 ? '0' . $i : $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div wire:loading class="loader"></div>
                    <div class="form-group row mt-5">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-primary" wire:loading.attr="disabled"
                                wire:click.prevent='export'>Xuất báo cáo</button>

                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        $('#yearStart').on('change', function(e) {
            var data = $('#yearStart').val();
            @this.set('yearStart', data);
        });
        $('#monthStart').on('change', function(e) {
            var data = $('#monthStart').val();
            @this.set('monthStart', data);
        });
        $('#yearEnd').on('change', function(e) {
            var data = $('#yearEnd').val();
            @this.set('yearEnd', data);
        });
        $('#monthEnd').on('change', function(e) {
            var data = $('#monthEnd').val();
            @this.set('monthEnd', data);
        });
    });
</script>

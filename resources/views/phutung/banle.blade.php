@extends('layouts.master') @section('title', 'Bán lẻ phụ tùng') @section('css')
<link href="{{ asset('assets/css/table-common.css') }}" rel="stylesheet" />
<style>
    .custom-select {
        width: 100%;
    }
</style>
@endsection @section('content') @livewire('phutung.banle') @endsection
@section('js')
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
    function isNumeric(str) {
        if (typeof str != "string") return false; // we only process strings!
        return (
            !isNaN(str) &&
            // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
            !isNaN(parseFloat(str))
        ); // ...and ensure strings of whitespace fail
    }
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        var actions =
            '<a class="add" title="Thêm" data-toggle="tooltip"><i class="fa fa-plus"></i></a>' +
            '<a class="edit" title="Sửa" data-toggle="tooltip"><i class="fa fa-edit"></i></a>' +
            '<a class="delete" title="Xóa" data-toggle="tooltip"><i class="fa fa-remove"></i></a>';
        // Append table with add row form on add new button click
        $(".add-new").click(function () {
            $(this).attr("disabled", "disabled");
            var indexRow = $("table tbody tr:last-child").index();
            var nextRow = indexRow + 2;
            var row =
                "<tr>" +
                '<td><input type="text" class="form-control" name="AccessaryNumber' +
                nextRow +
                '" id="AccessaryNumber' +
                nextRow +
                '"></td>' +
                '<td><input type="text" class="form-control" name="Quantity' +
                nextRow +
                '" id="Quantity' +
                nextRow +
                '"></td>' +
                '<td><input type="text" class="form-control" name="Price' +
                nextRow +
                '" id="Price' +
                nextRow +
                '"></td>' +
                '<td><input type="text" class="form-control" name="TotalPrice' +
                nextRow +
                '" readonly id="TotalPrice' +
                nextRow +
                '"></td>' +
                '<td><input type="text" class="form-control" name="FixedPrice' +
                nextRow +
                '" id="FixedPrice' +
                nextRow +
                '" value="19,000,000" readonly></td>' +
                "<td>" +
                actions +
                "</td>" +
                "</tr>";
            $("table").append(row);
            $("table tbody tr")
                .eq(indexRow + 1)
                .find(".add, .edit")
                .toggle();
            $('[data-toggle="tooltip"]').tooltip();
            $('table tbody tr input[type="text"]')
                .filter(function (index) {
                    return (
                        $(this).attr("name") === "Price" + nextRow ||
                        $(this).attr("name") === "Quantity" + nextRow
                    );
                })
                .change(function () {
                    let price = $(
                        'table tbody tr input[name="Price' + nextRow + '"]'
                    ).val();
                    let quantity = $(
                        'table tbody tr input[name="Quantity' + nextRow + '"]'
                    ).val();
                    if (isNumeric(price) && isNumeric(quantity)) {
                        $(
                            'table tbody tr input[name="TotalPrice' +
                                nextRow +
                                '"]'
                        ).val(new Intl.NumberFormat().format(price * quantity));
                    }
                });
        });
        // Add row on add button click
        $(document).on("click", ".add", function () {
            var empty = false;
            var input = $(this).parents("tr").find('input[type="text"]');
            input.each(function () {
                if (!$(this).val()) {
                    $(this).addClass("error");
                    empty = true;
                } else {
                    $(this).removeClass("error");
                }
            });
            $(this).parents("tr").find(".error").first().focus();
            if (!empty) {
                input.each(function (index, element) {
                    switch (index) {
                        case 2:
                            $(this)
                                .parent("td")
                                .html(
                                    new Intl.NumberFormat().format(
                                        $(this).val()
                                    )
                                );
                            break;
                        default:
                            $(this).parent("td").html($(this).val());
                            break;
                    }
                });
                $(this).parents("tr").find(".add, .edit").toggle();
                $(".add-new").removeAttr("disabled");
            }
        });
        // Edit row on edit button click
        $(document).on("click", ".edit", function () {
            let lastRow = $(this).parents("tr").find("td:not(:last-child)");
            let length = lastRow.length;
            lastRow.each(function (index, element) {
                switch (index) {
                    case length - 2:
                    case length - 1:
                        $(this).html(
                            '<input type="text" class="form-control" readonly value="' +
                                $(this).text() +
                                '">'
                        );
                        break;
                    case length - 3:
                        $(this).html(
                            '<input type="text" class="form-control" value="' +
                                $(this).text().replaceAll(",", "") +
                                '">'
                        );
                        break;
                    default:
                        $(this).html(
                            '<input type="text" class="form-control" value="' +
                                $(this).text() +
                                '">'
                        );
                        break;
                }
            });
            $(this).parents("tr").find(".add, .edit").toggle();
            $(".add-new").attr("disabled", "disabled");
            let rowEdit = $(this).parents("tr");
            rowEdit
                .find('input[type="text"]')
                .filter(function (index, element) {
                    return index === 1 || index === 2;
                })
                .change(function () {
                    let price = rowEdit
                        .find('input[type="text"]')
                        .filter(function (index, element) {
                            return index === 1;
                        })
                        .val();

                    let quantity = rowEdit
                        .find('input[type="text"]')
                        .filter(function (index, element) {
                            return index === 2;
                        })
                        .val();
                    if (isNumeric(price) && isNumeric(quantity)) {
                        rowEdit
                            .find('input[type="text"]')
                            .filter(function (index) {
                                return index === 3;
                            })
                            .val(
                                new Intl.NumberFormat().format(price * quantity)
                            );
                    }
                });
        });
        // Delete row on delete button click
        $(document).on("click", ".delete", function () {
            $(this).parents("tr").remove();
            $(".add-new").removeAttr("disabled");
            $(".tooltip").remove();
        });
    });
</script>
@endsection

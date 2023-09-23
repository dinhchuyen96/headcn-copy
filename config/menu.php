<?php
    return [
        /**
         * Tiện ích
         */
        [
            'text' => 'Tiện ích',
            'icon' => 'fa fa-th-large',
            'sub_menu' => [
                [
                    'text' => 'Cảnh báo',
                    'route' => 'tienich.canhbao.index',
                    'icon' => 'fa fa-exclamation-triangle',
                    'active' => ['tien-ich/canh-bao-chi-tiet*', 'tienich/canh-bao-chi-tiet*'],
                    'can' => ['tienich.canhbao.index'],

                ],
                [
                    'text' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'fa fa-tachometer',
                    'active' => [],
                ],
            ],
        ],

        /**
         * Kế toán
         */
        [
            'text' => 'Kế toán',
            'icon' => 'fa fa-money',
            'sub_menu' => [
                [
                    'text' => 'Thu tiền',
                    'route' => 'ketoan.thu.index',
                    'icon' => 'fa fa-sign-in',
                    'active' => [],
                    'can' => ['ketoan.thu.index'],
                ],
                [
                    'text' => 'Chi tiền',
                    'route' => 'ketoan.chi.index',
                    'icon' => 'fa fa-sign-out',
                    'active' => [],
                    'can' => ['ketoan.chi.index'],
                ],
                [
                    'text' => 'Báo cáo',
                    'icon' => 'fa fa-sitemap',
                    'sub_menu' => [
                        [
                            'text' => 'Tổng hợp phải thu',
                            'route' => 'ketoan.baocao.tonghopthu.index',
                            'active' => [],
                            'can' => ['ketoan.baocao.tonghopthu.index'],
                        ],
                        [
                            'text' => 'Chi tiết phải thu',
                            'route' => 'ketoan.baocao.chitietthu.index',
                            'active' => [],
                            'can' => ['ketoan.baocao.chitietthu.index'],
                        ],
                        [
                            'text' => 'Tổng hợp phải trả',
                            'route' => 'ketoan.baocao.tonghopchi.index',
                            'active' => [],
                            'can' => ['ketoan.baocao.tonghopchi.index'],
                        ],
                        [
                            'text' => 'Chi tiết phải trả',
                            'route' => 'ketoan.baocao.chitietchi.index',
                            'active' => [],
                            'can' => ['ketoan.baocao.chitietchi.index'],
                        ],
                        [
                            'text' => 'Danh sách thu',
                            'route' => 'ketoan.baocao.dsthu.index',
                            'active' => [],
                            'can' => ['ketoan.baocao.dsthu.index'],
                        ],
                        [
                            'text' => 'Danh sách chi',
                            'route' => 'ketoan.baocao.dschi.index',
                            'active' => [],
                            'can' => ['ketoan.baocao.dschi.index'],
                        ],
                        [
                            'text' => 'Sổ quỹ',
                            'route' => 'ketoan.baocao.soquy.index',
                            'active' => [],
                            'can' => ['ketoan.baocao.soquy.index'],
                        ],
                        [
                            'text' => 'Danh sách trả góp',
                            'route' => 'ketoan.baocao.dstragop.index',
                            'active' => [],
                            'can' => ['ketoan.baocao.dstragop.index'],
                        ],
                    ],
                ],
            ],
        ],

        /**
         * Bán hàng xe máy
         */
        [
            'text' => 'Bán hàng xe máy',
            'icon' => 'fa fa-motorcycle',
            'sub_menu' => [
                [
                    'text' => 'Bán buôn',
                    'route' => 'motorbikes.ban-buon.index',
                    'icon' => 'fa fa-simplybuilt',
                    'active' => [],
                    'can' => ['motorbikes.ban-buon.index'],
                ],
                [
                    'text' => 'Bán lẻ',
                    'route' => 'motorbikes.ban-le.index',
                    'icon' => 'fa fa-sign-language',
                    'active' => [],
                    'can' => ['motorbikes.ban-le.index'],
                ],
                [
                    'text' => 'Danh sách xe bán',
                    'route' => 'motorbikes.orders.index',
                    'icon' => 'fa fa-print',
                    'active' => [],
                    'can' => ['motorbikes.orders.index'],
                ],
                [
                    'text' => 'Nhập hàng',
                    'route' => 'motorbikes.buy.index',
                    'icon' => 'fa fa-map-signs',
                    'active' => [],
                    'can' => ['motorbikes.buy.index'],
                ],
                [
                    'text' => 'Danh sách xe nhập',
                    'route' => 'motorbikes.list.index',
                    'icon' => 'fa fa-list-alt',
                    'active' => [],
                    'can' => ['motorbikes.list.index'],
                ],
                [
                    'text' => 'Dịch vụ khác',
                    'route' => 'xemay.dichvukhac.index',
                    'icon' => 'fa fa-puzzle-piece',
                    'active' => ['xemay/dich-vu-khac/*', 'xemay/dich-vu-khac*'],
                    'can' => ['xemay.dichvukhac.index'],
                ],
                [
                    'text' => 'Chuyển kho xe máy',
                    'route' => 'quanlykho.chuyenkhoxemay.index',
                    'icon' => 'fa fa-life-ring',
                    'active' => [],
                    'can' => ['quanlykho.chuyenkhoxemay.index'],
                ],
                [
                    'text' => 'Lịch sử chuyển kho xe',
                    'route' => 'quanlykho.lichsuchuyenkhoxe.index',
                    'icon' => 'fa fa-glass',
                    'active' => [],
                    'can' => ['quanlykho.lichsuchuyenkhoxe.index'],
                ],
                [
                    'text' => 'Báo cáo',
                    'icon' => 'fa fa-pencil-square',
                    'sub_menu' => [
                        [
                            'text' => 'Báo cáo kho xe máy',
                            'route' => 'quanlykho.baocaokhoxemay.index',
                            'active' => ['quanlykho/thaydoiphutungxe*'],
                            'can' => ['quanlykho.baocaokhoxemay.index'],
                        ],
                        [
                            'text' => 'Báo cáo xe theo model',
                            'route' => 'quanlykho.baocaoxetheomodel.index',
                            'active' => [],
                            'can' => ['quanlykho.baocaoxetheomodel.index'],
                        ],
                    ],
                ],
            ],
        ],

        /**
         * Dịch vụ
         */
        [
            'text' => 'Dịch vụ',
            'icon' => 'fa fa-cogs',
            'sub_menu' => [
                [
                    'text' => 'Kiểm tra định kỳ',
                    'route' => 'dichvu.bao-duong-dinh-ki.index',
                    'icon' => 'fa fa-recycle',
                    'active' => [],
                    'can' => ['dichvu.bao-duong-dinh-ki.index'],
                ],
                [
                    'text' => 'Sửa chữa thông thường',
                    'route' => 'dichvu.sua-chua-thong-thuong.index',
                    'icon' => 'fa fa-map-signs',
                    'active' => [],
                    'can' => ['dichvu.sua-chua-thong-thuong.index'],
                ],
                [
                    'text' => 'Danh sách đơn hàng DV',
                    'route' => 'dichvu.dsdonhang.index',
                    'icon' => 'fa fa-glass',
                    'active' => [],
                    'can' => ['dichvu.dsdonhang.index'],
                ],
                [
                    'text' => 'Danh sách xe ngoài',
                    'route' => 'dichvu.dsxengoai.index',
                    'icon' => 'fa fa-list-alt',
                    'active' => [],
                    'can' => ['dichvu.dsxengoai.index'],
                ],
                [
                    'text' => 'DS PT chờ thay thế',
                    'route' => 'dichvu.ds-phu-tung-cho-thay-the.index',
                    'icon' => 'fa fa-tasks',
                    'active' => [],
                    'can' => ['dichvu.ds-phu-tung-cho-thay-the.index'],
                ],
                [
                    'text' => 'Báo cáo',
                    'icon' => 'fa fa-pencil-square',
                    'sub_menu' => [
                        [
                            'text' => 'Báo cáo doanh thu theo công việc',
                            'route' => 'dichvu.baocaotheocongviec.index',
                            'active' => [],
                            'can' => ['dichvu.baocaotheocongviec.index'],
                        ],
                        [
                            'text' => 'Báo cáo doanh thu theo thợ',
                            'route' => 'dichvu.baocaotheotho.index',
                            'active' => [],
                            'can' => ['dichvu.baocaotheotho.index'],
                        ],
                        [
                            'text' => 'Báo cáo kiểm tra định kỳ',
                            'route' => 'dichvu.baocao.ktdk.index',
                            'active' => [],
                            'can' => ['dichvu.baocao.ktdk.index'],
                        ],
                        [
                            'text' => 'Báo cáo sửa chữa thông thường',
                            'route' => 'dichvu.baocao.sctt.index',
                            'active' => [],
                            'can' => ['dichvu.baocao.sctt.index'],
                        ],
                    ],
                ],
            ],
        ],

        /**
         * Phụ tùng
         */
        [
            'text' => 'Phụ tùng',
            'icon' => 'fa fa-wrench',
            'sub_menu' => [
                [
                    'text' => 'Bán buôn',
                    'route' => 'phutung.banbuon.index',
                    'icon' => 'fa fa-simplybuilt',
                    'active' => [],
                    'can' => ['phutung.banbuon.index'],
                ],
                [
                    'text' => 'Bán lẻ',
                    'route' => 'phutung.banle.index',
                    'icon' => 'fa fa-sign-language',
                    'active' => [],
                    'can' => ['phutung.banle.index'],
                ],
                [
                    'text' => 'Bán phụ tùng',
                    'route' => 'phutung.dsdonhang.index',
                    'icon' => 'fa fa-tags',
                    'active' => [],
                    'can' => ['phutung.dsdonhang.index'],
                ],
                [
                    'text' => 'Nhập phụ tùng',
                    'route' => 'phutung.nhapphutung.index',
                    'icon' => 'fa fa-cogs',
                    'active' => [],
                    'can' => ['phutung.nhapphutung.index'],
                ],
                [
                    'text' => 'Danh sách nhập phụ tùng',
                    'route' => 'phutung.dsphutungnhap.index',
                    'icon' => 'fa fa-list-alt',
                    'active' => [],
                    'can' => ['phutung.dsphutungnhap.index'],
                ],
                [
                    'text' => 'Chuyển kho phụ tùng',
                    'route' => 'quanlykho.chuyenkhophutung.index',
                    'icon' => 'fa fa-truck',
                    'active' => [],
                    'can' => ['quanlykho.chuyenkhophutung.index'],
                ],
                [
                    'text' => 'Ngoại lệ',
                    'icon' => 'fa fa-object-group',
                    'sub_menu' => [
                        [
                            'text' => 'Nhập ngoại lệ',
                            'route' => 'quanlykho.nhapngoaile.index',
                            'active' => [],
                            'can' => ['quanlykho.nhapngoaile.index'],
                        ],
                        [
                            'text' => 'Xuất ngoại lệ',
                            'route' => 'quanlykho.xuatngoaile.index',
                            'active' => [],
                            'can' => ['quanlykho.xuatngoaile.index'],
                        ],
                        [
                            'text' => 'LS nhập xuất ngoại lệ',
                            'route' => 'quanlykho.lichsunhapxuatngoaile.index',
                            'active' => [],
                            'can' => ['quanlykho.lichsunhapxuatngoaile.index'],
                        ],
                    ],
                ],
                [
                    'text' => 'Báo cáo',
                    'icon' => 'fa fa-pencil-square',
                    'sub_menu' => [
                        [
                            'text' => 'Báo cáo kho phụ tùng',
                            'route' => 'quanlykho.baocaokhophutung.index',
                            'active' => [],
                            'can' => ['quanlykho.baocaokhophutung.index'],
                        ],
                        [
                            'text' => 'BC phụ tùng theo rank',
                            'route' => 'quanlykho.baocaophutungtheorank.index',
                            'active' => [],
                            'can' => ['quanlykho.baocaophutungtheorank.index'],
                        ],
                        [
                            'text' => 'Báo cáo bán hàng phụ tùng',
                            'route' => 'phutung.baocao.index',
                            'active' => [],
                            'can' => ['phutung.baocao.index'],
                        ],
                    ],
                ],
            ],
        ],

        /**
         * CSKH
         */
        [
            'text' => 'CSKH',
            'icon' => 'fa fa-users',
            'sub_menu' => [
                [
                    'text' => 'Thống kê liên hệ Khách hàng',
                    'route' => 'cskh.ds-lien-he-khach-hang.index',
                    'icon' => 'fa fa-list-ol',
                    'active' => [],
                    'can' => ['cskh.ds-lien-he-khach-hang.index'],
                ],
                [
                    'text' => 'Send SMS',
                    'route' => 'cskh.cham-soc-khach-hang.index',
                    'icon' => 'fa fa-comments-o',
                    'active' => [],
                    'can' => ['cskh.cham-soc-khach-hang.index'],
                ],
                [
                    'text' => 'Chăm sóc khách hàng',
                    'route' => 'cskh.dich-vu-cham-soc-khach-hang.index',
                    'icon' => 'fa fa-heartbeat',
                    'active' => [],
                    'can' => ['cskh.dich-vu-cham-soc-khach-hang.index'],
                ],
            ],
        ],

        /**
         * Danh mục
         */
        [
            'text' => 'Danh mục',
            'icon' => 'fa fa-archive',
            'sub_menu' => [
                [
                    'text' => 'Danh sách khách hàng',
                    'route' => 'customers.index',
                    'icon' => 'fa fa-users',
                    'active' => ['khachhang/*', 'khachhang*'],
                    'can' => ['customers.index'],
                ],
                [
                    'text' => 'Danh sách MTOC',
                    'route' => 'mtoc.index',
                    'icon' => 'fa fa-microchip',
                    'active' => ['mtoc/*', 'mtoc*'],
                    'can' => ['mtoc.index'],
                ],
                [
                    'text' => 'Danh sách nhà cung cấp',
                    'route' => 'nhacungcap.dsnhacungcap.index',
                    'icon' => 'fa fa-building-o',
                    'active' => ['nhacungcap/*', 'nhacungcap*'],
                    'can' => ['nhacungcap.dsnhacungcap.index'],
                ],
                [
                    'text' => 'Danh sách kho',
                    'route' => 'kho.danhsach.index',
                    'icon' => 'fa fa-hdd-o',
                    'active' => ['kho/*', 'kho*'],
                    'can' => ['kho.danhsach.index'],
                ],
                [
                    'text' => 'Danh sách TK ngân hàng',
                    'route' => 'bank.index',
                    'icon' => 'fa fa-university',
                    'active' => ['bank/*', 'bank*'],
                    'can' => ['bank.index'],
                ],
                [
                    'text' => 'Danh sách ND Công việc',
                    'route' => 'work-content.index',
                    'icon' => 'fa fa-briefcase',
                    'active' => ['work-content/*', 'work-content*'],
                    'can' => ['work-content.index'],
                ],
                [
                    'text' => 'Dịch vụ nội bộ',
                    'route' => 'chinoibo.index',
                    'icon' => 'fa fa-address-book',
                    'active' => ['chinoibo/*', 'chinoibo*'],
                    'can' => ['chinoibo.index'],
                ],
                [
                    'text' => 'Danh mục dịch vụ khác',
                    'route' => 'servicelist.index',
                    'icon' => 'fa fa-list',
                    'active' => ['servicelist/*', 'servicelist*'],
                    'can' => ['servicelist.index'],
                ],
                [
                    'text' => 'Danh sách mã phụ tùng',
                    'route' => 'danhmucmaphutung.danhsach.index',
                    'icon' => 'fa fa-archive',
                    'active' => ['danhmucmaphutung/*', 'danhmucmaphutung*'],
                    'can' => ['danhmucmaphutung.danhsach.index'],
                ],
                [
                    'text' => 'Danh sách quà tặng',
                    'route' => 'quatang.index',
                    'icon' => 'fa fa-archive',
                    'active' => ['quatang/*', 'quatang*'],
                    'can' => ['quatang.index'],
                ],
                [
                    'text' => 'Danh sách công ty trả góp',
                    'route' => 'installment-company.index',
                    'icon' => 'fa fa-building-o',
                    'active' => ['installment-company/*', 'installment-company*'],
                    'can' => ['installment-company.index'],
                ],
                [
                    'text' => 'Hình thức liên hệ',
                    'route' => 'contact.index',
                    'icon' => 'fa fa-phone',
                    'active' => ['contact/*', 'contact*'],
                    'can' => ['contact.index'],
                ],
            ],
        ],

        /**
         * Hệ thống
         */
        [
            'text' => 'Hệ thống',
            'icon' => 'fa fa-cog',
            'sub_menu' => [
                [
                    'text' => 'Quản lý người dùng',
                    'route' => 'nguoiDung.index',
                    'icon' => 'fa fa-user-circle-o',
                    'active' => ['nguoiDung/*', 'nguoiDung*'],
                    'can' => ['nguoiDung.index'],
                ],
                [
                    'text' => 'Quản lý quyền',
                    'route' => 'roles.index',
                    'icon' => 'fa fa-gavel',
                    'active' => ['phanquyen/*', 'phanquyen*'],
                    'can' => ['roles.index'],
                ],
            ],
        ],
    ];

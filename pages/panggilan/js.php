<!-- DataTables -->
<script src="assets/vendor/js/datatables.min.js" type="text/javascript"></script>

<script type="text/javascript">
    var table = {};
    $(document).ready(function() {
        var bell = document.getElementById('tingtung');
        var loket = JSON.parse(localStorage.getItem('_loket'));
        // Get type antrian pada loket
        let loketParse = JSON.parse(list_loket);
        let indexLoket = loketParse.map(object => object.no_loket).indexOf(loket.no_loket);
        let typeAntrian = loketParse[indexLoket].handle_type_antrian;

        let listTypeAntrian = JSON.parse(list_type_antrian);

        $(".namaLoket").html(loket.nama_loket.toUpperCase());

        const get_actions = () => {
            // Get jumlah antrian
            $.ajax({
                url: 'pages/panggilan/action.php',
                method: 'GET',
                data: {
                    type: 'get_jumlah_antrian'
                },
                async: false,
                cache: false,
                dataType: 'json',
                success: function(result) {
                    if (result.success == true) {
                        if (result.data.length > 0) {
                            result.data.forEach(function(element, index) {
                                $('#jumlah-antrian-' + element.code_antrian.toLowerCase()).html(element.jumlah);
                            });
                        } else {
                            $("[id^='jumlah-antrian']").html('-');
                        }
                    }
                }
            });

            // Get sisa antrian
            $.ajax({
                url: 'pages/panggilan/action.php',
                method: 'GET',
                data: {
                    type: 'get_sisa_antrian'
                },
                async: false,
                cache: false,
                dataType: 'json',
                success: function(result) {
                    if (result.success == true) {
                        if (result.data.length > 0) {
                            typeAntrian.forEach(function(elemenType, indexType) {
                                $('#sisa-antrian-' + elemenType.toLowerCase()).html('-');
                                result.data.forEach(function(element, index) {
                                    if (elemenType === element.code_antrian) {
                                        $('#sisa-antrian-' + element.code_antrian.toLowerCase()).html(element.jumlah);
                                    }
                                });
                            });
                        } else {
                            $("[id^='sisa-antrian']").html('-');
                        }
                    }
                }
            });

            // Get antrian sekarang
            $.ajax({
                url: 'pages/panggilan/action.php',
                method: 'GET',
                data: {
                    type: 'get_antrian_sekarang'
                },
                async: false,
                cache: false,
                dataType: 'json',
                success: function(result) {
                    if (result.success == true) {
                        if (result.data.length > 0) {
                            $(".btn-now-waiting-dissabled").prop('disabled', true);
                            result.data.forEach(function(element, index) {
                                $('.antrian-sekarang-' + element.code_antrian.toLowerCase()).html(element.code_antrian + element.no_antrian);
                                $('.antrian-sekarang-' + element.code_antrian.toLowerCase()).parent().data('panggil_antrian', JSON.stringify(element));
                                $('.antrian-sekarang-' + element.code_antrian.toLowerCase()).parent().data('panggil_antrian', JSON.stringify(element));
                                $(".btn-now-waiting-" + element.code_antrian.toLowerCase()).prop('disabled', false);
                            });
                        } else {
                            $("[class*='antrian-sekarang']").html('-');
                            $(".btn-now-waiting-dissabled").prop('disabled', true);
                        }
                    }
                }
            });

            // Get antrian selanjutnya
            $.ajax({
                url: 'pages/panggilan/action.php',
                method: 'GET',
                data: {
                    type: 'get_antrian_selanjutnya'
                },
                async: false,
                cache: false,
                dataType: 'json',
                success: function(result) {
                    if (result.success == true) {
                        if (result.data.length > 0 && typeAntrian.length > 0) {
                            // $(".btn-antrian-selanjutnya").prop('disabled', true);
                            typeAntrian.forEach(function(elemenType, indexType) {
                                $('#antrian-selanjutnya-' + elemenType.toLowerCase().toLowerCase()).html('-');
                                $('#antrian-selanjutnya-' + elemenType.toLowerCase().toLowerCase()).parent().data('panggil_antrian', '');
                                $('.btn-antrian-selanjutnya-' + elemenType.toLowerCase()).prop('disabled', true);
                                result.data.forEach(function(element, index) {
                                    if (elemenType === element.code_antrian) {
                                        $('#antrian-selanjutnya-' + element.code_antrian.toLowerCase()).html(element.code_antrian + element.no_antrian);
                                        $('#antrian-selanjutnya-' + element.code_antrian.toLowerCase()).parent().data('panggil_antrian', JSON.stringify(element));
                                        $('.btn-antrian-selanjutnya-' + elemenType.toLowerCase()).prop('disabled', false);
                                    }
                                });
                            });
                        } else {
                            $("[id^='antrian-selanjutnya']").html('-');
                            $(".btn-antrian-selanjutnya-dissabled").prop('disabled', true);
                        }
                    }
                }
            });
        }

        // Jumlah antrian
        let panggilAntrianHtml = ``;
        typeAntrian.forEach(function(element, index) {
            let type = element.toLowerCase();
            let indexTypeAntrianByCode = listTypeAntrian.map(object => object.code_antrian).indexOf(element);
            panggilAntrianHtml += `<div class="col mb-4" id="tabel-antrian-${type}">
                <div class="card border border-success shadow-sm">
                    <div class="card-header text-center fw-bold">
                        <h4 class="fw-bold m-0">${listTypeAntrian[indexTypeAntrianByCode].type_antrian}</h4>
                    </div>
                    <div class="card-body p-2">
                        <div class="border p-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <p class="text-muted fs-6 mb-0">
                                    <i class="bi bi-check2-circle text-success"></i>
                                    JUMLAH ANTRIAN
                                    <span id="type-antrian-jumlah-${type}"></span>
                                </p>
                                <span id="jumlah-antrian-${type}" class="fs-6 text-success fw-bold">-</span>
                            </div>
                            <hr class="border border-success my-2">
                            <div class="d-flex justify-content-between">
                                <p class="text-muted fs-6 mb-0">
                                    <i class="bi bi-check2-circle text-danger"></i>
                                    SISA ANTRIAN
                                    <span id="type-antrian-sisa-${type}"></span>
                                </p>
                                <span id="sisa-antrian-${type}" class="fs-6 text-danger fw-bold">-</span>
                            </div>
                        </div>
                        <div class="border p-2 mb-2">
                            <div class="text-center">
                                <h6 class="fw-bold text-muted">NOMOR ANTRIAN SEKARANG</h6>
                                <h1 class="display-6 fw-bold text-success text-center lh-1 m-0 antrian-sekarang-${type}">-</h1>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="border w-100 p-2">
                                <div class="text-center">
                                    <h6 class="fw-bold text-muted">PANGGIL LAGI</h6>
                                    <button class="btn btn-secondary btn-lg fw-bold btn-now-waiting-dissabled btn-now-waiting-${type}" data-panggil_antrian="">
                                        <i class="bi-mic-fill me-2"></i> 
                                        <span class="antrian-sekarang-${type}">-</span>
                                    </button>
                                </div>
                            </div>
                            <div class="border w-100 p-2">
                                <div class="text-center">
                                    <h6 class="fw-bold text-muted">SELANJUTNYA</h6>
                                    <button class="btn btn-success btn-lg fw-bold btn-antrian-selanjutnya-dissabled btn-antrian-selanjutnya-${type}" data-panggil_antrian="">
                                        <i class="bi-mic-fill me-2"></i>
                                        <span id="antrian-selanjutnya-${type}">-</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        });
        $('#table-panggilan').html(panggilAntrianHtml);

        get_actions();

        typeAntrian.forEach(function(element, index) {
            // panggilan antrian dan update data
            $('#tabel-antrian-' + element.toLowerCase()).on('click', 'button', function() {
                // ambil data dari datatables 
                let getData = $(this).data('panggil_antrian');
                let data = (getData.length > 0) ? JSON.parse(getData) : null;

                // buat variabel untuk menampilkan data "id"
                let id = (data !== null && typeof data !== 'undefined') ? data["id"] : "";

                if (id !== '') {
                    // proses create panggilan antrian
                    $.ajax({
                        url: "pages/panggilan/action.php", // url file proses update data
                        type: "GET", // mengirim data dengan method POST
                        // tentukan data yang dikirim
                        dataType: 'json',
                        data: {
                            type: 'create_panggilan',
                            antrian: data['code_antrian'] + data["no_antrian"],
                            loket: loket.nama_loket
                        },
                        async: false,
                        cache: false,
                        success: function(data) {
                            console.log(data);
                        }
                    });

                    // proses update data
                    $.ajax({
                        url: "pages/panggilan/action.php", // url file proses update data
                        type: "GET", // mengirim data dengan method POST
                        // tentukan data yang dikirim
                        dataType: 'json',
                        data: {
                            type: 'update_antrian',
                            id: id
                        },
                        async: false,
                        cache: false,
                        success: function(data) {
                            console.log(data);
                        }
                    });
                }
            });
        });

        // auto reload data antrian setiap 1 detik untuk menampilkan data secara realtime
        setInterval(function() {
            get_actions();
        }, 1000);
    });
</script>
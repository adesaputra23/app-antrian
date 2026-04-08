<script type="text/javascript">
    $(document).ready(function() {
        var typeAntrian = JSON.parse(list_type_antrian);
        var total_terlayani = 0;
        var total_tidak_terlayani = 0;
        var html = ``;

        const get_actions = (type, start, end) => {
            let tmp_total_terlayani = 0;
            let tmp_total_tidak_terlayani = 0;

            // Get jumlah antrian
            $.ajax({
                url: 'pages/rekapitulasi/action.php',
                method: 'POST',
                data: {
                    type: 'list_antrian',
                    type_antrian: type.code_antrian,
                    start: start,
                    end: end
                },
                async: false,
                cache: false,
                dataType: 'json',
                success: function(result) {
                    if (result.success == true) {
                        if (result.data.length > 0) {
                            result.data.forEach(function(element, index) {
                                if (element.status == 1) {
                                    total_terlayani += 1;
                                    tmp_total_terlayani += 1;
                                } else {
                                    total_tidak_terlayani += 1;
                                    tmp_total_tidak_terlayani += 1;
                                }
                            });
                            let tmp_total = tmp_total_terlayani + tmp_total_tidak_terlayani;
                            let tmp_html = `<div class="col my-3">
                                <div class="card border border-success">
                                    <div class="card-header text-center">
                                        Rekapitulasi ${type.type_antrian}
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped table-hover" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Deskripsi</th>
                                                    <th class="text-center">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Jumlah Antrian Terlayani</td>
                                                    <td class="text-center">${tmp_total_terlayani}</td>
                                                </tr>
                                                <tr>
                                                    <td>Jumlah Antrian Tidak Terlayani</td>
                                                    <td class="text-center">${tmp_total_tidak_terlayani}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="col">Total Antrian</th>
                                                    <th scope="col" class="text-center">${tmp_total}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>`;
                            html += tmp_html;
                        } else {

                        }
                    }
                }
            });
        }

        const getData = () => {
            var start = $("#start").val();
            var end = $("#end").val();
            typeAntrian.forEach((element, index) => {
                get_actions(element, start, end);
            });

            var total = total_terlayani + total_tidak_terlayani;
            html += `<div class="col my-3">
                        <div class="card border border-success">
                            <div class="card-header text-center">
                                Rekapitulasi Seluruh Antrian
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Deskripsi</th>
                                            <th class="text-center">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Jumlah Antrian Terlayani</td>
                                            <td class="text-center">${total_terlayani}</td>
                                        </tr>
                                        <tr>
                                            <td>Jumlah Antrian Tidak Terlayani</td>
                                            <td class="text-center">${total_tidak_terlayani}</td>
                                        </tr>
                                        <tr>
                                            <th scope="col">Total Antrian</th>
                                            <th scope="col" class="text-center">${total}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>`;
            $("#tabel-antrian").html(html).fadeIn("slow");
            total_terlayani = 0;
            total_tidak_terlayani = 0;
            total = 0;
            html = ``;
        }

        getData();

        $(document).on("click", "#btn-print", function() {
            window.print();
        });

        $(document).on("click", "#btn-get-data", function() {
            getData();
        });
    });
</script>
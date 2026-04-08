<?php
$css = 'css.php';
?>

<main class="flex-shrink-0">
    <div class="container pt-4">
        <div class="d-flex flex-column flex-md-row px-3 py-2 mb-4 bg-white rounded-1 shadow-sm border border-success" id="header-antrian">
            <!-- judul halaman -->
            <div class="d-flex align-items-center me-md-auto">
                <i class="bi-mic-fill text-success me-3 fs-3"></i>
                <h1 class="fw-bold h5 pt-2">REKAPITULASI ANTRIAN</h1>
            </div>
            <!-- breadcrumbs -->
            <div class="ms-5 ms-md-0 pt-md-3 pb-md-0">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/"><i class="bi-house-fill text-success"></i></a></li>
                        <li class="breadcrumb-item text-muted" aria-current="page">Rekapitulasi Antrian</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row justify-content-center" id="body-antrian">
            <div class="col mb-4">
                <div class="card border-success shadow-sm">
                    <div class="card-header d-flex justify-content-between" id="card-header-1">
                        Rekapitulasi Antrian
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center mb-3" id="form-filter">
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label for="start">Tanggal Awal</label>
                                <input type="date" class="form-control" id="start" value="<?= date("Y-m-d"); ?>">
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label for="end">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="end" value="<?= date("Y-m-d"); ?>">
                            </div>
                            <div class="col-md-2 col-sm-12 col-xs-12 d-flex justify-content-start align-items-end gap-4">
                                <button type="button" class="btn btn-primary" id="btn-get-data">
                                    <i class="bi-search"></i>
                                </button>
                                <button type="button" class="btn btn-info" id="btn-print">
                                    <i class="bi-file-earmark-pdf text-white"></i>
                                </button>
                            </div>
                        </div>
                        <div id="header-print" style="display: none;">
                            <div class="row text-center mb-4">
                                <span class="fw-bold fs-4">REKAPITULASI ANTRIAN</span>
                                <span class="fw-lighter text-muted" id="range-tanggal">12 Januari 2029 - 13 February 2029</span>
                            </div>
                        </div>
                        <div class="row justify-centent-center m-0" id="tabel-antrian"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    var list_type_antrian = '<?= $data['list_type_antrian'] ?>';
</script>
<?php $js = 'js.php'; ?>
<?php
$list_type_antrian = json_decode($data['list_type_antrian'], true);
?>
<style>
	.new-card {
		background-color: #483C32;
		font-size: 1.3rem;
		width: 22rem;
		height: 20rem;
		margin-bottom: 2.2rem;
		color: #D4BC8C;
		text-decoration: none;
		text-align: center;
		border-radius: 5px;
		box-shadow: 0px 0px 8px 1px rgba(0, 0, 0, 0.41);
	}
</style>
<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST']; // localhost

// Ambil script path
$scriptName = $_SERVER['SCRIPT_NAME']; // contoh: /app-anjungan-mandiri-rsud-sumbawa/index.php

// Ambil nama folder project (bagian pertama setelah slash)
$segments = explode('/', trim($scriptName, '/'));
$projectFolder = $segments[0]; // app-anjungan-mandiri-rsud-sumbawa

$baseUrl = $protocol . "://" . $host . '/' . $projectFolder;
?>
<main class="flex-shrink-0 bg-white" style="min-height: 100vh !important;">
	<div class="col-md-12" style="background-color:<?= $data['warna_primary'] ? $data['warna_primary'] : '#6B5935' ?>;">
		<div class="row px-3 py-4" style="height: 100%;">
			<div class="col-2">
				<img class="img-fluid d-block mx-auto"
					src="<?= $data['logo'] && file_exists('assets/img/' . $data['logo']) ? 'assets/img/' . $data['logo'] : 'assets/img/default.png' ?>"
					alt="Image" class="mr-3" style="max-width: 70px;">
			</div>
			<div class="col-8 text-center text-white">
				<h4 class="card-title"><?= $data['nama_instansi'] ? $data['nama_instansi'] : ''; ?></h4>
				<h6 class="card-text"><?= $data['alamat'] ? $data['alamat'] : ''; ?></h6>
				<p class="card-text">Tlp. <?= $data['telpon'] ? $data['telpon'] : ''; ?>, Email.
					<?= $data['email'] ? $data['email'] : ''; ?>
				</p>
			</div>
			<div class="col-2">
				<!-- <img class="img-fluid d-block mx-auto" src="<?= $data['logo'] && file_exists('assets/img/' . $data['logo']) ? 'assets/img/' . $data['logo'] : 'assets/img/default.png' ?>" alt="Image" class="mr-3" style="max-width: 70px;"> -->
			</div>
		</div>
	</div>
	<div style="height: 5vh;"></div>
	<div class="container mb-5">

		<div class="row g-4">
			<div class="col-sm-9 col-md-12">
				<div class="row row-cols-12 justify-content-lg">
					<?php if (count($list_type_antrian) > 0): ?>
						<?php foreach ($list_type_antrian as $lta): ?>
							<div class="col mb-4 d-none kategori-antrian" id="<?= $lta['code_antrian']; ?>">
								<div class="px-4 py-3 mb-4 bg-white rounded-2 shadow-sm">
									<!-- judul halaman -->
									<div class="d-flex align-items-center me-md-auto">
										<i class="bi-people-fill text-success me-3 fs-3"></i>
										<h1 class="h5 pt-2"><span class="fw-bold" style="font-size: 1.5rem;">ANTRIAN
												<?= $lta['type_antrian']; ?></span></h1>
									</div>
									<div class="card-body border rounded-2 p-2" style="padding: 0; height: 15rem;">
										<ul>
											<div id="list-antrian-<?= $lta['code_antrian'] ?>" class="row"
												style="font-size: 1.25rem; font-weight: medium;"></div>
										</ul>
										<!-- akan diisi via JS -->
									</div>
								</div>

								<div class="card border-0 shadow-sm">
									<div class="card-body text-center d-grid p-5">
										<div class="border border-success rounded-2 py-2 mb-5" style="min-height: 20vh;">
											<h3 class="pt-4">ANTRIAN</h3>
											<!-- menampilkan informasi jumlah antrian -->
											<h1 id="antrian-<?= $lta['code_antrian'] ?>"
												class="display-1 fw-bold text-success text-center lh-1 pb-2"
												style="font-family: Arial, Helvetica, sans-serif;"></h1>
										</div>
										<!-- button pengambilan nomor antrian -->
										<a id="insert-<?= $lta['code_antrian'] ?>"
											data-code_antrian="<?= $lta['code_antrian']; ?>"
											data-type_antrian="<?= $lta['type_antrian']; ?>" href="javascript:void(0)"
											class="btn btn-success btn-block rounded-pill fs-5 px-5 py-4 mb-2">
											<i class="bi-person-plus fs-4 me-2"></i> Ambil
										</a>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
			<!-- <div class="col-6 col-md-4">
				<div class="card border-0 shadow-sm p-3 mb-5 bg-body rounded">
					<div class="card-body">
						<div class="card-title text-center">Untuk Chekin, Silahkan Scan QR Code di Bawah ini Melalui
							Aplikasi Mobile JKN</div>
					</div>
					<img src="<?= $baseUrl; ?>/assets/0012R001.png">
					<button class="btn btn-sm btn-primary float-end" id="KodeBooking" href="javascript:void(0)">
						Lanjutkan Untuk Cetak SEP
					</button>
				</div>
			</div> -->
		</div>
	</div>

	<!-- modal dialog select zone -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
		aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="myModalLabel">Informasi</h5>
					<button type="button" class="close" id="btn-close-zone" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="card mb-2">
						<div class="card-body">
							<p class="text-center text-danger" style="font-size: 12px !important;">Keterangan : form ini
								digunakan untuk menampilkan Antrian Kategori yang ingin ditampilkan dilayar mesin
								anjungan untuk cetak no antrian!</p>
						</div>
					</div>
					<?php if (count($list_type_antrian) > 0) { ?>
						<label for="">Silahkan pilih Antrian Kategori</label>
						<?php foreach ($list_type_antrian as $lta) { ?>
							<div class="form-check ms-3 mt-2">
								<input class="form-check-input" type="checkbox" value="<?= $lta['code_antrian'] ?>"
									id="type_antrian_<?= $lta['code_antrian'] ?>">
								<label class="form-check-label" for="type_antrian_<?= $lta['code_antrian'] ?>">
									ANTRIAN <?= $lta['type_antrian']; ?>
								</label>
							</div>
						<?php } ?>
					<?php } else { ?>
						<div class="text-center">
							<span>Data kategori tidak ditemukan!</span>
						</div>
					<?php } ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" id="btn-close-zone"
						data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="btn-simpan-zone">Simpan</button>
				</div>
			</div>
		</div>
	</div>
	<!-- end modal dialog select zone -->

</main>
<script>
	var list_type_antrian = '<?= $data['list_type_antrian'] ?>';
	var setUrl = '<?= $baseUrl ?>'
</script>
<?php $js = 'js.php'; ?>

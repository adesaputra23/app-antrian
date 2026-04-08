<?php
$css = 'css.php';
$hariIni = new DateTime();
$monitor = isset($_GET['monitor']) ? $_GET['monitor'] : '';
$config_monitor = (!empty($data['config_monitor'])) ? json_decode($data['config_monitor'], true) : [];
$index = array_search($monitor, array_column($config_monitor, 'no_monitor'));

$handleCategories = $index !== false
	? json_decode($config_monitor[$index]['handle_category_monitor'], true)
	: [];

function hariIndo($hariInggris)
{
	switch ($hariInggris) {
		case 'Sunday':
			return 'Minggu';
		case 'Monday':
			return 'Senin';
		case 'Tuesday':
			return 'Selasa';
		case 'Wednesday':
			return 'Rabu';
		case 'Thursday':
			return 'Kamis';
		case 'Friday':
			return 'Jumat';
		case 'Saturday':
			return 'Sabtu';
		default:
			return 'hari tidak valid';
	}
}
$list_type_antrian = (!empty($data['list_type_antrian'])) ? array_filter(json_decode($data['list_type_antrian'], true), function ($item) use ($handleCategories) {
	return in_array($item['code_antrian'], $handleCategories);
}) : [];
?>
<main class="flex-shrink-0">
	<div class="d-flex justify-content-between align-items-center px-3"
		style="height: 10vh; background-color:<?= $data['warna_secondary'] ? $data['warna_secondary'] : '#6B5935' ?>;">
		<img class="img-fluid d-block"
			src="<?= $data['logo'] && file_exists('assets/img/' . $data['logo']) ? 'assets/img/' . $data['logo'] : 'assets/img/default.png' ?>"
			alt="Image" class="mr-3" style="max-width: 50px;">
		<div class="text-white text-center">
			<h3 class="nama-instansi fw-bold"><?= $data['nama_instansi'] ? $data['nama_instansi'] : ''; ?></h3>
			<p class="fw-lighter lh-1 m-1">
				<?= $data['alamat'] ? $data['alamat'] : ''; ?>
				<br>
				Tlp. <?= $data['telpon'] ? $data['telpon'] : ''; ?>, Email. <?= $data['email'] ? $data['email'] : ''; ?>
			</p>
		</div>
		<div style="color:<?= $data['warna_text'] ? $data['warna_text'] : '#fff' ?> ">
			<i class="bi bi-calendar2-check"></i>
			<span id="date"><?= hariIndo(date('l')) . ", " . strftime('%d %B %Y', $hariIni->getTimestamp()); ?></span>
			<br>
			<i class="bi bi-clock-history"></i>
			<span id="time"></span>
		</div>
	</div>
	<div class="d-flex justify-content-between mx-1 mt-1" style="height: 45vh;">
		<div class="col-md-5 p-1" style="height: 45vh;">
			<iframe class="rounded embed-responsive-item" style="width: 100%; height: 45vh;" allow="autoplay"
				src="https://www.youtube.com/embed/<?= $data['youtube_id'] ? $data['youtube_id'] : ''; ?>?rel=0&modestbranding=1&autohide=1&mute=0&showinfo=0&controls=1&loop=1&autoplay=1&playlist=<?= $data['youtube_id'] ? $data['youtube_id'] : ''; ?>" muted>
			</iframe>
		</div>
		<div class="col-md-7 p-1" style="color:<?= $data['warna_text'] ? $data['warna_text'] : '#fff' ?>; height: 45vh;">
			<div class="card text-center text-white" style="height: 45vh; background-color: #0A7525;">
				<h2 class="card-header fw-bold">NOMOR ANTRIAN SEKARANG</h2>
				<div class="card-body text-center d-flex justify-content-center align-items-center">
					<h1 id="antrian-sekarang" class="text-center fw-bold py-0" style="font-size: 120px;">-</h1>
				</div>
				<h1 class="card-footer namaLoketMonitor fw-bold" style="font-size: 50px;">-</h1>
			</div>
		</div>
	</div>
	<div class="d-flex justify-content-center px-1" style="height: 35vh; margin-top: 12px;">
		<?php
		$bg_color = [
			'bg-primary',
			'bg-warning',
			'bg-success',
			'bg-secondary',
			'bg-danger'
		];
		$key_bg = 0;
		?>
		<?php if (count($list_type_antrian) > 0): ?>
			<?php foreach ($list_type_antrian as $key_lta => $val_lta): ?>
				<?php
				if ($key_bg != count($bg_color)) {
					$bg = $bg_color[$key_bg];
				} else {
					$key_bg = 0;
					$bg = $bg_color[$key_bg];
				}
				?>
				<div class="card text-center text-white mx-1 " style="width: 100%; height: 35vh; background-color: #16537e;">
					<h2 class="card-header fw-bold"><?= $val_lta['type_antrian']; ?></h2>
					<div class="card-body d-flex justify-content-center align-items-center">
						<h1 id="code-antrian-<?= strtolower($val_lta['code_antrian']) ?>" class="text-center fw-bold"
							style="font-size: 90px;">-</h1>
					</div>
				</div>
				<?php $key_bg++; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</main>
<script>
	const config_monitor = <?= json_encode($config_monitor); ?>;
</script>

<!-- load file audio bell antrian -->
<audio id="tingtung" src="assets/audio/tingtung.mp3"></audio>
<?php $js = 'js.php'; ?>

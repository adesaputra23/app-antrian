<!-- Get API Key -> https://responsivevoice.org/ -->
<script src="assets/vendor/js/responsivevoice.js" type="text/javascript"></script>
<!-- <script src="https://code.responsivevoice.org/responsivevoice.js?key=vqryvYFl"></script> -->

<script>
	const params = new URLSearchParams(window.location.search);

	const monitor = JSON.parse(
		config_monitor.find((item) => item.no_monitor === params.get('monitor')).handle_category_monitor
	); // ["A", "B"]

	$(document).ready(function () {
		// buat variabel untuk menampilkan audio bell antrian
		var bell = document.getElementById('tingtung');
		var queuePanggil = [];
		var SubmitQueuePanggil = [];
		var currentPanggil = 0;
		var isPlay = false;

		// Get antrian sekarang
		const get_antrian = () => $.ajax({
			url: 'pages/panggilan/action.php',
			method: 'GET',
			data: {
				type: 'get_antrian_sekarang'
			},
			async: false,
			cache: false,
			dataType: 'json',
			success: function (result) {
				if (result.success == true) {
					if (result.data.length > 0) {
						result.data.forEach(function (element, index) {
							$('#code-antrian-' + element.code_antrian.toLowerCase()).html(element.code_antrian + element.no_antrian).fadeIn('slow');
						});
						isPlay = false;
					} else {
						$("[id^='code-antrian']").html('-');
						$("#antrian-sekarang").html('-');
						$(".namaLoketMonitor").html('-');
						isPlay = true;
					}
				}
			}
		});

		const checkQueuePanggil = (key, arrayOfQueue) => {
			var result = false;
			for (let i = 0; i < arrayOfQueue.length; i++) {
				if (arrayOfQueue[i].id === key) {
					result = true;
				}
			}
			return result;
		}

		function get_panggilan() {
			$.ajax({
				url: 'pages/monitor/action.php',
				method: 'POST',
				data: {
					type: 'get_panggilan'
				},
				async: false,
				cache: false,
				dataType: 'json',
				success: function (result) {
					if (result.success == true) {
						if (result.data.length > 0) {
							result.data.forEach(function (element, index) {
								if (checkQueuePanggil(element.id, queuePanggil)) {
									return;
								}
								queuePanggil.push(element);
								if (!isPlay) {
									panggilAntrian();
								}
							});
						}
					}
				}
			});
		};

		const delete_panggilan = (id) => {
			$.ajax({
				url: 'pages/monitor/action.php',
				method: 'POST',
				data: {
					type: 'delete_panggilan',
					id: id
				},
				async: false,
				cache: false,
				dataType: 'json',
				success: function (result) {
					console.log(result.message);
				}
			});
		}

		get_antrian();
		get_panggilan();

		// auto reload data antrian setiap 1 detik untuk menampilkan data secara realtime
		setInterval(function () {
			get_antrian();
			get_panggilan();
		}, 1000);

		function panggilAntrian() {
			if (queuePanggil.length > 0) {
				let value = queuePanggil[queuePanggil.length - 1];
				let no_antrian = value.antrian
				const code_antrian = no_antrian.slice(0, -3)
				if (!isPlay) {
					if (monitor.includes(code_antrian)) {
						isPlay = true;
						$("#antrian-sekarang").html(value.antrian);
						$(".namaLoketMonitor").html(value.loket.toUpperCase());
						// mainkan suara bell antrian
						bell.currentTime = 0;
						bell.pause();
						bell.play();

						// set delay antara suara bell dengan suara nomor antrian
						durasi_bell = bell.duration * 770;


						// mainkan suara nomor antrian
						console.log("suara")
						setTimeout(function () {
							let format_no_antrian = no_antrian[0] + ", " + Number(no_antrian.slice(1))
							responsiveVoice.speak("Nomor Antrian, " + format_no_antrian + ", menuju, " + value.loket, "Indonesian Female", {
								rate: 0.9,
								pitch: 1,
								volume: 1,
								onend: () => {
									// queuePanggil.splice(index, 1);
									isPlay = true;
									if (queuePanggil.length > 0) {
										panggilAntrian();
									}
								}
							});
							isPlay = false;
						}, durasi_bell);
					} else {
						console.log("tidak suara")
					}
				}
			}
		}
	});
</script>

<script>
	jam();

	function jam() {
		var e = document.getElementById("time"),
			d = new Date(),
			h,
			m,
			s;
		h = d.getHours();
		m = set(d.getMinutes());
		s = set(d.getSeconds());

		e.innerHTML = h + ":" + m + ":" + s;

		setTimeout("jam()", 1000);
	}

	function set(e) {
		e = e < 10 ? "0" + e : e;
		return e;
	}
</script>

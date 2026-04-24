<script type="text/javascript">
	const daftar_poli = JSON.parse(list_type_antrian);
	var idInterval = false;

	function extractword(str, start, end) {
		let string = str.substring(
			str.indexOf(start) + 1,
			str.lastIndexOf(end)
		);
		return string;
	}

	const getAntrian = () => {
		$.ajax({
			url: 'pages/nomor/action.php',
			method: 'POST',
			data: {
				type: 'get_antrian'
			},
			async: false,
			cache: false,
			dataType: 'json',
			success: function(result) {
				if (result.success == true) {
					if (result.data.length > 0) {
						$.each(result.data, function(index, value) {
							// Tampilkan nomor antrian
							$("#antrian-" + value.code_antrian)
								.html(`${value.code_antrian}${value.no_antrian}`)
								.fadeIn('slow');
						});

					} else {
						// Kosongkan jika tidak ada data antrian
						$("[id^='antrian']").html('');
						$("[id^='list-antrian']").html('');
					}
				}
			}
		});
	}

	$(document).ready(function() {
		// tampilkan jumlah antrian
		getAntrian();
		showPoli();

		$(document).delegate("a[id^='insert']", "click", function(e) {
			var tombolAmbil = $(this);
			let code_antrian = tombolAmbil.data('code_antrian');

			$.ajax({
				url: 'pages/nomor/action.php',
				type: 'POST',
				data: {
					type: 'create_antrian',
					code_antrian: code_antrian
				},
				dataType: 'json',
				beforeSend: function(data) {
					tombolAmbil.addClass("disabled");
					tombolAmbil.html(`
						<span class="spinner-grow spinner-grow-sm my-2" role="status" aria-hidden="true"></span>
					`);

					clearInterval(idInterval);
				},
				success: function(result) {
					if (result.success == true) {
						// let no_antrian = result.data.code_antrian + result.data.no_antrian;
						// printAntrian(no_antrian);
						getAntrian();
					} else {
						alert('Eits ada masalah nih, hubungi IT Support yaa!');
					}
				},
				error: function(data) {
					var loadExtractData = '{' + extractword(data.responseText, '{', '}') + '}';
					var loadExtractDataParse = JSON.parse(loadExtractData);
					if (loadExtractDataParse.success == true) {
						getAntrian();
						console.error('Printer error:', JSON.stringify(data));
						// alert(`Antrian anda ${loadExtractDataParse.data.code_antrian}${loadExtractDataParse.data.no_antrian} berhasil diambil, tapi printer bermasalah!`);
					}
				},
				complete: function(data) {
					tombolAmbil.removeClass("disabled");
					tombolAmbil.html(`<i class="bi-person-plus fs-4 me-2"></i> AMBIL`);

					startInterval();
				}
			});
		});

		const startInterval = () => {
			idInterval = setInterval(() => {
				getAntrian();
				showPoli();
			}, 1000);
		}

		startInterval();
	});

	function printAntrian(nomorAntrian) {


		// Jangan tampilkan kertas kosong
		if (!nomorAntrian || typeof nomorAntrian !== "string" || nomorAntrian.trim() === "" || nomorAntrian === "undefined" || nomorAntrian === "null") {
			return;
		}

		// Buat iframe tersembunyi untuk mencetak agar tidak muncul kertas kosong pada parent
		const iframe = document.createElement('iframe');
		iframe.style.position = "fixed";
		iframe.style.right = "0";
		iframe.style.bottom = "0";
		iframe.style.width = "0";
		iframe.style.height = "0";
		iframe.style.border = "0";
		iframe.style.visibility = "hidden";
		document.body.appendChild(iframe);

		const doc = iframe.contentWindow || iframe.contentDocument;
		const win = iframe.contentWindow || iframe;

		const printContent = `
			<!DOCTYPE html>
			<html>
			<head>
			<meta charset="utf-8">
			<title>Antrian Cetak</title>
			<style>
			@media print {
				body * {
					visibility: hidden !important;
				}
				#print-area, #print-area * {
					visibility: visible !important;
				}
				#print-area {
					position: absolute !important;
					top: 0;
					left: 0;
					width: 40mm !important;
					min-width: 0 !important;
					max-width: none !important;
					height: auto !important;
					box-sizing: border-box;
					display: block !important;
					margin: 0 !important;
					padding: 0 !important;
					background: white !important;
					page-break-after: avoid !important;
				}
				.ticket {
					border: 1px dashed #000;
					text-align: center;
					width: 36mm;
					min-width: 30mm;
					max-width: 38mm;
					margin: 0 auto;
					background: #fff;
					display: flex;
					flex-direction: column;
					justify-content: center;
					align-items: center;
					padding: 10px 0;
					/* Tambahkan margin top agar tidak nempel header */
					margin-top: 14px !important;
				}
				.queue-number {
					font-size: 1.5em;
					font-weight: bold;
					margin-bottom: 5px;
					letter-spacing: 0.1em;
					margin-top: 10px !important; /* Tambah margin top untuk nomor antrian */
				}
				.info {
					font-size: 0.85em;
					margin-bottom: 3px;
				}
				html, body {
					width: 40mm !important;
					height: auto !important;
					overflow: visible !important;
					background: white !important;
					margin: 0 !important;
					padding: 0 !important;
				}
			}
			@page {
				size: 40mm 60mm;
				margin: 0mm;
			}
			</style>
			</head>
			<body>
				<div id="print-area">
					<div class="ticket">
						<div class="queue-number">${nomorAntrian}</div>
						<div class="info">Silakan menunggu</div>
						<div class="info">Akan dipanggil sesuai urutan</div>
					</div>
				</div>
			</body>
			</html>
		`;

		// Tulis konten ke iframe dan cetak, lalu hapus iframe
		const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
		iframeDoc.open();
		iframeDoc.write(printContent);
		iframeDoc.close();

		iframe.onload = function() {
			setTimeout(function() {
				win.focus();
				win.print();
				document.body.removeChild(iframe);
			}, 200);
		};
	}

	function showPoli() {
		const list_type = JSON.parse(list_type_antrian);
		let listPoliHTML = "";
		list_type.map((item, index) => {
			const data = daftar_poli.find(poli => poli.code_antrian === item.code_antrian);
			if (data.list_poli.length <= 5) {
				const listPoliHTML = data.list_poli.map(poli => `
					<li>${poli}</li>
				`).join('');
				$("#list-antrian-" + item.code_antrian).html(listPoliHTML);
			} else {
				const listPoliHTML = data.list_poli.map(poli => `
					<div class="col-6">
						<li>${poli}</li>
					</div>
				`).join('');
				$("#list-antrian-" + item.code_antrian).html(listPoliHTML);
			}
		})
	}

	$(document).ready(function() {
		$("#myModal").modal('show');
	});

	$(document).on('click', '#btn-simpan-zone', function(e) {
		e.preventDefault();
		var selectedValues = [];
		$("input[type='checkbox']:checked").each(function() {
			let idKategori = '#' + $(this).val();
			$(idKategori).removeClass('d-none');
			selectedValues.push($(this).val());
		});
		if (selectedValues.length === 0) {
			$('.kategori-antrian').removeClass('d-none');
		}
		$("#myModal").modal('hide');
	})

	$(document).on('click', '#btn-close-zone', function(e) {
		e.preventDefault();
		$('.kategori-antrian').removeClass('d-none');
	})

	$(document).ready(function() {
		window.onload = function() {
			document.getElementById("kodebooking").focus();
		}

		$(document).ready(function() {
			$('#KodeBooking').on('click', function() {
				$('#KodeBooking').on('click', function() {
					Swal.fire({
						title: 'Scan No Kartu BPJS / Rekam Medik Anda Disini',
						input: 'text',
						inputAttributes: {
							autocapitalize: 'off'
						},
						showCancelButton: true,
						cancelButtonText: 'Tidak',
						cancelButtonColor: "#3085d6",
						confirmButtonText: 'Lanjutkan',
						confirmButtonColor: "#d33",
						showLoaderOnConfirm: true,
						preConfirm: (noKartu) => {
							var isUrl = setUrl + '/jkn_online/chekin_sep_auto/' + noKartu + '';
							return fetch(isUrl)
								.then(response => {
									if (!response.ok) {
										throw new Error(response.statusText)
									}
									return response.json()
								})
								.catch(error => {
									Swal.showValidationMessage(
										`Request failed: ${error}`
									)
								})
						},
						allowOutsideClick: () => !Swal.isLoading()
					}).then((result) => {
						if (result.isConfirmed) {
							if (result.value.metadata.code == '200') {
								Swal.fire({
									title: '' + result.value.metadata.message + '',
									showConfirmButton: false,
									timer: 10000
								});
								var setUrl = setUrl + 'jkn_online/cetak_sep/' + no_sep + '';
								var no_sep = result.value.metadata.noSep;
								window.open(setUrl, 'popUpWindow', 'height=500,width=1000,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');
							} else {
								Swal.fire({
									title: '' + result.value.metadata.message + '',
									showConfirmButton: false,
									timer: 10000
								})
							}
						}
					})
				});
			});
		});

	});
</script>
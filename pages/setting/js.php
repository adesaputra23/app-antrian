<script type="text/javascript">
	let optionLoket = ``;
	let optionPoli = ``;

	parseTypeAntrian.forEach(function (item, index) {
		optionLoket += `<option value="` + item.code_antrian + `">` + item.type_antrian + `</option>`;
	});

	parseListPoli.forEach(function (item, index) {
		optionPoli += `<option value="` + item + `">` + item + `</option>`;
	});

	var totalLoket = 0;
	var totalAntrian = 0;

	const htmlPoli = `
		<div class="row col-4 block_row">
			<div class="col-10">
				<div class="mb-3">
					<input type="text" class="form-control" name="nama_poli[]" placeholder="Kode Antrian" required>
				</div>
			</div>
			<div class="col-1">
				<div class="d-flex justify-content-center align-items-center">
					<button type="button" class="btn btn-danger btn-sm mt-1 deletePoli">
						<i class="bi-trash text-white"></i>
					</button>
				</div>
			</div>
		</div>`;

	const htmlMonitor = `
		<div class="row block_row">
			<div class="col-11">
				<div class="row">
					<div class="col-3">
						<div class="mb-3">
							<input type="text" class="form-control form-control-lg" name="no_monitor[]"
								placeholder="Nomor Monitor" required>
						</div>
					</div>
					<div class="col-9">
						<div class="mb-3">
							<select class="form-control form-control-sm handleMonitor"
								data-selected="[]" name="handle_category_monitor[` + (totalLoket + 1) + `][]" multiple="multiple">
									` + optionLoket + `
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="col-1">
				<div class="d-flex justify-content-center align-items-center">
					<button type="button" class="btn btn-danger btn-sm mt-1 deleteMonitor"><i
							class="bi-trash text-white"></i></button>
				</div>
			</div>
		</div>
	`;

	$(document).on("click", ".addLoket", function (e) {
		totalLoket = $(this).data('total_loket');
		$(this).data('total_loket', (totalLoket + 1));
		const htmlLoket = `<div class="row block_row">
						<div class="col-11">
							<div class="row">
								<div class="col-2">
									<div class="mb-3">
										<input type="text" class="form-control"  name="no_loket[]" placeholder="Nomor Loket" required>
									</div>
								</div>
								<div class="col-4">
									<div class="mb-3">
										<input type="text" class="form-control"  name="nama_loket[]" placeholder="Nama Loket" required>
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3">
										<select class="form-control form-control-sm handleTypeAntrian" data-selected="[]" name="handle_type_antrian[` + (totalLoket + 1) + `][]" multiple="multiple">
										` + optionLoket + `
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="col-1">
							<div class="d-flex justify-content-center align-items-center">
								<button type="button" class="btn btn-danger btn-sm mt-1 deleteLoket"><i class="bi-trash text-white"></i></button>
							</div>
						</div>
					</div>`;
		$("#blockLoket").append(htmlLoket);
		$(".handleTypeAntrian").select2({
			theme: "bootstrap-5",
			placeholder: "Pilih Type Antrian"
		});
	});

	$(document).on("click", ".addType", function (e) {
		totalAntrian = $(this).data('total_antrian');
		$(this).data('total_antrian', (totalAntrian + 1));
		const htmlType = `
			<div class="row block_row">
				<div class="col-11">
					<div class="row">
						<div class="col-3">
							<div class="mb-3">
								<input type="text" class="form-control"  name="type_antrian[]" placeholder="Tipe Antrian" required>
							</div>
						</div>
						<div class="col-3">
							<div class="mb-3">
								<input type="text" class="form-control"  name="code_antrian[]" placeholder="Kode Antrian" required>
							</div>
						</div>
						<div class="col-6">
							<div class="mb-3">
								<select class="form-control form-control-sm handleJenisPoli" data-selected="[]" name="handle_jenis_poli[` + (totalAntrian + 1) + `][]" multiple="multiple">
								` + optionPoli + `
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="col-1">
					<div class="d-flex justify-content-center align-items-center">
						<button type="button" class="btn btn-danger btn-sm mt-1 deleteType"><i class="bi-trash text-white"></i></button>
					</div>
				</div>
			</div>`;
		$("#blockType").append(htmlType);
		$(".handleJenisPoli").select2({
			theme: "bootstrap-5",
			placeholder: "Pilih Jenis Poli"
		});
	});

	$(document).on("click", ".addPoli", function (e) {
		$("#blockPoli").append(htmlPoli);
	});

	$(document).on("click", ".addMonitor", function (e) {
		$("#blockMonitor").append(htmlMonitor);
		$(".handleMonitor").select2({
			theme: "bootstrap-5",
			placeholder: "Pilih Kategori Antrian"
		});
	});

	$(document).on("click", ".deleteLoket", function (e) {
		$(this).parents(".block_row").remove();
	});

	$(document).on("click", ".deleteType", function (e) {
		$(this).parents(".block_row").remove();
	});

	$(document).on("click", ".deletePoli", function (e) {
		$(this).parents(".block_row").remove();
	});

	$(document).on("click", ".deleteMonitor", function (e) {
		$(this).parents(".block_row").remove();
	});

	$(".handleTypeAntrian").select2({
		theme: "bootstrap-5",
		placeholder: "Pilih Type Antrian"
	});

	$(".handleTypeAntrian-printer").select2({
		theme: "bootstrap-5",
		placeholder: "Pilih Type Antrian"
	});

	$(".handleJenisPoli").select2({
		theme: "bootstrap-5",
		placeholder: "Pilih Jenis Poli"
	});

	$(".handleMonitor").select2({
		theme: "bootstrap-5",
		placeholder: "Pilih Kategori Antrian"
	});

	$(".handleJenisPoli").each(function (e) {
		let selected = $(this).data('selected');
		let parseSelected = (selected.length > 0) ? JSON.parse(selected.replaceAll("'", '"')) : [];
		$(this).val(parseSelected).trigger('change');
	})

	$(".handleTypeAntrian").each(function (e) {
		let selected = $(this).data('selected');
		let parseSelected = (selected.length > 0) ? JSON.parse(selected.replaceAll("'", '"')) : [];
		$(this).val(parseSelected).trigger('change');
	})

	$(".handleTypeAntrian-printer").each(function (e) {
		let selected = $(this).data('selected');
		let parseSelected = (selected.length > 0) ? JSON.parse(selected.replaceAll("'", '"')) : [];
		$(this).val(parseSelected).trigger('change');
	})
	
	$(".handleMonitor").each(function (e) {
		let selected = $(this).data('selected');
		let parseSelected = (selected.length > 0) ? JSON.parse(selected.replaceAll("'", '"')) : [];
		$(this).val(parseSelected).trigger('change');
	})

	$(document).on("submit", "#saveSetting", function (e) {
		e.preventDefault();
		var formData = new FormData(this);
		formData.append('type', 'save');

		const namaPoliArray = formData.getAll('nama_poli[]');
		formData.set('nama_poli[]', JSON.stringify(namaPoliArray));

		$.ajax({
			type: 'POST',
			url: 'pages/setting/action.php',
			dataType: 'JSON',
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function (result) {
				if (result.success === true) {
					alert("Setting berhasil disimpan")
					// window.location.reload();
				} else {
					alert(result.message);
				}
			},
		});
	});

	$(document).on("click", "#reset_antrian", function (e) {
		let message = "Apakah anda yakin ingin mereset antrian?";
		if (confirm(message) == true) {
			$.ajax({
				url: 'pages/setting/action.php',
				method: 'POST',
				data: {
					type: 'reset_antrian'
				},
				async: false,
				cache: false,
				dataType: 'json',
				success: function (result) {
					alert(result.message);
				}
			});
		}
	});

	$(document).on("click", "#logout", function (e) {
		$.ajax({
			type: 'POST',
			url: 'pages/setting/action.php',
			data: {
				type: 'logout'
			},
			dataType: 'json',
			success: function (result) {
				if (result.success === true) {
					window.location.reload();
				} else {
					alert("Eits ada masalah nih, hubungi IT Support yaa!");
				}
			},
		});
	});
</script>

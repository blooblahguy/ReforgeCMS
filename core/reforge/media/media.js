var progressBar
var preview
var gallery
var offset

function rf_media_initialize() {
	let dropper = document.getElementById('dropper')

	if (! dropper) {return; }

	progressBar = document.getElementById('dropper_progress')
	preview = document.getElementById('preview')
	gallery = document.getElementById('rf_gallery')
	offset = gallery.getBoundingClientRect();
	gallery.style.cssText = "max-height: calc(100vh - "+(offset.top+40)+"px);"

	console.log("rf_media loaded")

	/** Events */
	;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
		dropper.addEventListener(eventName, preventDefaults, false)
	})

	;['dragenter', 'dragover'].forEach(eventName => {
		dropper.addEventListener(eventName, highlight, false)
	})

	;['dragleave', 'drop'].forEach(eventName => {
		dropper.addEventListener(eventName, unhighlight, false)
	})

	dropper.addEventListener('drop', handleDrop, false)
}

/** Functions */
function preventDefaults(e) {
	e.preventDefault();
	e.stopPropagation();
}

function initializeProgress(numFiles) {
	progressBar.value = 0
	uploadProgress = []

	for(let i = numFiles; i > 0; i--) {
		uploadProgress.push(0)
	}
}

function updateProgress(fileNumber, percent) {
	uploadProgress[fileNumber] = percent
	let total = uploadProgress.reduce((tot, curr) => tot + curr, 0) / uploadProgress.length
	progressBar.value = total

	if (total == 100) {
		preview.innerHTML ='<div class="message-success">Successfully uploaded all images</div>';
		window.setTimeout(function() {
			$(preview).remove()
			// location.reload();
		}, 1000)
	}
}

function handleDrop(e) {
	let dt = e.dataTransfer
	let files = dt.files

	handleFiles(files)
}
function handleFiles(files) {
	files = [...files]
	initializeProgress(files.length) // <- Add this line
	files.forEach(uploadFile)
	files.forEach(previewFile)
}

function uploadFile(file, i) { // <- Add `i` parameter
	var url = "/admin/rf_media/upload"
	var xhr = new XMLHttpRequest()
	var formData = new FormData()
	xhr.open('POST', url, true)

	// Add following event listener
	xhr.upload.addEventListener("progress", function(e) {
		updateProgress(i, (e.loaded * 100.0 / e.total) || 100)
	})

	xhr.addEventListener('readystatechange', function(e) {
		if (xhr.readyState == 4 && xhr.status == 200) {
			// Done. Inform the user
			console.log(xhr.response)
		}
		else if (xhr.readyState == 4 && xhr.status != 200) {
			// Error. Inform the user
		}
	})

	formData.append('file', file)
	xhr.send(formData)
}


function previewFile(file) {
	let reader = new FileReader()
	reader.readAsDataURL(file)
	reader.onloadend = function() {
		let img = document.createElement('img')
		img.src = reader.result
		preview.appendChild(img)
	}
}

function highlight(e) {
	dropper.classList.add('hover')
}

function unhighlight(e) {
	dropper.classList.remove('hover')
}


rf_media_initialize()

function preview_image(key, src) {
	var previewer = $(".preview_"+key)
	console.log(".preview_"+key)
	previewer.attr("style", "");
	previewer.src = src
}


$("body").on("click", ".rf_media_browse", function(e) {
	preventDefaults(e);
	modal_create();

	var key = $(this).data("key")
	var value = $("[name='"+key+"']").val();

	var title = $(".modal_title")
	var modal = $(".modal_body")
	title.html("Browse Media")

	$.get($(this).attr("href"), {"current": value}, function(response) {
		modal.html(response)

		var lazyLoadInstance = new LazyLoad({
			elements_selector: ".lazy"
		});

		modal.on("click", ".file_card a", function(e) {
			preventDefaults(e);
			var id = $(this).data("id")
			var img = $(this).siblings("img").first().attr("src")

			var hidden = $("[name='"+key+"']")
			hidden.val(id);

			var preview = hidden.siblings(".preview").first().children("img")
			preview.show();
			preview.attr("src", img);

			// preview_image(key, img)

			modal_destroy();
		})
	})
});
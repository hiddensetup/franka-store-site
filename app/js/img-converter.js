const getRandomHash = (length) => Array.from({
    length
}, () => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789' [Math.floor(Math.random() * 62)]).join('');

const bytesToMegabytes = (bytes) => (bytes / (1024 * 1024)).toFixed(2);

const refs = {
    welcomeMessage: document.getElementById('welcome-message'),
    imageTable: document.getElementById('imageTable'),
    imagePreviews: document.querySelector('.previews'),
    fileSelector: document.querySelector('#fileSelector'),
    progressBar: document.getElementById('progress-bar'),
    conversionCounter: document.getElementById('conversion-counter'),
    selectAllCheckbox: document.getElementById('selectAll'),
};

refs.imageTableBody = document.getElementById('imageTableBody');

let convertedImages = 0;
let totalDroppedFiles = 0;

const addImageBox = (container) => {
    const imageBox = document.createElement('div');
    imageBox.className = 'col';
    container.appendChild(imageBox);
    return imageBox;
};

const removeLoader = (imageBox) => {
    const loader = imageBox.querySelector('.spinner-border');
    if (loader) loader.remove();
};

const hideWelcomeMessage = () => refs.welcomeMessage.style.display = 'none';
const showImageTable = () => refs.imageTable.style.display = 'table';

const processFile = async (file) => {
    if (!file) return;
    hideWelcomeMessage();
    showImageTable();

    const randomHash = getRandomHash(16);
    const currentDate = new Date().toLocaleDateString('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: '2-digit'
    }).replace(/\//g, '-');

    const spinner = document.createElement('div');
    spinner.className = 'spinner-border text-primary';
    spinner.setAttribute('role', 'status');

    const imageBox = addImageBox(refs.imagePreviews);
    imageBox.appendChild(spinner);

    try {
        const rawImage = await new Promise((resolve, reject) => {
            setTimeout(() => {
                spinner.remove();
                const img = new Image();
                img.onload = () => resolve(img);
                img.onerror = reject;
                img.src = URL.createObjectURL(file);
            }, 5000);
        });

        const canvas = Object.assign(document.createElement('canvas'), {
            width: rawImage.width,
            height: rawImage.height
        });
        const ctx = canvas.getContext('2d');
        ctx.drawImage(rawImage, 0, 0);

        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/webp'));

        removeLoader(imageBox);

        const imageURL = URL.createObjectURL(blob);
        const originalSize = bytesToMegabytes(file.size);
        const convertedSize = bytesToMegabytes(blob.size);

        const tableRow = document.createElement('tr');
        tableRow.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <input style="min-width:20px; min-height:20px" class="form-check-input image-checkbox m-0" type="checkbox" value="${randomHash}-${currentDate}" data-image-url="${imageURL}">
                    <label class="form-check-label" for="${randomHash}-${currentDate}"></label>
                    <img src="${imageURL}" class="img-thumbnail rounded-4 mx-2" alt="Converted Image" style="width: 60px;height: 60px;object-fit: cover;min-width: 20px;min-height: 20px;">
                </div>
            </td>
            <td class="d-none d-sm-table-cell">${originalSize} MB</td>
            <td class="d-none d-sm-table-cell">${convertedSize} MB</td>
            <td>
                <a href="${imageURL}" download="${randomHash}-${currentDate}.webp" class="text-decoration-none">
                    <small>${randomHash}-${currentDate}.webp</small>
                </a>
            </td>
        `;

        refs.imageTableBody.appendChild(tableRow);

        tableRow.scrollIntoView({
            behavior: 'smooth',
            block: 'end',
            inline: 'nearest'
        });

        convertedImages++;
        updateProgress();

        // Send converted image data to PHP script for storage
        const imageData = canvas.toDataURL('image/webp');
        const formData = new FormData();
        formData.append('imageData', imageData);
        formData.append('filename', `${randomHash}-${currentDate}`);

        fetch('../php/img-converter_save.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.message);
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error saving image:', error));

    } catch (error) {
        console.error('Error processing file:', error);
    }
};

const processFiles = (files) => {
    totalDroppedFiles += files.length;

    for (const file of files) {
        processFile(file);
    }

    if (totalDroppedFiles >= 2) {
        removeDNone(refs.selectAllCheckbox.parentElement);
    }
};

const updateProgress = () => {
    const progress = (convertedImages / totalDroppedFiles) * 100;
    refs.progressBar.style.width = `${progress}%`;
    refs.progressBar.setAttribute('aria-valuenow', progress);
    refs.conversionCounter.innerText = totalDroppedFiles.toString();
};

const fileSelectorChanged = () => {
    processFiles(refs.fileSelector.files);
    refs.fileSelector.value = '';
};

refs.fileSelector.addEventListener('change', fileSelectorChanged);

const dragenter = (e) => {
    e.stopPropagation();
    e.preventDefault();
};
const dragover = (e) => {
    e.stopPropagation();
    e.preventDefault();
};
const drop = (callback, e) => {
    e.stopPropagation();
    e.preventDefault();
    callback(e.dataTransfer.files);
};

const setDragDrop = (area, callback) => {
    area.addEventListener('dragenter', dragenter, false);
    area.addEventListener('dragover', dragover, false);
    area.addEventListener('drop', (e) => drop(callback, e), false);
};

setDragDrop(document.documentElement, processFiles);

refs.selectAllCheckbox.addEventListener('change', () => {
    const checkboxes = document.querySelectorAll('.image-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = refs.selectAllCheckbox.checked);
});

const downloadSelected = async () => {
    const selectedFiles = Array.from(document.querySelectorAll('.image-checkbox:checked')).map(checkbox => ({
        value: checkbox.value,
        imageURL: checkbox.getAttribute('data-image-url')
    }));

    if (selectedFiles.length === 0) {
        alert('Please select at least one file for download.');
        return;
    }

    const zip = new JSZip();

    for (const file of selectedFiles) {
        const blob = await fetch(file.imageURL).then(response => response.blob());
        zip.file(`${file.value}.webp`, blob);
    }

    zip.generateAsync({
        type: 'blob'
    }).then((content) => {
        const downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(content);
        downloadLink.download = 'selected_images.zip';
        downloadLink.click();
    });
};

const removeDNone = (element) => {
    element.classList.remove('d-none');
    element.classList.add('slideUp');
};

// your_separate_js_file.js

function generateQRCode() {
    // Display loader and hide QR code
    document.getElementById('qrcode').innerHTML = ''; // Clear existing content
    document.getElementById('qrcode').style.display = 'none';

    // Get the URL from the input field
    const url = document.getElementById('urlInput').value;

    // Make a GET request to the server
    fetch(url)
        .then(response => response.arrayBuffer())
        .then(data => {
            // Convert the binary data to a Blob
            const blob = new Blob([data], { type: 'image/png' });

            // Create a data URL from the Blob
            const imageUrl = URL.createObjectURL(blob);

            // Set the data URL as the source of the image element in the modal
            const imgElement = document.getElementById('qrcode');
            imgElement.appendChild(createImageElement(imageUrl));

            // Display QR code
            document.getElementById('qrcode').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorAlert('Error rendering QR code. Please check the console for details.');
        });
}

function createImageElement(src) {
    const imgElement = document.createElement('img');
    imgElement.src = src;
    imgElement.classList.add('img-fluid');
    return imgElement;
}

function showErrorAlert(message) {
    const alertDiv = document.createElement('div');
    alertDiv.classList.add('alert', 'alert-danger', 'position-fixed', 'bottom-0', 'end-0', 'm-4', 'fade', 'show');
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
    document.body.appendChild(alertDiv);

    // Remove the alert after 5 seconds (5000 milliseconds)
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

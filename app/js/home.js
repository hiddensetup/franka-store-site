// Declare contentJSON in the global scope
let contentJSON;

async function fetchContent() {
    try {
        const response = await fetch('../data/home-data/content.json');
        const data = await response.json();

        // Update content with fetched data
        contentJSON = data;

        updateContent(contentJSON);
    } catch (error) {
        console.error('Error fetching content:', error);
    }
}

// Function to replace HTML content with values from JSON
function updateContent(contentJSON) {
    for (const section in contentJSON) {
        if (contentJSON.hasOwnProperty(section)) {
            const sectionData = contentJSON[section];
            for (const key in sectionData) {
                if (sectionData.hasOwnProperty(key)) {
                    const element = document.getElementById(key);
                    if (element) {
                        if (key === 'metaKeywords' || key === 'metaDescription') {
                            // For meta tags, use setAttribute to set the content
                            element.setAttribute('content', sectionData[key] || '');
                        } else if (key === 'favicon') {
                            // For favicon, update the href attribute
                            element.setAttribute('href', sectionData[key] || '');
                        } else if (key === 'heroDescription') {
                            // For heroDescription, join the array elements and use innerHTML
                            const heroDescriptionHTML = sectionData.heroDescription.join('');
                            element.innerHTML = heroDescriptionHTML;
                        } else {
                            // For other elements, use innerHTML
                            element.innerHTML = sectionData[key];
                        }
                    }
                }
            }
        }
    }

    // Update dropdown options
    const dropdown = document.getElementById('messageDropdown');
    dropdown.innerHTML = contentJSON.form.messageOptions.map(option => `<option value="${option}">${option}</option>`).join('');
}

function generateWhatsAppMessage() {
    console.log('generateWhatsAppMessage function called'); // Check if this log appears

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const selectedMessageKey = document.getElementById('messageDropdown').value;
    const phoneNumber = contentJSON.footer.phoneNumber;
    const selectedMessage = selectedMessageKey;

    const whatsappMessage = `New Inquiry\nName: ${name}\nEmail: ${email}\nMessage: ${selectedMessage}`;

    window.open(`https://wa.me/${phoneNumber.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(whatsappMessage)}`, '_blank');

    return false;
}

window.onload = function () {
    fetchContent();
};


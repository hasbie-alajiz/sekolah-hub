import "./libs/trix";


import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Handle Trix Editor file uploads globally
document.addEventListener('trix-attachment-add', function(event) {
    const attachment = event.attachment;
    if (attachment.file) {
        const formData = new FormData();
        formData.append('file', attachment.file);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/admin/media/richtext-upload', true);
        if (csrfToken) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        }

        xhr.upload.onprogress = function(event) {
            const progress = (event.loaded / event.total) * 100;
            attachment.setUploadProgress(progress);
        };

        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    attachment.setAttributes({
                        url: response.image_url,
                        href: response.image_url,
                        mediaId: response.media_id
                    });
                } catch (e) {
                    console.error('Failed to parse upload response', e);
                }
            } else {
                console.error('Upload failed with status ' + xhr.status);
            }
        };

        xhr.send(formData);
    }
});

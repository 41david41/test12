document.addEventListener('DOMContentLoaded', function () {
    function createPreview(containerId, file, isImage) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        const preview = document.createElement('div');
        preview.className = 'preview-item';

        const removeBtn = document.createElement('button');
        removeBtn.innerHTML = '×';
        removeBtn.className = 'remove-btn';
        removeBtn.onclick = () => {
            container.innerHTML = '';
            document.getElementById(containerId.replace("Preview", "Input")).value = '';
        };

        if (isImage) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'thumbnail';
            preview.appendChild(img);
        } else {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(file);
            link.textContent = file.name;
            link.target = '_blank';
            preview.appendChild(link);
        }

        preview.appendChild(removeBtn);
        container.appendChild(preview);
    }

    const bildeInput = document.getElementById('imageUpload');
    const pdfInput = document.getElementById('pdfUpload');

    if (bildeInput) {
        bildeInput.addEventListener('change', function (e) {
            if (e.target.files[0]) createPreview('bildePreview', e.target.files[0], true);
        });
    }

    if (pdfInput) {
        pdfInput.addEventListener('change', function (e) {
            if (e.target.files[0]) createPreview('pdfPreview', e.target.files[0], false);
        });
    }
});
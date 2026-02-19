import Quill from 'quill';

// Initialize Quill editor
export function initializeQuill(elementId) {
    const quill = new Quill(`#${elementId}`, {
        theme: 'snow',
        placeholder: 'Jelaskan detail aset secara lengkap...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Sync Quill content with hidden textarea on form submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const descriptionField = document.querySelector(`#${elementId}_content`);
            if (descriptionField) {
                descriptionField.value = quill.root.innerHTML;
            }
        });
    }

    return quill;
}

// Get Quill instance
export function getQuillInstance(elementId) {
    return Quill.find(`#${elementId}`);
}

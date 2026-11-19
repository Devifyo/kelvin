/* ─────────────────────────────────────────────────────────────────────────
   IMPORT FROM WORD (.docx) into a TinyMCE editor
   Used by: Resource Library → Full Text tab, Blog Posts → Main Body.

   Why this exists: a browser paste from Word carries the text but not the
   pictures (Word only puts references to temporary files on the clipboard,
   which a web page is not allowed to read). Uploading the .docx itself gets
   everything. The conversion runs on the server (App\Support\DocxToHtml),
   which also stores the pictures and returns ready-to-edit HTML.

   Markup contract (see partials/word-import-button.blade.php):
     <div data-word-import-scope>
        <button data-word-import data-import-url="…" data-csrf="…">…</button>
        <textarea>…</textarea>        ← the TinyMCE editor of this field
     </div>

   The editor's own 'change' listener pushes the new content to Livewire,
   so this script does not need to know the field name.
   ───────────────────────────────────────────────────────────────────────── */
(function () {
    'use strict';

    const MAX_DOCX_MB = 100;
    const COPPER = '#b5722a';
    const RED = '#ef4444';

    function findEditor(scope) {
        const textarea = scope.querySelector('textarea');
        if (!textarea || typeof tinymce === 'undefined') return null;
        return tinymce.get().find((ed) => ed.getElement() === textarea) || null;
    }

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]);
    }

    /** POST with upload progress (fetch cannot report it). */
    function upload(url, csrf, file, onProgress) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', url);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.upload.onprogress = (e) => { if (e.lengthComputable) onProgress(e.loaded / e.total); };
            xhr.upload.onload = () => onProgress(1);
            xhr.onload = () => {
                let json = null;
                try { json = JSON.parse(xhr.responseText); } catch (e) { /* handled below */ }

                if (xhr.status >= 200 && xhr.status < 300 && json && typeof json.html === 'string') {
                    resolve(json);
                    return;
                }

                let msg = (json && json.message) || '';
                if (json && json.errors && json.errors.file) msg = json.errors.file[0];
                if (xhr.status === 419) msg = 'Your session has expired. Reload the page and try again.';
                if (xhr.status === 413) msg = 'This file is too large for the server to accept.';
                reject(new Error(msg || ('The import failed (HTTP ' + xhr.status + ').')));
            };
            xhr.onerror = () => reject(new Error('The file could not be sent. Check your connection and try again.'));

            const form = new FormData();
            form.append('file', file, file.name);
            xhr.send(form);
        });
    }

    async function importFile(file, button) {
        const scope = button.closest('[data-word-import-scope]');
        const editor = scope && findEditor(scope);

        if (!editor) {
            Swal.fire({ icon: 'error', title: 'Editor not ready', text: 'Please wait for the editor to finish loading, then try again.', confirmButtonColor: COPPER });
            return;
        }

        if (!/\.docx$/i.test(file.name)) {
            Swal.fire({ icon: 'warning', title: 'Word .docx files only',
                html: 'Older <strong>.doc</strong> files cannot be imported. Open the file in Word and use <em>Save As → Word Document (.docx)</em>.',
                confirmButtonColor: COPPER });
            return;
        }

        if (file.size > MAX_DOCX_MB * 1024 * 1024) {
            Swal.fire({ icon: 'warning', title: 'File too large', text: 'Word files up to ' + MAX_DOCX_MB + ' MB can be imported.', confirmButtonColor: COPPER });
            return;
        }

        if (editor.getContent({ format: 'text' }).trim() !== '' || editor.getContent().indexOf('<img') !== -1) {
            const answer = await Swal.fire({
                icon: 'question',
                title: 'Replace the current text?',
                text: 'Importing "' + file.name + '" will replace everything currently in this editor.',
                showCancelButton: true,
                confirmButtonText: 'Yes, replace it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: COPPER,
            });
            if (!answer.isConfirmed) return;
        }

        Swal.fire({
            title: 'Importing from Word',
            text: 'Uploading the document…',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading(),
        });

        const status = (text) => {
            const el = Swal.getHtmlContainer && Swal.getHtmlContainer();
            if (el) el.textContent = text;
        };

        try {
            const result = await upload(button.dataset.importUrl, button.dataset.csrf, file, (fraction) => {
                status(fraction < 1
                    ? 'Uploading the document… ' + Math.round(fraction * 100) + '%'
                    : 'Converting the text and pictures…');
            });

            editor.undoManager.transact(() => editor.setContent(result.html));
            // The editor's own listener syncs the content to Livewire.
            (editor.dispatch || editor.fire).call(editor, 'change');

            const pictureIssues = (result.warnings || []).filter((w) => /^Picture/.test(w));
            const otherNotes = (result.warnings || []).filter((w) => !/^Picture/.test(w));

            let summary = '<p style="margin:0 0 .5rem">The document was imported'
                + (result.images ? ' with <strong>' + result.images + '</strong> picture' + (result.images === 1 ? '' : 's') : '') + '.</p>';

            if (pictureIssues.length) {
                summary += '<p style="margin:.75rem 0 .35rem;color:' + RED + ';font-weight:700">'
                    + pictureIssues.length + ' picture' + (pictureIssues.length === 1 ? ' needs' : 's need') + ' your attention:</p>'
                    + '<ul style="text-align:left;margin:0;padding-left:1.2rem">'
                    + pictureIssues.map((w) => '<li>' + escapeHtml(w) + '</li>').join('')
                    + '</ul><p style="margin:.6rem 0 0;font-size:.85rem">Each one is marked with ⚠ in the editor. Save it from Word as PNG or JPEG and insert it there.</p>';
            }

            if (otherNotes.length) {
                summary += '<ul style="text-align:left;margin:.75rem 0 0;padding-left:1.2rem;font-size:.85rem">'
                    + otherNotes.map((w) => '<li>' + escapeHtml(w) + '</li>').join('') + '</ul>';
            }

            summary += '<p style="margin:.75rem 0 0;font-size:.85rem">Review the text, then click <strong>Save</strong> to publish it.</p>';

            Swal.fire({ icon: pictureIssues.length ? 'warning' : 'success', title: 'Imported from Word', html: summary, confirmButtonColor: COPPER });
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Import failed', text: err.message, confirmButtonColor: COPPER });
        }
    }

    document.addEventListener('click', (e) => {
        const button = e.target.closest('[data-word-import]');
        if (!button) return;
        e.preventDefault();

        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        input.addEventListener('change', () => {
            if (input.files && input.files[0]) importFile(input.files[0], button);
        });
        input.click();
    });
})();

{{-- "Import from Word" for a TinyMCE field. Place inside an element marked
     data-word-import-scope that also contains the editor's <textarea>.
     Behaviour lives in public/js/admin/word-import.js (loaded by the admin layout). --}}
<div class="wim-bar">
    <button type="button" class="wim-btn" data-word-import
            data-import-url="{{ route('admin.word-import') }}"
            data-csrf="{{ csrf_token() }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
        Import from Word
    </button>
    <span class="wim-hint">Choose a .docx file to bring in its text, headings, lists, tables and pictures in one step. Review it, then save.</span>
</div>

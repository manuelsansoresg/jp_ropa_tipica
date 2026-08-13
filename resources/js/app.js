import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('singleImageUpload', (currentImage = null) => ({
    preview: currentImage || null,
    hasOriginal: Boolean(currentImage),
    filename: '',
    remove: false,
    choose(event) {
        const [file] = event.target.files;

        if (!file) return;

        if (this.preview?.startsWith('blob:')) URL.revokeObjectURL(this.preview);
        this.preview = URL.createObjectURL(file);
        this.filename = file.name;
        this.remove = false;
    },
    clear() {
        if (this.preview?.startsWith('blob:')) URL.revokeObjectURL(this.preview);
        this.preview = null;
        this.filename = '';
        this.remove = this.hasOriginal;
        this.$refs.file.value = '';
    },
}));

Alpine.data('multipleImageUpload', () => ({
    previews: [],
    choose(event) {
        this.clearPreviews();
        this.previews = Array.from(event.target.files).map((file) => ({
            name: file.name,
            url: URL.createObjectURL(file),
        }));
    },
    clear() {
        this.clearPreviews();
        this.previews = [];
        this.$refs.files.value = '';
    },
    clearPreviews() {
        this.previews.forEach((image) => URL.revokeObjectURL(image.url));
    },
}));

Alpine.data('bulkProductSelection', (pageIds, total) => ({
    selected: [],
    pageIds: pageIds.map(String),
    total,
    selectAllCatalog: false,
    showConfirmation: false,
    togglePage(checked) {
        this.selected = checked ? [...this.pageIds] : [];
    },
    requestDelete() {
        const count = this.selectAllCatalog ? this.total : this.selected.length;

        if (!count) {
            window.alert('Selecciona al menos un producto.');
            return;
        }

        this.showConfirmation = true;
    },
    confirmationMessage() {
        return this.selectAllCatalog
            ? `Se eliminarán definitivamente los ${this.total} productos del catálogo.`
            : `Se eliminarán definitivamente ${this.selected.length} producto(s) seleccionado(s).`;
    },
    submitConfirmed(form) {
        this.showConfirmation = false;
        form.submit();
    },
}));

Alpine.start();

const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.12, rootMargin: '0px 0px -40px' });

document.querySelectorAll('.reveal, .image-reveal').forEach((element) => revealObserver.observe(element));

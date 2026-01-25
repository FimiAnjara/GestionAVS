<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-trash text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="fs-5 mb-1">Êtes-vous sûr de vouloir supprimer cet élément ?</p>
                <p class="text-muted" id="deleteItemName"></p>
                <div class="alert alert-warning py-2 small mb-0">
                    <i class="bi bi-info-circle me-2"></i>Cette action est irréversible.
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteConfirmForm" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 shadow-sm">
                        Confirmer la suppression
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

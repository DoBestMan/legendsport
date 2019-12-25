//MODAL-DELETE··········································································
    Vue.component('modal-delete', {
        props: [
            'indexRowId',
            'textDescription',
        ],

        template: `
            <div :id="'modalDelete' + (indexRowId ? indexRowId : '')"
                class="modal fade"
                tabindex="-1"
                role="dialog"
                >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Eliminar</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body text-left">
                            <p>
                                Confirme la eliminación de:
                                <strong>{{ textDescription }}</strong>
                            </p>
                        </div>

                        <div class="modal-footer">
                            <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >Cancelar</button>

                            <button type="submit"
                                :form="'formDelete' + (indexRowId ? indexRowId : '')"
                                class="btn btn-danger"
                            >Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
        `,
    });

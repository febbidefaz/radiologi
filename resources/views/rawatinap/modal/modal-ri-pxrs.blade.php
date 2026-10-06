{{-- ====================================================== --}}
{{-- MODAL UPDATE PXRS --}}
{{-- ====================================================== --}}

<div class="modal fade" id="modalUpdatePxRS" tabindex="-1">

    <div class="modal-dialog modal-md">

        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header modal-modern">

                <h5 class="modal-title font-weight-bold">

                    <i class="fas fa-user-edit mr-2"></i>
                    Ubah PxRS

                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            <form id="formUpdatePxRS" action="{{ route('rad.update.pxrs', $pasien->ID) }}" method="POST">

                @csrf

                <div class="modal-body">

                    <input type="hidden" id="pxrsID" value="{{ $pasien->ID }}">

                    <div class="form-group mb-0">

                        <label>
                            Pilih PxRS
                            <span class="text-danger">*</span>
                        </label>

                        <select name="uPx" id="uPx" class="form-control" required>

                            <option value="">
                                -- Pilih PxRS --
                            </option>

                            @foreach ($upxList ?? [] as $u)
                                <option value="{{ $u->ID }}"
                                    {{ (string) ($pasien->uPx ?? '') === (string) $u->ID ? 'selected' : '' }}>

                                    {{ $u->PxRS }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">

                        <i class="fas fa-times mr-1"></i>
                        Batal

                    </button>

                    <button type="submit" class="btn btn-success btn-sm" id="btnSimpanPxRS">

                        <i class="fas fa-save mr-1"></i>
                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

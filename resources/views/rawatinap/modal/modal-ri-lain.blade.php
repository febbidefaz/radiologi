  {{-- ====================================================== --}}
  {{-- MODAL LAIN-LAIN --}}
  {{-- ====================================================== --}}

  <div class="modal fade" id="modalInsertLain" tabindex="-1">

      <div class="modal-dialog modal-md">

          <div class="modal-content border-0 shadow-lg">


              <div class="modal-header modal-modern">

                  <h5 class="modal-title font-weight-bold">

                      <i class="fas fa-list-alt mr-2"></i>
                      Biaya Lain-lain

                  </h5>


                  <button type="button" class="close text-white" data-dismiss="modal">

                      <span>&times;</span>

                  </button>

              </div>


              <div class="modal-body">

                  <input type="hidden" id="lainID">


                  <div class="form-group">

                      <label>
                          Tanggal
                      </label>

                      <input type="date" id="lainTgl" class="form-control" value="{{ date('Y-m-d') }}">

                  </div>


                  <div class="form-group">

                      <label>
                          Nama Biaya
                      </label>

                      <input type="text" id="lainNama" class="form-control" placeholder="Nama biaya">

                  </div>


                  <div class="form-group">

                      <label>
                          Tarif
                      </label>

                      <input type="number" id="lainBiaya" class="form-control" value="0">

                  </div>


                  <div class="form-group">

                      <label>
                          Potongan (%)
                      </label>

                      <input type="number" step="0.01" id="lainPot" class="form-control" value="0">

                  </div>

              </div>


              <div class="modal-footer">

                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">

                      Tutup

                  </button>


                  <button type="button" id="btnSimpanLain" class="btn btn-success btn-sm" onclick="simpanInsertLain()">

                      <i class="fas fa-save mr-1"></i>
                      Simpan

                  </button>


                  <button type="button" id="btnHapusLain" class="btn btn-danger btn-sm" onclick="hapusLain()"
                      style="display:none;">

                      <i class="fas fa-trash mr-1"></i>
                      Hapus

                  </button>

              </div>

          </div>

      </div>

  </div>

  {{-- JS SEP --}}
  <script>
      function showSepDetail(noSep) {
          if (!noSep || noSep === '-') return;

          $('#modalSep').modal('show');

          $('#sepContent').html(`
              <div class="text-center text-muted py-4">
                  <i class="fas fa-spinner fa-spin"></i> Memuat data SEP...
              </div>
          `);

          $.getJSON("{{ route('rawatinap.sep.detail') }}?nosep=" + encodeURIComponent(noSep), function(res) {

              if (!res || res.metaData?.code != 200) {
                  $('#sepContent').html(`
                      <div class="alert alert-danger mb-0">
                          Data SEP tidak ditemukan.
                      </div>
                  `);
                  return;
              }

              let d = res.response || {};
              let p = d.peserta || {};
              let dpjp = d.dpjp || {};
              let tujuan = d.tujuanKunj || {};

              $('#sepContent').html(`
          <div class="sep-print-preview">

          <div class="sep-header">

              <div class="sep-logo">
                  <img src="{{ asset('img/BPJS_Kesehatan_logo.svg') }}" alt="BPJS">
              </div>

              <div class="sep-title">

                  <div>
                      SURAT ELEGIBILITAS PESERTA
                  </div>

                  <small>
                      RS AISYIYAH BOJONEGORO
                  </small>

              </div>

          </div>

          <div class="row mt-3">

              <div class="col-md-6">

                  <table class="table table-sm table-borderless sep-table">

                      <tr>
                          <td>No. SEP</td>
                          <td>:</td>
                          <td><strong>${d.noSep ?? '-'}</strong></td>
                      </tr>

                      <tr>
                          <td>Tgl SEP</td>
                          <td>:</td>
                          <td>${d.tglSep ?? '-'}</td>
                      </tr>

                      <tr>
                          <td>No Kartu</td>
                          <td>:</td>
                          <td>${p.noKartu ?? '-'} (${p.noMr ?? '-'})</td>
                      </tr>

                      <tr>
                          <td>Nama Peserta</td>
                          <td>:</td>
                          <td>${p.nama ?? '-'}</td>
                      </tr>

                      <tr>
                          <td>Tgl. Lahir</td>
                          <td>:</td>
                          <td>
                              ${p.tglLahir ?? '-'}
                              &nbsp;&nbsp;
                              Kelamin : ${p.kelamin ?? '-'}
                          </td>
                      </tr>

                      <tr>
                          <td>No. Telepon</td>
                          <td>:</td>
                          <td>-</td>
                      </tr>

                      <tr>
                          <td>Sub/Spesialis</td>
                          <td>:</td>
                          <td>${d.poli ?? '-'}</td>
                      </tr>

                      <tr>
                          <td>Dokter</td>
                          <td>:</td>
                          <td>${dpjp.nmDPJP ?? '-'}</td>
                      </tr>

                      <tr>
                          <td>Faskes Perujuk</td>
                          <td>:</td>
                          <td>RS AISYIYAH BOJONEGORO</td>
                      </tr>

                      <tr>
                          <td>Diagnosa Awal</td>
                          <td>:</td>
                          <td>${d.diagnosa ?? '-'}</td>
                      </tr>

                  </table>

              </div>


              <div class="col-md-6">

                  <table class="table table-sm table-borderless sep-table">

                      <tr>
                          <td>Peserta</td>
                          <td>:</td>
                          <td>${p.jnsPeserta ?? '-'}</td>
                      </tr>

                      <tr>
                          <td>Jns. Rawat</td>
                          <td>:</td>
                          <td>${d.jnsPelayanan ?? '-'}</td>
                      </tr>

                      <tr>
                          <td>Jns Kunjungan</td>
                          <td>:</td>
                          <td>${tujuan.nama ?? '-'}</td>
                      </tr>

                      <tr>
                          <td>Poli Perujuk</td>
                          <td>:</td>
                          <td>${d.poli ?? '-'}</td>
                      </tr>

                      <tr>
                          <td>Kls. Hak</td>
                          <td>:</td>
                          <td>${p.hakKelas ?? '-'}</td>
                      </tr>

                      <tr>
                          <td>Kls. Rawat</td>
                          <td>:</td>
                          <td>${d.kelasRawat ?? '-'}</td>
                      </tr>

                      <tr>
                          <td>Penjamin</td>
                          <td>:</td>
                          <td>${d.penjamin ?? '-'}</td>
                      </tr>

                      <tr>
                          <td>Catatan</td>
                          <td>:</td>
                          <td>${d.catatan ?? '-'}</td>
                      </tr>

                  </table>

              </div>

          </div>

          <hr>

          <div class="sep-note">

              <em>*Saya menyetujui BPJS Kesehatan untuk:</em><br>

              a. membuka dan atau menggunakan informasi medis pasien untuk keperluan administrasi,
              pembayaran asuransi atau jaminan pembiayaan kesehatan.<br>

              b. memberikan akses informasi medis atau riwayat kepada dokter/tenaga medis pada
              RS AISYIYAH BOJONEGORO untuk kepentingan pemeliharaan kesehatan,
              pengobatan, penyembuhan, dan perawatan pasien.

          </div>

          </div>
              `);

          }).fail(function() {
              $('#sepContent').html(`
                  <div class="alert alert-danger mb-0">
                      Gagal mengambil data SEP dari server BPJS.
                  </div>
              `);
          });
      }
  </script>

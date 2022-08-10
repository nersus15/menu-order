<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/main/_head"); ?>
<!-- ./head -->

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <!-- navbar -->
            <?php $this->load->view("components/main/_navbar"); ?>
            <!-- ./navbar -->
            <!-- sidebar -->
            <?php $this->load->view("components/main/_sidebar"); ?>
            <!-- ./sidebar -->

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header d-flex justify-content-between">
                        <h1><?= $title; ?></h1>
                        <a href="<?= base_url("outcomingitem/create") ?>" class="btn btn-primary btn-lg">Tambah Barang Keluar</a>
                    </div>
                    <!-- alert flashdata -->
                    <?php
                    $flash = $this->session->flashdata('message');
                    if (!empty($flash)) {
                        echo '<div class="alert alert-' . $flash['type'] . '">' . $flash['message'] . '</div>';
                        unset($_SESSION['message']);
                    }
                    ?>
                    <!-- end alert flashdata -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <h3>Filter</h3>
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <?php if ($jenis == 'transaksi') : ?>
                                            <div class="col-sm-12 col-md-6 form-group">
                                                <label for="">Jenis Transaksi</label>
                                                <select name="" id="jenis-transaksi" class="form-control">
                                                    <option value="semua" selected>Semua Transaksi</option>
                                                    <option value="keluar">Transaksi Keluar</option>
                                                    <option value="masuk">Transaksi Masuk</option>
                                                </select>
                                            </div>
                                        <?php endif ?>
                                        <?php if (is_login('admin')) : ?>
                                            <div class="col-sm-12 col-md-6 form-group">
                                                <label for="">Gudang</label>
                                                <select name="" id="sgudang" class="form-control">
                                                    <option value="semua" selected>Semua Gudang</option>
                                                    <?php foreach($listGudang as $gudang):?>
                                                        <?php
                                                            $namaGudang = strpos(strtolower($gudang['nama']), 'gudang') === false ? 'Gudang ' . $gudang['nama'] : $gudang['nama'];
                                                            if ($gudang['level_wilayah_gudang'] == '1')
                                                                $namaGudang .= ' - Prov. ' . $gudang['wilayah_gudang'];
                                                            elseif ($gudang['level_wilayah_gudang'] == 3)
                                                                $namaGudang .= ' - Kec. ' . $gudang['wilayah_gudang'];
                                                        ?>
                                                        <option value="<?= $gudang['id'] ?>"><?= $namaGudang ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        <?php endif ?>
                                        <div class="col-sm-12 col-md-6 form-group">
                                            <label for="">Waktu</label>
                                            <div class="row ml-4">
                                                <div class="form-group">
                                                    <input checked type="radio" name="jenis" id="jenis-semua" value="semua">
                                                    <label for="jenis-semua">Semua</label>
                                                </div>
                                                <div class="form-group ml-4">
                                                    <input type="radio" name="jenis" id="jenis-filter" value="filter">
                                                    <label for="jenis-filter">Filter</label>
                                                </div>
                                            </div>
                                            <div style="display: none;" class="form-group ml-4 mt-0" id="filter">
                                                <input type="hidden" name="filter_val" id="filter-val">
                                                <label for="">Pilih Waktu</label>
                                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-primary" id="export">
                                        Export Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- footer -->
                <?php $this->load->view("components/main/_footer"); ?>
                <!-- ./footer -->
            </div>
        </div>


        <!-- scripts -->
        <?php $this->load->view("components/main/_scripts", $this->session->flashdata('message')); ?>
        <!-- ./scripts -->
        <script>
            $(document).ready(function() {
                var jenis = "<?= $jenis ?>";
                var role = "<?= sessiondata('login', 'user_role') ?>";
                $(function() {
                    var start = moment().subtract(29, 'days');
                    var end = moment();

                    function cb(start, end) {
                        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                        $("#filter-val").val(start.format('YYYY-MM-DD') + '_' + end.format('YYYY-MM-DD'));
                    }

                    $('#reportrange').daterangepicker({
                        startDate: start,
                        endDate: end,
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        }
                    }, cb);

                    cb(start, end);

                });
                $("input[name='jenis']").change(function() {
                    var val = $(this).val();
                    if (val == 'semua') {
                        $("#filter").hide();
                    } else if (val == 'filter') {
                        $("#filter").show();
                    }
                });
                $("#export").click(function() {
                    var url = path + 'report/';
                    var gudang = role == 'admin' ? $("#sgudang").val() : 'semua';
                    if ($("input[name='jenis']:checked").val() == 'semua') {
                        if(jenis == 'transaksi'){
                            var tipe = $("#jenis-transaksi").val();
                            url += 'rtransaksi/' + gudang + '/' + tipe;
                        }else if(jenis == 'barang'){
                            url += 'rbarang/' + gudang;
                        }
                    } else {
                        var waktu = $("#filter-val").val();
                        if(jenis == 'transaksi'){
                            var tipe = $("#jenis-transaksi").val();
                            url += 'rtransaksi/' + gudang + '/' + tipe + '/' + waktu;
                        }else if(jenis == 'barang'){
                            url += 'rbarang/' + gudang + '/' + waktu;
                        }
                    }
                    window.open(url, '_blank');
                });
            });
        </script>
</body>

</html>
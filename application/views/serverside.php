<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <Link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    </Link>
    <Link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    </Link>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2>Datatables serverside</h2>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary mb-3" onclick="add()">
                            Tambah data
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="modalData" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitle">Modal title</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body form">
                                        <form action="#" id="formData">
                                            <input type="hidden" id="id" name="id" value="">
                                            <div class="form-group">
                                                <label for="firstName">Nama Depan</label>
                                                <input type="text" class="form-control" id="firstName" name="firstName"
                                                    placeholder="Masukkan nama depan">
                                            </div>
                                            <div class="form-group">
                                                <label for="lastName">Nama Belakang</label>
                                                <input type="text" class="form-control" id="lastName" name="lastName"
                                                    placeholder="Masukkan nama belakang">
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Alamat</label>
                                                <textarea type="text" class="form-control" id="address" name="address"
                                                    placeholder="Masukkan alamat"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="mobilePhoneNumber">No Hp</label>
                                                <input type="text" class="form-control" id="mobilePhoneNumber"
                                                    name="mobilePhoneNumber" placeholder="Masukkan no hp">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">tutup</button>
                                        <button type="button" id="btnSave" class="btn btn-primary"
                                            onclick="save()">simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table id="myTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Depan</th>
                                    <th>Nama Belakang</th>
                                    <th>Alamat</th>
                                    <th>No Hp</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"
        integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script>
    var saveData;
    var modal = $('#modalData');
    var tableData = $('#myTable');
    var formData = $('#formData');
    var modalTitle = $('#modalTitle');
    var btnSave = $('#btnSave');

    $(document).ready(function() {
        tableData.DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                type: "POST",
                url: "<?= base_url('serverside/getData'); ?>",
            },
            "columnDefs": [{
                "target": [-1],
                "orderable": false
            }]
        });
    });

    function reloadTable() {
        tableData.DataTable().ajax.reload();
    }

    function add() {
        saveData = 'tambah';
        formData[0].reset();
        modal.modal('show');
        modalTitle.text('Tambah data');
    }

    function save() {
        btnSave.text('Mohon tunggu');
        btnSave.attr('disabled', true);
        if (saveData == 'tambah') {
            url = "<?= base_url('serverside/add'); ?>"
        } else {
            url = "<?= base_url('serverside/update'); ?>"
        }
        $.ajax({
            type: "POST",
            url: url,
            data: formData.serialize(),
            dataType: "JSON",
            success: function(response) {
                if (response.status == 'success') {
                    modal.modal('hide');
                    reloadTable();
                }
            },
            error: function() {
                console.log('error database');
            }
        });
    }

    function byid(id, type) {
        if (type == 'edit') {
            saveData = 'edit';
            formData[0].reset();
        }
        $.ajax({
            type: "GET",
            url: "<?= base_url('serverside/byid/') ?>" + id,
            dataType: "JSON",
            success: function(response) {
                if (type == 'edit') {
                    modalTitle.text('ubah data');
                    btnSave.text('Ubah Data');
                    btnSave.attr('disabled', false);
                    $('[name="id"]').val(response.id);
                    $('[name="firstName"]').val(response.nama_depan);
                    $('[name="lastName"]').val(response.nama_belakang);
                    $('[name="address"]').val(response.alamat);
                    $('[name="mobilePhoneNumber"]').val(response.no_hp);
                    modal.modal('show');
                } else {
                    var result = confirm('apakah akan menghapus data ini?' + response.nama_depan);
                    if (result) { //tekan ok
                        deleteData(response.id);
                    }
                }
            }
        });
    }

    function deleteData(id) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('serverside/delete/')?>" + id,
            dataType: "JSON",
            success: function(response) {
                reloadTable();
            }
        });
    }
    </script>
</body>


</html>
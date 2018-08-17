<?php
require_once('../connection.php');

if(isset($_GET['transaksi'])) {
    getDataTransaksi($connect);
}

if(isset($_POST['btn_action'])) {
    switch($_POST['btn_action']) {
        case 'Add':
            saveData($connect);
            break;
        case 'Edit':
            editData($connect);
            break;
        case 'fetch_single':
            fetchSingle($connect);
            break;
        case 'fetch_single_pengerjaan':
            fetchSinglePengerjaan($_POST['id'], $_SESSION['id'], $connect);
            break;
        case 'Delete':
            deleteData($connect);
            break;
        case 'edit_pengerjaan':
            updatePengerjaan($connect);
            break;
        case 'getCode':
            getCode($connect);
            break;
    }
}

function getCode($connect) {
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $connect->prepare("SELECT MAX(kode) as code FROM tb_transaksi");
    $query->execute();
    $result = $query->fetch();
    if ($result) {
        $codeResult = substr($result['code'], 1);
        $code = (int) $codeResult;
        $code = $code + 1;
        $autoCode = "T".str_pad($code,6,"0",STR_PAD_LEFT);
        echo json_encode($autoCode);
    } else {
        echo json_encode("T000001");
    }
    
}

function saveData($connect) {
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "INSERT INTO tb_transaksi (id_customer,tgl,pengerjaan,uang_muka,total_transaksi,kode)
    VALUES (:id_customer,:tgl,:pengerjaan,:uang_muka,:total_transaksi,:kode)";
    $statement = $connect->prepare($query);
    $tgl = date_create($_POST['tgl']);
    $statement->execute(
        array(
            ':id_customer'      => $_POST['id_customer'],
            ':tgl'              => date_format($tgl, 'Y-m-d'),
            ':pengerjaan'       => $_POST['pengerjaan'],
            ':uang_muka'        => (double)$_POST['uang_muka'],
            ':total_transaksi'  =>(double) $_POST['total_transaksi'],
            ':kode'             => $_POST['kode']
        )
    );

    // get id transaksi
    $id = $connect->lastInsertId();
    $queryPengerjaan = "INSERT INTO tb_pengerjaan_transaksi (id_transaksi, id_admin)
        VALUES (:id_transaksi, :id_admin)";
    $statement = $connect->prepare($queryPengerjaan);
    $statement->execute([
        ':id_transaksi'         => $id,
        ':id_admin'             => $_POST['id_admin']
    ]);

    $result = $statement->rowCount();
    if ($result > 0) {
        echo json_encode(['errors'=>false,'msg'=>'Data berhasil ditambahkan']);
    }
}

function checkname($connect, $nama) {
    $query = "SELECT * FROM tb_transaksi WHERE nama = :nama";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':nama' => $nama
    ]);
    $result = $statement->rowCount();
    if($result > 0) {
        return true;
    }else {
        return false;
    }
}

function fetchSingle($connect) {
    $query = " SELECT tb_transaksi.*, tb_customer.nama as nama_customer,
        tb_pengerjaan_transaksi.waktu, tb_pengerjaan_transaksi.status as status_pengerjaan, tb_pengerjaan_transaksi.cacheAdditionalTime,
        tb_admin.username as nama_admin
		from tb_transaksi
        LEFT JOIN tb_customer ON tb_customer.id = tb_transaksi.id_customer
        LEFT JOIN tb_pengerjaan_transaksi ON tb_pengerjaan_transaksi.id_transaksi = tb_transaksi.id
        LEFT JOIN tb_admin ON tb_pengerjaan_transaksi.id_admin = tb_admin.id
        WHERE tb_transaksi.id = :id ";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':id' => $_POST['id']
    ]);
    $result = $statement->fetchAll();
    // $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    foreach ($result as $row) {
        $output['id'] = $row['id'];
        $output['nama_customer'] = $row['nama_customer'];
        $output['id_customer'] = $row['id_customer'];
        $output['tgl'] = $row['tgl'];
        $output['kode'] = $row['kode'];
        $output['pengerjaan'] = $row['pengerjaan'];
        $output['uang_muka'] = $row['uang_muka'];
        $output['total_transaksi'] = $row['total_transaksi'];
        $output['waktu'] = $row['waktu'];
        $output['cacheAdditionalTime'] = $row['cacheAdditionalTime'];
        $output['nama_admin'] = $row['nama_admin'];
        $output['status'] = $row['status_pengerjaan'];
    }
    echo json_encode($output);
}

function fetchSinglePengerjaan($id, $id_user, $connect) {
    $query = " SELECT tb_pengerjaan_transaksi.*
		from tb_pengerjaan_transaksi
        LEFT JOIN tb_transaksi ON tb_transaksi.id = tb_pengerjaan_transaksi.id_transaksi
        WHERE tb_pengerjaan_transaksi.id_transaksi = :id
        ";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':id' => $_POST['id']
    ]);
    $result = $statement->fetchAll();
    // $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    foreach ($result as $row) {
        $output['id'] = $row['id'];
        $output['id_transaksi'] = $row['id_transaksi'];
        $output['id_admin'] = $row['id_admin'];
        $output['waktu'] = $row['waktu'];
        $output['status'] = $row['status'];
        $output['cacheAdditionalTime'] = $row['cacheAdditionalTime'];
        $output['status'] = $row['status'];
    }
    echo json_encode($output);
}

function editData($connect) {
     // $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "
        UPDATE tb_transaksi
        set
        id_customer = :id_customer,
        tgl = :tgl,
        pengerjaan = :pengerjaan,
        uang_muka = :uang_muka,
        total_transaksi = :total_transaksi
        WHERE id = :id
    ";
    $statement = $connect->prepare($query);
    $tgl = date_create($_POST['tgl']);
    $statement->execute(
        array(
            ':id'               => $_POST['transaksi_id'],
            ':id_customer'      => $_POST['id_customer'],
            ':tgl'              => date_format($tgl, 'Y-m-d'),
            ':pengerjaan'       => $_POST['pengerjaan'],
            ':uang_muka'        => $_POST['uang_muka'],
            ':total_transaksi'  => $_POST['total_transaksi']
        )
    );
    $count = $statement->rowCount();
    $result = $statement->fetch();
    if (isset($result)) {
        echo json_encode(['errors'=>false,'msg'=>"Data telah diupdate!"]);
    }
}

function updatePengerjaan($connect) {
//     $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    Update Pengerjaan transaksi
    $query = "
        UPDATE tb_pengerjaan_transaksi
        set
        id_admin = :id_admin,
        waktu = :waktu,
        status = :status,
        cacheAdditionalTime = :cacheAdditionalTime
        WHERE id = :id
    ";
    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':id'            => $_POST['user_id'],
            ':id_admin'      => $_SESSION['id'],
            ':status'       => ($_POST['status']) ? $_POST['status'] : 0,
            ':waktu'        => $_POST['waktu'],
            ':cacheAdditionalTime'  => $_POST['cacheAdditionalTime']
        )
    );
//    Update Transaksi
    $result = $statement->fetch();
    if (isset($result)) {
        echo json_encode(['errors'=>false,'msg'=>"Data telah diupdate!"]);
    }
}

function deleteData($connect){
    if ($_POST['status'] == 'active') {
		$status = 'non-active';
	}else {
		$status = 'non-active';
	}
	$query ="
		UPDATE tb_transaksi
		set status = :status
		WHERE id = :user_id
	";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':status' 	=> $status,
			':user_id' 	=> $_POST['user_id']
		)
	);
	$count = $statement->rowCount();
	if ($count > 0) {
		echo 'Status user berubah menjadi ' . $status;
	}
}


function getDataTransaksi($connect) {
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = '';
    $output = [];
    $query .= "
        SELECT tb_transaksi.*,tb_customer.nama as nama_customer, tb_customer.nama as nama_customer,
        tb_pengerjaan_transaksi.waktu, tb_pengerjaan_transaksi.status as status_pengerjaan, 
        tb_pengerjaan_transaksi.cacheAdditionalTime,
        tb_pengerjaan_transaksi.id_admin,
        tb_admin.username as nama_admin
        from tb_transaksi
        LEFT JOIN tb_customer ON tb_transaksi.id_customer = tb_customer.id
        LEFT JOIN tb_pengerjaan_transaksi ON tb_transaksi.id = tb_pengerjaan_transaksi.id_transaksi
        LEFT JOIN tb_admin ON tb_admin.id = tb_pengerjaan_transaksi.id_admin
    ";
    if (isset($_GET["search"]["value"])) {
        $query .= 'WHERE tb_customer.nama LIKE "%'.$_GET["search"]["value"].'%" ';
        $query .= 'AND tb_transaksi.pengerjaan LIKE "%'.$_GET["search"]["value"].'%" ';
    }
    if (isset($_GET["order"])) {
        $query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
    }else {
        $query .= 'ORDER BY tb_transaksi.id DESC ';
    }
    if ($_GET["length"] != -1) {
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
    }

    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $data = [];
    $filtered_rows = $statement->rowCount();
    $start = $_REQUEST['start'];
    $idx = 0;
    foreach ($result as $row) {
        $idx++;
        $tgl = date_create($row['tgl']);
        $status_pengerjaan = '' ;
        $button = '';
        switch ($row['status_pengerjaan']) {
            case '0':
                $status_pengerjaan = 'Belum Dikerjakan';
                $button = ($row['id_admin'] == $_SESSION['id']) ? '<button type="button" name="ambil_pekerjaan" id="'.$row["id"].'" class="btn btn-warning btn-xs perkerjaan" data-status="'.$status_pengerjaan.'">Ambil Pekerjaan</button>' : '';
                break;
            case '1':
                $status_pengerjaan = 'Sedang dikerjakan';
                $button = ($row['id_admin'] == $_SESSION['id']) ? '<button type="button" name="ambil_pekerjaan" id="'.$row["id"].'" class="btn btn-info btn-xs perkerjaan" data-status="'.$status_pengerjaan.'">Lanjutkan Pekerjaan</button>' : '';
                break;
            case '2':
                $status_pengerjaan = 'Selesai';
                $button = '<button type="button" id="'.$row["id"].'" class="btn btn-success btn-xs" data-status="'.$status_pengerjaan.'">Pekerjaan Selesai</button>';
                break;
        }

        $sub_array = [];
        $sub_array[] = $idx;
        $sub_array[] = date_format($tgl,'d M Y');
        $sub_array[] = $row['nama_customer'];
        $sub_array[] = $row['kode'];
        $sub_array[] = $row['pengerjaan'];
        $sub_array[] = number_format($row['uang_muka']);
        $sub_array[] = number_format($row['total_transaksi']);
        $sub_array[] = $status_pengerjaan;
        $sub_array[] = $row['nama_admin'];
        // $sub_array[] = $row['avatar'];
        $sub_array[] = '<button type="button" name="update" id="'.$row['id'].'" class="btn btn-warning btn-xs update-user">Ubah</button>';
        $sub_array[] = $button;
        $sub_array[] = $row['id_admin'];
        $data[] = $sub_array;
    }

    $output = [
        "draw" => intval($_GET["draw"]),
        "recordsTotal" => $filtered_rows,
        "recordsFiltered"  => get_total_all_records($connect),
        "data" => $data
    ];

    echo json_encode($output);
}

function get_total_all_records($connect)
{
	$statement = $connect->prepare('SELECT * FROM tb_transaksi');
	$statement->execute();
	return $statement->rowCount();
}

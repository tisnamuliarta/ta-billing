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
        case 'Delete':
            deleteData($connect);
            break;
    }
}

function saveData($connect) {
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "INSERT INTO tb_transaksi (id_customer,tgl,pengerjaan,uang_muka,total_transaksi)
    VALUES (:id_customer,:tgl,:pengerjaan,:uang_muka,:total_transaksi)";
    $statement = $connect->prepare($query);
    $tgl = date_create($_POST['tgl']);
    $statement->execute(
        array(
            ':id_customer'      => $_POST['id_customer'],
            ':tgl'              => date_format($tgl, 'Y-m-d'),
            ':pengerjaan'       => $_POST['pengerjaan'],
            ':uang_muka'        => $_POST['uang_muka'],
            ':total_transaksi'  => $_POST['total_transaksi']
        )
    );
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
    $query = " SELECT tb_transaksi.*, tb_customer.nama as nama_customer
		from tb_transaksi
        LEFT JOIN tb_customer ON tb_customer.id = tb_transaksi.id_customer
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
        $output['pengerjaan'] = $row['pengerjaan'];
        $output['uang_muka'] = $row['uang_muka'];
        $output['total_transaksi'] = $row['total_transaksi'];
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
            ':id'               => $_POST['user_id'],
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
    $query = '';
    $output = [];
    $query .= "
        SELECT tb_transaksi.*,tb_customer.nama as nama_customer, tb_admin.username as admin_username, tb_pengerjaan_transaksi.status as status_pengerjaan
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
        $sub_array = [];
        $sub_array[] = $idx;
        $sub_array[] = date_format($tgl,'d M Y');
        $sub_array[] = $row['nama_customer'];
        $sub_array[] = $row['pengerjaan'];
        $sub_array[] = number_format($row['uang_muka']);
        $sub_array[] = number_format($row['total_transaksi']);
        $sub_array[] = $row['status'];
        $sub_array[] = $row['admin_username'];
        // $sub_array[] = $row['avatar'];
        $sub_array[] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning btn-xs update-user">Ubah</button>';
        if ($row['status_pengerjaan'] == 0) {
            $sub_array[] = '<button type="button" name="ambil_pekerjaan" id="ambil_pekerjaan_'.$row["id"].'" class="btn btn-info btn-xs perkerjaan" data-status="'.$row["status"].'">Ambil Pekerjaan</button>';
        }else {
            $sub_array[] = '<button type="button" name="ambil_pekerjaan" id="ambil_pekerjaan_'.$row["id"].'" class="btn btn-info btn-xs perkerjaan" data-status="'.$row["status"].'">Lanjutkan Pekerjaan</button>';
        }
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

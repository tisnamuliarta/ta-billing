<?php
/**
 * Created by PhpStorm.
 * User: Pro Health Box
 * Date: 4/22/2018
 * Time: 11:16 AM
 */
require_once('../connection.php');

if(isset($_GET['report'])) {
    getDataReport($connect);
}
if(isset($_GET['id_report'])) {
    checkDataPrint($connect, $_GET['id_report']);
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

function checkDataPrint($connect,$id)
{
    echo json_encode(['success'=>true,'data_id' => $id]);   
}

function saveData($connect) {
    $checkname = checkname($connect, $_POST['nama']);
    if($checkname) {
        echo json_encode(['errors'=>true,'msg'=>"Nama tidak boleh sama!"]);
    }else {
        $query = "INSERT INTO tb_customer (nama,alamat,tlpn,status)
        VALUES (:nama,:alamat,:tlpn,:status)";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':nama'         => $_POST['nama'],
                ':alamat'           => $_POST['alamat'],
                ':tlpn'             => $_POST['tlpn'],
                ':status'            => 'active'
            )
        );
        $result = $statement->rowCount();
        if ($result > 0) {
            echo json_encode(['errors'=>false,'msg'=>'Customer berhasil ditambahkan']);
        }
    }
}

function checkname($connect, $nama) {
    $query = "SELECT * FROM tb_customer WHERE nama = :nama";
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
    $query = " SELECT tb_customer.*
		from tb_customer WHERE tb_customer.id = :id ";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':id' => $_POST['id']
    ]);
    $result = $statement->fetchAll();
    // $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    foreach ($result as $row) {
        $output['id'] = $row['id'];
        $output['nama'] = $row['nama'];
        $output['alamat'] = $row['alamat'];
        $output['tlpn'] = $row['tlpn'];
        $output['status'] = $row['status'];
    }
    echo json_encode($output);
}

function editData($connect) {
    // $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "
        UPDATE tb_customer
        set
        nama = :nama,
        alamat = :alamat,
        tlpn = :tlpn
        WHERE id = :id
    ";
    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':nama'           => $_POST['nama'],
            ':alamat'           => $_POST['alamat'],
            ':tlpn'             => $_POST['tlpn'],
            ':id'				=> $_POST['user_id']
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
		UPDATE tb_customer
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


function getDataReport($connect) {
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = '';
    $output = [];
    $query .= "
        SELECT id_customer, kode, (SELECT tb_customer.nama FROM tb_customer WHERE tb_customer.id=tb_transaksi.id_customer) as nama_customer
        FROM tb_transaksi 
        LEFT JOIN tb_customer ON tb_customer.id = tb_transaksi.id 
    ";
    if (isset($_GET["search"]["value"])) {
        $query .= 'WHERE (SELECT tb_customer.nama FROM tb_customer WHERE tb_customer.id=tb_transaksi.id_customer) LIKE "%'.$_GET["search"]["value"].'%" ';
    }
    $query.= ' GROUP BY kode, id_customer ';
    if (isset($_GET["order"])) {
        $query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
    }else {
        $query .= 'ORDER BY tb_transaksi.id_customer ASC ';
    }
    if ($_GET["length"] != -1) {
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
    }
    // echo $query;

    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $data = [];
    $filtered_rows = $statement->rowCount();
    $start = $_REQUEST['start'];
    $idx = 0;
    foreach ($result as $row) {
        $idx++;

        $sub_array = [];
        $sub_array[] = $idx;
        $sub_array[] = $row['nama_customer'];
        $sub_array[] = $row['kode'];
        $sub_array[] = '<button type="button" name="update" id="'.$row["id_customer"].'" data-kode="'.$row['kode'].'" class="btn btn-warning btn-xs print-report">Print</button>';
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
    $statement = $connect->prepare('SELECT id_customer, kode, (SELECT tb_customer.nama FROM tb_customer WHERE tb_customer.id=tb_transaksi.id_customer) as nama_customer
        FROM tb_transaksi 
        LEFT JOIN tb_customer ON tb_customer.id = tb_transaksi.id ');
    $statement->execute();
    return $statement->rowCount();
}

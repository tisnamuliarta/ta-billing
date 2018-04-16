<?php
require_once('../connection.php');

if(isset($_GET['admin'])) {
    getDataAdmin($connect);
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
    $checkUsername = checkUsernam($connect, $_POST['username']);
    if(preg_match('/\s/', $_POST['username'])) {
        echo json_encode(['errors'=>true,'msg'=>"Username tidak boleh mengandung spasi!"]);
    }else {
        if($checkUsername) {
            echo json_encode(['errors'=>true,'msg'=>"Username tidak boleh sama!"]);
        }else {
            $query = "INSERT INTO tb_admin (username,jabatan,password,alamat,tlpn,status)
            VALUES (:username,:jabatan,:password,:alamat,:tlpn,:status)";
            $statement = $connect->prepare($query);
            $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
            $statement->execute(
                array(
                ':jabatan'        => $_POST['jabatan'],
                ':username'         => $_POST['username'],
                ':password'         => $password,
                ':alamat'           => $_POST['alamat'],
                ':tlpn'             => $_POST['tlpn'],
                ':status'            => 'active'
                )
            );
            $result = $statement->rowCount();
            if ($result > 0) {
                echo json_encode(['errors'=>false,'msg'=>'User berhasil ditambahkan']);
            }
        }
    }
}

function checkWhiteSpace($username) {
    if(ctype_space($username)) {
        return true;
    }
    return false;
}

function checkUsernam($connect, $username) {
    $query = "SELECT * FROM tb_admin WHERE username = :username";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':username' => $username
    ]);
    $result = $statement->rowCount();
    if($result > 0) {
        return true;
    }else {
        return false;
    }
}

function fetchSingle($connect) {
    $query = " SELECT tb_admin.*
		from tb_admin WHERE tb_admin.id = :id ";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':id' => $_POST['id']
    ]);
    $result = $statement->fetchAll();
    // $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    foreach ($result as $row) {
        $output['id'] = $row['id'];
        $output['jabatan'] = $row['jabatan'];
        $output['username'] = $row['username'];
        $output['alamat'] = $row['alamat'];
        $output['tlpn'] = $row['tlpn'];
        $output['status'] = $row['status'];
        $output['avatar'] = $row['avatar'];
    }
    echo json_encode($output);
}

function editData($connect) {
    // $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "
        UPDATE tb_admin
        set
        alamat = :alamat,
        tlpn = :tlpn,
        status = :status
        WHERE id = :id
    ";
    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':alamat'           => $_POST['alamat'],
            ':tlpn'             => $_POST['tlpn'],
            ':status' 			=> ($_POST['status']) ? $_POST['status'] : 'active',
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
		UPDATE tb_admin
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


function getDataAdmin($connect) {
    $query = '';
    $output = [];
    $query .= "
        SELECT tb_admin.*
        from tb_admin
    ";
    if (isset($_GET["search"]["value"])) {
        $query .= 'WHERE tb_admin.username LIKE "%'.$_GET["search"]["value"].'%" ';
        $query .= 'AND tb_admin.jabatan LIKE "%'.$_GET["search"]["value"].'%" ';
        $query .= 'AND tb_admin.tlpn LIKE "%'.$_GET["search"]["value"].'%" ';
        $query .= 'AND tb_admin.alamat LIKE "%'.$_GET["search"]["value"].'%" ';
    }
    if (isset($_GET["order"])) {
        $query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
    }else {
        $query .= 'ORDER BY tb_admin.id DESC ';
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

        $sub_array = [];
        $sub_array[] = $idx;
        $sub_array[] = $row['username'];
        $sub_array[] = $row['jabatan'];
        $sub_array[] = $row['tlpn'];
        $sub_array[] = $row['alamat'];
        $sub_array[] = $row['status'];
        // $sub_array[] = $row['avatar'];
        $sub_array[] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning btn-xs update-user">Ubah</button>';
        $sub_array[] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete-user" data-status="'.$row["status"].'">Hapus</button>';
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
	$statement = $connect->prepare('SELECT * FROM tb_admin');
	$statement->execute();
	return $statement->rowCount();
}

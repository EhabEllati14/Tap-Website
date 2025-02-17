<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("dbconfig.php");

if (!isset($_SESSION['role']) || !isset($_SESSION['logged']) || !isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== "Manager") {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    unset($_SESSION['error']);
    unset($_SESSION['valid']);


    $errs = [];
    $file_errs = [];


    if (!isset($_POST['projectId']) || empty($_POST['projectId'])) {
        $errs['projectId'] = "Please Enter The Project ID.";
    } else {
        $qu = "SELECT COUNT(*) AS projexists FROM projects WHERE project_id=:projid;";
        $stm = $pdo->prepare($qu);
        $stm->bindValue(":projid", $_POST['projectId']);
        $stm->execute();
        $res = $stm->fetch(PDO::FETCH_ASSOC);

        if (!$res['projexists'] == 0) {
            $errs['projectId'] = "This Project ID already exists!";
        } elseif (!preg_match('/^[A-Z]{4}-\d{5}$/', $_POST['projectId'])) {
            $errs['projectId'] = "Invalid Project ID format. Use format EX: ABCD-12345.";
        } else {
            $projid = $_POST['projectId'];
        }
    }

    if (!isset($_POST['projectTitle']) || empty($_POST['projectTitle'])) {
        $errs['projectTitle'] = "Please Enter The Project Title.";
    } elseif (strlen($_POST['projectTitle']) > 25) {
        $errs['projectTitle'] = "The Title must be short!";
    } else {
        $protitle = $_POST['projectTitle'];
    }

    if (!isset($_POST['projectdescription']) || empty($_POST['projectdescription'])) {
        $errs['projectdescription'] = "Please Enter the Description of the Project.";
    } else {
        $description = $_POST['projectdescription'];
    }

    if (!isset($_POST['customername']) || empty($_POST['customername'])) {
        $errs['customername'] = "Please Enter the Customer Name.";
    } else {
        $custname = $_POST['customername'];
    }

    if (!isset($_POST['budget']) || empty($_POST['budget'])) {
        $errs['budget'] = "Please Enter the Project Budget.";
    } elseif (!is_numeric($_POST['budget']) || $_POST['budget'] <= 0) {
        $errs['budget'] = "Budget must be a valid number greater than 0.";
    } else {
        $budget = $_POST['budget'];
    }

    if (!isset($_POST['startdate']) || empty($_POST['startdate'])) {
        $errs['startdate'] = "Please Enter the Project Start Date.";
    } else {
        $startdate = $_POST['startdate'];
    }

    if (!isset($_POST['enddate']) || empty($_POST['enddate'])) {
        $errs['enddate'] = "Please Enter the Project End Date.";
    } elseif (isset($startdate) && strtotime($_POST['enddate']) <= strtotime($startdate)) {
        $errs['enddate'] = "The End Date must be after the Start Date!";
    } else {
        $enddate = $_POST['enddate'];
    }


    if (isset($_FILES['docs'])) {
        $docs = $_FILES['docs'];
        $doc_titles = $_POST['doc_titles'];
        $max_size = 2 * 1024 * 1024; // Maximum size: 2 MB
        $allowed_types = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/png', 'image/jpg'];

        for ($i = 0; $i < count($docs['name']); $i++) {
            $file_uploaded = !empty($docs['name'][$i]);
            $title_provided = !empty($doc_titles[$i]);

            if ($file_uploaded || $title_provided) {
                if (!$file_uploaded) {
                    $file_errs["file_{$i}"] = "Please attach a file for the title '{$doc_titles[$i]}'.";
                } elseif (!$title_provided) {
                    $file_errs["title_{$i}"] = "Title is required for the file '{$docs['name'][$i]}'.";
                } elseif ($docs['size'][$i] > $max_size) {
                    $file_errs["file_{$i}"] = "File '{$docs['name'][$i]}' exceeds the size limit of 2MB.";
                } elseif (!in_array($docs['type'][$i], $allowed_types)) {
                    $file_errs["file_{$i}"] = "File '{$docs['name'][$i]}' is not an allowed type. Allowed types: PDF, DOCX, PNG, JPG.";
                }
            }
        }
    }

  
    if (!empty($errs) || !empty($file_errs)) {
        $_SESSION['error'] = array_merge($errs, $file_errs);
        $_SESSION['valid'] = $_POST;
        header("Location: dashboard.php?page=addproject");
        exit();
    }


    $ququ = "INSERT INTO projects (project_id, project_title, project_description, customer_name, budget, start_date, end_date) VALUES
             (:project_id, :project_title, :project_description, :customer_name, :budget, :start_date, :end_date);";
    try {
        $st = $pdo->prepare($ququ);
        $st->bindValue(':project_id', $projid);
        $st->bindValue(':project_title', $protitle);
        $st->bindValue(':project_description', $description);
        $st->bindValue(':customer_name', $custname);
        $st->bindValue(':budget', $budget);
        $st->bindValue(':start_date', $startdate);
        $st->bindValue(':end_date', $enddate);
        $st->execute();

        if (isset($docs) && !empty($docs['name'][0])) {
            $upload_dir = 'DOCS/';
            for ($i = 0; $i < count($docs['name']); $i++) {
                if (!empty($docs['name'][$i]) && !empty($doc_titles[$i])) {
                    $file_path = $upload_dir . $docs['name'][$i];
                    move_uploaded_file($docs['tmp_name'][$i], $file_path);
                    $stmt = $pdo->prepare("INSERT INTO project_documents (project_id, document_title, document_path)
                                           VALUES (:project_id, :document_title, :document_path)");
                    $stmt->bindValue(':project_id', $projid);
                    $stmt->bindValue(':document_title', $doc_titles[$i]);
                    $stmt->bindValue(':document_path', $file_path);
                    $stmt->execute();
                }
            }
        }

        $_SESSION['success'] = "Project successfully added !";
        unset($_SESSION['error']);
        unset($_SESSION['valid']);
        header("Location: success.php");
        exit();
    } catch (PDOException $e) {
        error_log("Error adding project: " . $e->getMessage());
        $_SESSION['error']['database'] = "An error occurred while adding the project.";
        header("Location: dashboard.php?page=addproject");
        exit();
    }
}
?>
    <h2>Please Enter the Project Details:</h2>
    <form method="post" action="AddProject.php" enctype="multipart/form-data" id='addprojectform'>
        <label for="projectid">Project ID:</label>
        <input type="text" id="projectid" name="projectId" required 
               value="<?php if (isset($_SESSION['valid']['projectId'])) echo $_SESSION['valid']['projectId']; ?>">
        <?php 
        if (isset($_SESSION['error']['projectId'])) {
            echo "<span>" . $_SESSION['error']['projectId'] . "</span>"; 
        } 
        ?>
        <br>
        <label for="projecttitle">Project Title:</label>
        <input type="text" id="projecttitle" name="projectTitle" maxlength="25" required 
               value="<?php if (isset($_SESSION['valid']['projectTitle'])) echo $_SESSION['valid']['projectTitle']; ?>">
        <?php 
        if (isset($_SESSION['error']['projectTitle'])) {
            echo "<span>" .$_SESSION['error']['projectTitle'] . "</span>";
        } 
        ?>
        <br>
        <label for="projdescription">Project Description:</label>
        <textarea id="projdescription" name="projectdescription" required><?php 
            if (isset($_SESSION['valid']['projectdescription'])) echo $_SESSION['valid']['projectdescription']; 
        ?></textarea>
        <?php 
        if (isset($_SESSION['error']['projectdescription'])) {
            echo "<span>" . $_SESSION['error']['projectdescription'] . "</span>";
        } 
        ?>
        <br>
        <label for="custname">Customer Name:</label>
        <input type="text" id="custname" name="customername" required 
               value="<?php if (isset($_SESSION['valid']['customername'])) echo $_SESSION['valid']['customername']; ?>">
        <?php 
        if (isset($_SESSION['error']['customername'])) {
            echo "<span>" .$_SESSION['error']['customername']. "</span>";
        } 
        ?>
        <br>
        <label for="budget">Budget:</label>
        <input type="number" id="budget" name="budget" min="1" required 
               value="<?php if (isset($_SESSION['valid']['budget'])) echo $_SESSION['valid']['budget']; ?>">
        <?php 
        if (isset($_SESSION['error']['budget'])) {
            echo "<span>" .$_SESSION['error']['budget']. "</span>";
        } 
        ?>
        <br>
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startdate" required 
               value="<?php if (isset($_SESSION['valid']['startdate'])) echo $_SESSION['valid']['startdate']; ?>">
        <?php 
        if (isset($_SESSION['error']['startdate'])) {
            echo "<span>" . $_SESSION['error']['startdate'] . "</span>";
        } 
        ?>
        <br>
        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" name="enddate" required 
               value="<?php if (isset($_SESSION['valid']['enddate'])) echo $_SESSION['valid']['enddate']; ?>">
        <?php 
        if (isset($_SESSION['error']['enddate'])) {
            echo "<span>" .$_SESSION['error']['enddate'] . "</span>";
        } 
        ?>
        <br>
        <label for="doc1_title">Documents 1 Title:</label>
        <input type="text" id="doc1_title" name="doc_titles[]" 
               value="<?php if (isset($_SESSION['valid']['doc_titles'][0])) echo htmlspecialchars($_SESSION['valid']['doc_titles'][0]); ?>">
        <input type="file" id="doc1_file" name="docs[]" accept=".pdf,.docx,.png,.jpg">
        <?php 
        if (isset($_SESSION['error']['file_0'])) {
            echo "<span>" . htmlspecialchars($_SESSION['error']['file_0']) . "</span>";
        } 
        if (isset($_SESSION['error']['title_0'])) {
            echo "<span>" . htmlspecialchars($_SESSION['error']['title_0']) . "</span>";
        } 
        ?>
        <br>
        <label for="doc2_title">Documents 2 Title:</label>
        <input type="text" id="doc2_title" name="doc_titles[]" 
               value="<?php if (isset($_SESSION['valid']['doc_titles'][1])) echo $_SESSION['valid']['doc_titles'][1]; ?>">
        <input type="file" id="doc2_file" name="docs[]" accept=".pdf,.docx,.png,.jpg">
        <?php 
        if (isset($_SESSION['error']['file_1'])) {
            echo "<span>" . $_SESSION['error']['file_1'] . "</span>";
        } 
        if (isset($_SESSION['error']['title_1'])) {
            echo "<span>" . $_SESSION['error']['title_1'] . "</span>";
        } 
        ?>
        <br>
        <label for="doc3_title">Documents 3 Title:</label>
        <input type="text" id="doc3_title" name="doc_titles[]" 
               value="<?php if (isset($_SESSION['valid']['doc_titles'][2])) echo$_SESSION['valid']['doc_titles'][2]; ?>">
        <input type="file" id="doc3_file" name="docs[]" accept=".pdf,.docx,.png,.jpg">
        <?php 
        if (isset($_SESSION['error']['file_2'])) {
            echo "<span>" . $_SESSION['error']['file_2'] . "</span>";
        } 
        if (isset($_SESSION['error']['title_2'])) {
            echo "<span>" . $_SESSION['error']['title_2'] . "</span>";
        } 
        ?>
        <br>
        <input type="submit" name="add" value="Add Project">
</form>



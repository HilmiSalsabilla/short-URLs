<?php 
    require "config.php";

    $error = null;

    // Ambil semua URL
    $select = $conn->prepare("SELECT * FROM urls");
    $select->execute();
    $rows = $select->fetchAll(PDO::FETCH_OBJ);

    // Proses form submit
    if (isset($_POST['submit'])) {
        $url = trim($_POST['url']);

        if (empty($url)) {
            $error = "The input is empty.";
        } elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
            $error = "Invalid URL format.";
        } else {
            $insert = $conn->prepare("INSERT INTO urls(url) VALUES (:url)");
            $insert->execute([':url' => $url]);

            header("Location: index.php");
            exit;
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Simple URL Shortener</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>       
        <div class="container-fluid">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <h3 class="text-center py-4">Simple URL Shortener</h3>
                    <form class="card" method="POST" action="index.php">
                        <div class="input-group">
                            <input type="text" name="url" class="form-control" placeholder="Enter your URL">
                            <div class="input-group-append">
                                <button type="submit" name="submit" class="btn btn-success">Shorten</button>
                            </div>
                        </div>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger mt-3"><?= $error ?></div>
                        <?php endif; ?>
                    </form>
                </div>
           </div>
        </div>

        <div class="container-fluid mb-4">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <table class="table table-bordered mt-4 text-center" id="url-table">
                        <thead>
                            <tr>
                            <th>#</th>
                            <th>Long Url</th>
                            <th>Short Url</th>
                            <th style="width: 80px">Clicks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($rows as $row): ?>
                                <tr>
                                    <td><?= $row->id ?></td>
                                    <td class="text-left"><?= htmlspecialchars($row->url) ?></td>
                                    <td class="text-left">
                                        <a href="http://localhost/short-urls/u?id=<?= $row->id ?>" target="_blank">
                                            http://localhost/short-urls/u?id=<?= $row->id ?>
                                        </a>
                                    </td>
                                    <td><?= $row->clicks ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                 </div>
             </div>
        </div>
    
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" ></script>
        <script>
            // Auto-refresh tabel setiap 5 detik
            setInterval(function() {
                $("#url-table").load(" #url-table > *");
            }, 5000);
        </script>
    </body>
</html>

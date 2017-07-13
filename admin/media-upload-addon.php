<?php

  addHook('admin_body_load', 'renderUploadButton');

  function renderUploadButton($data) {

    $route = getRoute();

    if(strpos($route, 'mp-admin/upload') !== false) {
      echo '
      <form action="index.php" method="POST" enctype="multipart/form-data">
          Select image to upload:
          <input type="file" name="myFile" id="myFile">
          <input type="submit" value="Upload Image" name="submit">
      </form>';
    }

  }

  define("UPLOAD_DIR", "/var/www/html/media/");

  if (!empty($_FILES["myFile"])) {
      $myFile = $_FILES["myFile"];

      if ($myFile["error"] !== UPLOAD_ERR_OK) {
          echo "<p>An error occurred.</p>";
          exit;
      }

      // ensure a safe filename
      $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

      // don't overwrite an existing file
      $i = 0;
      $parts = pathinfo($name);
      while (file_exists(UPLOAD_DIR . $name)) {
          $i++;
          $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
      }

      // preserve file from temporary directory
      $success = move_uploaded_file($myFile["tmp_name"],
          UPLOAD_DIR . $name);
      if (!$success) {
          echo "<p>Unable to save file.</p>";
          exit;
      }

      $database->addMedia($name, $name, '');

      // set proper permissions on the new file
      chmod(UPLOAD_DIR . $name, 0644);
      header('Location: /mp-admin/media');
  }

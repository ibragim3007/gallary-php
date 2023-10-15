<?php
    require "inc/database.php";
    $getAllFiles = mysqli_query($connect, "SELECT * FROM `file` ORDER BY rand()");
?>
<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <!-- <script src="https://kit.fontawesome.com/e12edcb89e.js" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="src/app.css">
    <title>Drag&Drop</title>
</head>
<body>
    <div class="container">
        <div class="pole">
            <? while($file = mysqli_fetch_assoc($getAllFiles))
            { ?>
                <div class="item">
                    <a data-fancybox="gallery" href="<? echo $file["path"]; ?>">
                        <img style="max-height: 500px;" id="myImg" src="src/image/preloader.gif" data="<? echo $file['path']; ?>" alt="<? echo $file['name']; ?>" class="icon">
                    </a>
                </div>
                <?
            } ?>
        </div>
    </div>

    <div class="drag">
        <div class="pole-for-file">

            <div class="input__wrapper">
                <input name="file" type="file" name="file" id="input__file" class="input input__file" multiple>
                <label for="input__file" class="input__file-button">
                    <span class="input__file-icon-wrapper"><img style="width: 50px;" src="src/image/add_file.png" alt=""></span>
                </label>
            </div>

            <div class="text-replace">
               <p class="self-text">Переместите сюда файлы</p>
            </div>
        </div>
        <div class="files">
            <div class="images">

            </div>
            <button class="upload none" onclick="uploadFiles()">Загрузить</button>
        </div>
    </div>

</body>
<script src="src/lazyload.js"></script>
<script src="src/app.js"></script>

</html>
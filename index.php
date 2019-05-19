<?php
setlocale(LC_ALL, 'id_ID');
date_default_timezone_set('Asia/Jakarta');

$database = new MySQLi(
  getenv('DB_HOST'),
  getenv('DB_USER'),
  getenv('DB_PASS'),
  getenv('DB_NAME')
);

if (!$database->connect_errno) {
  if (isset($_POST['title'], $_POST['content'])) {
    header('Location: /');

    $statement = $database->prepare('insert `posts` (`title`, `content`) values (?, ?)');
    $statement->bind_param('ss', $_POST['title'], $_POST['content']);
    $statement->execute();

    exit;
  }

  $database->query('set time_zone = \'Asia/Jakarta\'');

  $posts = $database->query(
    'select * from `posts` order by `published_at` desc',
    MYSQLI_USE_RESULT
  )->fetch_all(MYSQLI_ASSOC);
}
?>
<!doctype html>
<html lang="id" style="scroll-behavior: smooth">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Blog | Sebuah aplikasi blog sederhana</title>
    <link rel="icon" href="https://portal.azure.com/favicon.ico" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" />
    <link rel="stylesheet" href="https://getbootstrap.com/docs/3.3/examples/blog/blog.css" />
  </head>
  <body>
    <div class="blog-masthead">
      <div class="container">
        <nav class="blog-nav">
          <a class="blog-nav-item" href="#">
            <span class="glyphicon glyphicon-home"></span> Beranda
          </a>
          <a class="blog-nav-item" href="#about">
            <span class="glyphicon glyphicon-info-sign"></span> Tentang
          </a>
          <a class="blog-nav-item pull-right" href="#new-post" data-toggle="modal">
            <span class="glyphicon glyphicon-plus-sign"></span> Pos Baru
          </a>
        </nav>
      </div>
    </div>
    <div class="container">
      <div class="blog-header">
        <h1 class="blog-title">Blog</h1>
        <p class="lead blog-description">Sebuah aplikasi blog sederhana…</p>
      </div>
      <div class="row">
        <div class="col-sm-8 blog-main">
          <?php
          if (!empty($posts)) {
            foreach ($posts as $post) {
            ?>
              <div class="blog-post">
                <h2 id="post-<?=$post['id']?>" class="blog-post-title"><?=$post['title']?></h2>
                <p class="blog-post-meta"><?=strftime('%c', strtotime($post['published_at']))?></p>
                <?=$post['content']?>
              </div>
            <?php
            }
          }
          ?>
        </div>
        <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
          <div class="sidebar-module sidebar-module-inset">
            <h4 id="about">Tentang</h4>
            <p>
              Aplikasi blog sederhana ini dibuat dengan <span style="color: #e25555;">&hearts;</span>
              oleh <a href="https://github.com/ndaru">@ndaru</a> sebagai bahan untuk kelas
              <a href="https://www.dicoding.com/academies/83">Menjadi Azure Cloud Developer</a>.
            </p>
          </div>
          <?php
          if (!empty($posts)) {
          ?>
            <div class="sidebar-module">
              <h4>Pos Terbaru</h4>
              <ol class="list-unstyled">
                <?php
                foreach (array_slice($posts, 0, 3) as $post) {
                ?>
                  <li><a href="#post-<?=$post['id']?>"><?=$post['title']?></a></li>
                <?php
                }
                ?>
              </ol>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
    <footer class="blog-footer">
      <p>
        Templat ini dibuat menggunakan <a href="https://getbootstrap.com/">Bootstrap</a>
        oleh <a href="https://twitter.com/mdo">@mdo</a>.
      </p>
      <p><a href="#">Kembali ke atas</a></p>
    </footer>
    <form method="post">
      <div id="new-post" class="modal fade">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button class="close" data-dismiss="modal" type="button"><span>&times;</span></button>
              <h4 class="modal-title">Pos Baru</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="title">Judul</label>
                <input id="title" class="form-control" name="title" type="text" required="required">
              </div>
              <div class="form-group">
                <label for="content">Konten</label>
                <textarea id="content" name="content"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default" type="button" data-dismiss="modal">Tutup</button>
              <button class="btn btn-primary" type="submit">Terbitkan</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/lang/summernote-id-ID.min.js"></script>
    <script>
      $("#content").summernote({
        lang: 'id-ID',
        height: 300
      })
    </script>
  </body>
</html>

<?php
require_once 'class/Session.php';
require_once 'class/Downloader.php';
require_once 'class/FileHandler.php';

$session = Session::getInstance();
$file    = new FileHandler;

require 'views/header.php';

if (!$session->is_logged_in()) {
    header("Location: login.php");
    exit();
} else {
    if (isset($_GET['kill']) && !empty($_GET['kill']) && $_GET['kill'] === "all") {
        Downloader::kill_them_all();
    }

    if (isset($_POST['urls']) && !empty($_POST['urls'])) {
        $audio_only = false;
		$quality = false;

        if (isset($_POST['audio']) && !empty($_POST['audio'])) {
			$audio_only = true;
		}

		if (isset($_POST['quality']) && !empty($_POST['quality'])) {
			$quality = $_POST['quality'];
		}

        $downloader = new Downloader($_POST['urls'], $audio_only, $quality);
        
        if (!isset($_SESSION['errors'])) {
            header("Location: index.php");
        }
    }
}
?>
		<div class="container">
			<h1>Download</h1>
			<?php if (isset($_SESSION['errors']) && $_SESSION['errors'] > 0): ?>
                <?php foreach ($_SESSION['errors'] as $e): ?>
                    <div class="alert alert-warning" role="alert"><?= $e; ?></div>
                <?php endforeach; ?>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>
			<form id="download-form" class="form-horizontal" action="index.php" method="post">					
				<div class="form-group">
					<div class="col-md-10">
						<input class="form-control" id="url" name="urls" placeholder="Link(s) separated by a comma" type="text" required>
					</div>
					<div class="col-md-2">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="audio">Audio Only
							</label>
						</div>
					</div>
					<div class="col-md-2">
						<div class="select">
							<label> quality:
								<select name="quality">
									<option value="false">no change</option>
									<option>best</option>
									<option>worst</option>
									<option>mp4</option>
									<option value="all">all formats</option>
									<option>flv</option>
								</select>
							</label>
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary">Download</button>
			</form>
			<br>
			<div class="row">
				<div class="col-lg-6">
					<div class="panel panel-info">
						<div class="panel-heading"><h3 class="panel-title">Info</h3></div>
						<div class="panel-body">
							<p>Free space : <kbd><?= $file->free_space(); ?><kbd></p>
							<p>Download folder : <kbd><?= $file->get_downloads_folder(); ?><kbd></p>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="panel panel-info">
						<div class="panel-heading"><h3 class="panel-title">Help</h3></div>
						<div class="panel-body">
							<p><b>How does it work ?</b></p>
							<p>Simply paste your video link in the field and click "Download"</p>
							<p><b>With which sites does it work?</b></p>
							<p><a href="http://rg3.github.io/youtube-dl/supportedsites.html" target="__blank">Here's</a> a list of the supported sites</p>
							<p><b>How can I download the video on my computer?</b></p>
							<p>Go to <a href="./list.php?type=v">List of videos</a> -> choose one -> right click on the link -> "Save target as ..." </p>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php require 'views/footer.php'; ?>

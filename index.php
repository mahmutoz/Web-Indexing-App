<?php include_once "header.php" ?>
<?php include_once "src/1.php" ?>

<main>
    <div class="container">
        <div class="row search ml-1 mr-1">
            <h1 class="text-center">WEB INDEXING APPLICATION</h1>
            <img width="410" height="320" src="img/web-search.png" alt="search">
            <form method="post" action="index.php">
                <div class="form-group">
                    <label for="url-area">Enter the website address</label>
                    <input type="text" class="form-control" name="url" id="url-area" value="" placeholder="http://..." required>
                </div>
                <button class="btn btn-primary" type="submit">Scan</button>
            </form>
        </div>
        <div class="row mt-1">
            <div class="col-md-8">
                <h4 class="pl-2">Indexed Content</h4>
                <div class="freq-results">
                    <?php foreach ($newWords as $key=>$val): ?>
                    <?php echo $val; ?>
                    <?php endforeach; ?>
                </div>
                <div class="words-count mt-1">
                    <?php echo "Total word count = " . count($newWords); ?>
                </div>
            </div>
            <div class="col-md-4">
                <h4 class="text-center">Words and Frequencies</h4>
                <div class="freq-results">
                    <?php foreach ($freq as $key=>$val): ?>
                    <?php
                        echo "<div class='freq-keywords'><span> $key </span>";
                        echo "<span> $val </span></div>";
                        echo '<hr>';
                    ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include_once "footer.php" ?>
<?php include_once "header.php" ?>
<?php include_once "src/2-3.php" ?>
    <main>
        <div class="container height-100">
            <div class="search">
                <h1 class="text-center">KEYWORDS & SIMILARITY SCORE</h1>
                <img width="440" height="350" src="img/anahtar-kelime.png" alt="search">
            </div>
            <div class="row">
                <form class="w-100" method="post" action="keywords-and-similarity-score.php">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="url-area">Enter the first website address.</label>
                            <input type="text" class="form-control frm1 mb-2" name="url[]" id="url-area" value=""
                                   placeholder="http://..." required>
                        </div>
                        <div class="col-md-6">
                            <label for="url-area">Enter the second website address.</label>
                            <input type="text" class="form-control" name="url[]" id="url-area2" value=""
                                   placeholder="http://..." required>
                        </div>
                    </div>
                    <button class="btn btn-primary text-center" type="submit">Scan</button>
                </form>
            </div>
            <div class="row mt-3">
                <?php if ( is_array($latest) || is_object($latest) ): ?>
                    <?php foreach ( $latest as $key => $val ): ?>
                        <?php echo "<div class='col-6'>"; ?>
                        <?php echo "<h6 class='text-center mt-2'>" . " Keywords and Frequencies of the " . ($key + 1) . " . Website</h6>"; ?>
                        <?php echo "<div class='freq-results'>"; ?>
                        <?php foreach ( $val as $kval => $vval ): ?>
                            <?php
                            echo "<div class='freq-keywords'><span> $kval </span>";
                            echo "<span> $vval </span></div>";
                            echo '<hr>';
                            ?>
                        <?php endforeach; ?>
                        <?php echo " </div>"; ?>
                        <?php echo "</div>"; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <div class="skor text-center mt-4">
                        <h4 class="pb-2">Similarity Score</h4>
                        <?php echo ($skors < 101) ? "% " . round($skors, 0, PHP_ROUND_HALF_UP) : ""; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php include_once "footer.php" ?>
<?php include_once "header.php" ?>
<?php include_once "src/4.php"; ?>
    <main>
        <div class="container height-100">
            <div class="search">
                <h1 class="text-center">SITE INDEXING & RANKING</h1>
                <img width="550" height="350" src="img/siralama.png" alt="search">
            </div>
            <div class="row">
                <form class="w-100" method="post" action="site-indexing-and-ranking.php">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="url-area">Enter the website address</label>
                            <input type="text" class="form-control mb-2" name="url" id="url-area" value=""
                                   placeholder="http://..." required>
                        </div>
                        <div class="col-md-6">
                            <label for="url-area">Enter the web site set. (One website per line)</label>
                            <textarea name="sites" class="form-control" id="exampleFormControlTextarea1" cols="55"
                                      rows="5"></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Scan</button>
                </form>
            </div>
            <div class="row">
                <?php echo "<div class='col'>" ?>
                <?php foreach ( $allUrls as $key => $val ): ?>
                    <?php
                    echo "<div class='derece first'>" . $val . "</div>";
                    ?>
                    <?php foreach ( $ikinciDerece as $ikey => $ival ): ?>
                        <?php foreach ( $ival as $iikey => $iival ): ?>
                            <?php
                            if ( $key == $ikey ) {
                                echo "<div class='derece second'>" . $iival . "</div>";
                                $x = 0;
                            }
                            ?>

                            <?php foreach ( $ucuncuDerece as $ukey => $uval ): ?>
                                <?php foreach ( $uval as $uukey => $uuval ): ?>
                                    <?php
                                    if ( ($ukey == (($ikey * 3) + $iikey)) && $x < count($uval) ) {
                                        echo "<div class='derece three'>" . $uuval . "</div>";
                                        $x++;
                                    }
                                    ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php echo "</div>" ?>
            </div>
            <div class="row">
                <?php foreach ($wholeUrls

                as $key => $link): ?>
                <?php $yaz = anahtarKelimeAnalizi($link) ?>
                <?php if (is_array($yaz) || is_object($yaz)): ?>
                <?php foreach ($yaz as $key => $val): ?>
                <?php if ($key == 0): ?>

                <?php echo "<div class='col-6 mt-4'>"; ?>
                <?php echo "<h6 class='text-center mt-2'><span class='text-c'>$link</span>Website Keywords and Frequencies</h6>"; ?>
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
                <?php echo "<div class='col-6 d-flex align-items-center justify-content-center'>"; ?>
                <div class="skor text-center mt-4">
                    <h4 class="pb-2">Similarity Score</h4>
                    <?php $skor = skorHesapla($yaz) ?>
                    <?php echo ($skor < 101) ? "% " . round($skor, 0, PHP_ROUND_HALF_UP) : ""; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php endforeach; ?>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <!--            <div class="row">-->
        <!--                <div class="col-12 d-flex justify-content-center">-->
        <!--                    <div class="skor text-center mt-4">-->
        <!--                        <h4 class="pb-2">Benzerlik Skoru</h4>-->
        <!--                        --><?php //echo ($skors < 101) ? "% " . round($skors, 0, PHP_ROUND_HALF_UP) : ""; ?>
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        </div>
    </main>
<?php include_once "footer.php" ?>
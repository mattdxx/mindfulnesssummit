<?php
function tcm_ui_faq() {
    global $tcm;
    $i=1;
    while($tcm->Lang->H('Faq.Question'.$i)) {
        $q=$tcm->Lang->L('Faq.Question'.$i);
        $r=$tcm->Lang->L('Faq.Response'.$i);
        ?>
        <p>
            <b><?php echo $q?></b>
            <br/>
            <?php echo $r?>
        </p>
        <?php
        ++$i;
    }
    ?>
    <h2><?php $tcm->Lang->P('YouTubeVideo.Title') ?></h2>
    <?php
    $i=1;
    while($tcm->Lang->H('YouTubeVideo.URL'.$i)) {
        $q=$tcm->Lang->L('YouTubeVideo.URL'.$i);
        $r=$tcm->Lang->L('YouTubeVideo.Description'.$i);
        ?>
        <p>
            <iframe width="350" height="210" src="https://www.youtube.com/embed/<?php echo $q?>"></iframe>
            <br/>
            <?php echo $r?>
        </p>
        <?php
        ++$i;
    }
}
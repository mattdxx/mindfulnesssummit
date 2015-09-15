<?php
/**
 * Created by PhpStorm.
 * User: alessio
 * Date: 29/03/2015
 * Time: 09:10
 */
function tcm_ui_feedback() {
    global $tcm;

    $tcm->Form->prefix='Feedback';
    if($tcm->Check->nonce('tcm_feedback', 'tcm_feedback')) {
        $tcm->Check->email('email');
        $tcm->Check->value('body');

        if(!$tcm->Check->hasErrors()) {
            $tcm->Options->setFeedbackEmail($tcm->Check->of('email'));
            $id=-1;
            if($tcm->Check->of('track', 0)) {
                $id=$tcm->Tracking->sendTracking(TRUE);
            }
            $tcm->Check->data['tracking_id']=$id;
            $data=$tcm->Utils->remotePost('feedback', $tcm->Check->data);
            if($data) {
                $tcm->Options->pushSuccessMessage('FeedbackSuccess');
            } else {
                $tcm->Options->pushErrorMessage('FeedbackError');
            }
        }
    }
    ?>
    <br>
    <h2><?php $tcm->Lang->P('FeedbackHeader')?></h2>
    <?php
    $tcm->Options->writeMessages();

    $tcm->Form->formStarts();
    {
        $tcm->Form->text('email', $tcm->Options->getFeedbackEmail());
        $tcm->Form->textarea('body', '', array('rows'=>5));
        $tcm->Form->checkbox('track');

        $tcm->Form->nonce('tcm_feedback', 'tcm_feedback');
        $tcm->Form->submit('Send');
    }
    $tcm->Form->formEnds();
}
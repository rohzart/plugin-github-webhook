<h3><i class="fa fa-github fa-fw"></i>&nbsp;<?= t('Github webhooks') ?></h3>
<div class="listing">
<input type="text" class="auto-select" readonly="readonly" value="<?= $this->url->href('webhook', 'handler', array('plugin' => 'GithubWebhook', 'token' => $webhook_token, 'project_id' => $project['id']), false, '', true) ?>"/><br/>
<p class="form-help"><a href="http://kanboard.net/plugins/github-webhook" target="_blank"><?= t('Help on Github webhooks') ?></a></p>
</div>
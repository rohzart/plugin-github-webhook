<?php

namespace Kanboard\Plugin\GithubWebhook;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Security\Role;
use Kanboard\Core\Translator;

class Plugin extends Base
{
    public function initialize()
    {
        /* Events - Issue */
        $this->actionManager->getAction('\Kanboard\Action\CommentCreation')->addEvent(WebhookHandler::EVENT_ISSUE_COMMENT);
        $this->actionManager->getAction('\Kanboard\Action\TaskAssignCategoryLabel')->addEvent(WebhookHandler::EVENT_ISSUE_LABEL_CHANGE);
        $this->actionManager->getAction('\Kanboard\Action\TaskAssignUser')->addEvent(WebhookHandler::EVENT_ISSUE_ASSIGNEE_CHANGE);
        $this->actionManager->getAction('\Kanboard\Action\TaskClose')->addEvent(WebhookHandler::EVENT_ISSUE_CLOSED);
        $this->actionManager->getAction('\Kanboard\Action\TaskCreation')->addEvent(WebhookHandler::EVENT_ISSUE_OPENED);
        $this->actionManager->getAction('\Kanboard\Action\TaskOpen')->addEvent(WebhookHandler::EVENT_ISSUE_REOPENED);

        /* Events - Commit */
        $this->actionManager->getAction('\Kanboard\Action\CommentCreation')->addEvent(WebhookHandler::EVENT_COMMIT);
        $this->actionManager->getAction('\Kanboard\Action\TaskClose')->addEvent(WebhookHandler::EVENT_COMMIT);

        /* Events - Project Card */
        $this->actionManager->getAction('\Kanboard\Action\TaskCreation')->addEvent(WebhookHandler::EVENT_CARD_CREATED);
        $this->actionManager->getAction('\Kanboard\Action\TaskClose')->addEvent(WebhookHandler::EVENT_CARD_DELETED);
        $this->actionManager->getAction('\Kanboard\Action\CommentCreation')->addEvent(WebhookHandler::EVENT_CARD_CONVERTED);
        $this->actionManager->getAction('\Kanboard\Action\CommentCreation')->addEvent(WebhookHandler::EVENT_CARD_EDITED);
        $this->actionManager->getAction('\Kanboard\Action\CommentCreation')->addEvent(WebhookHandler::EVENT_CARD_MOVED);

        $this->template->hook->attach('template:project:integrations', 'GithubWebhook:project/integrations');
        $this->route->addRoute('/webhook/github/:project_id/:token', 'Webhook', 'handler', 'GithubWebhook');
        $this->applicationAccessMap->add('Webhook', 'handler', Role::APP_PUBLIC);
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');

        /* Event - Commit */
        $this->eventManager->register(WebhookHandler::EVENT_COMMIT, t('Github commit received'));
        /* Event - Issue */
        $this->eventManager->register(WebhookHandler::EVENT_ISSUE_OPENED, t('Github issue opened'));
        $this->eventManager->register(WebhookHandler::EVENT_ISSUE_CLOSED, t('Github issue closed'));
        $this->eventManager->register(WebhookHandler::EVENT_ISSUE_REOPENED, t('Github issue reopened'));
        $this->eventManager->register(WebhookHandler::EVENT_ISSUE_ASSIGNEE_CHANGE, t('Github issue assignee change'));
        $this->eventManager->register(WebhookHandler::EVENT_ISSUE_LABEL_CHANGE, t('Github issue label change'));
        $this->eventManager->register(WebhookHandler::EVENT_ISSUE_COMMENT, t('Github issue comment created'));
        /* Event - Project Card */
        $this->eventManager->register(WebhookHandler::EVENT_CARD_CREATED, t('Github project card created'));
        $this->eventManager->register(WebhookHandler::EVENT_CARD_EDITED, t('Github project card edited'));
        $this->eventManager->register(WebhookHandler::EVENT_CARD_MOVED, t('Github project card moved'));
        $this->eventManager->register(WebhookHandler::EVENT_CARD_CONVERTED, t('Github project card converted'));
        $this->eventManager->register(WebhookHandler::EVENT_CARD_DELETED, t('Github project card deleted'));
    }

    public function getPluginName()
    {
        return 'Github Webhook';
    }

    public function getPluginDescription()
    {
        return t('Bind Github webhook events to Kanboard automatic actions');
    }

    public function getPluginAuthor()
    {
        return 'Frédéric Guillot';
    }

    public function getPluginVersion()
    {
        return '1.0.6';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/kanboard/plugin-github-webhook';
    }

    public function getCompatibleVersion()
    {
        return '>=1.0.37';
    }
}

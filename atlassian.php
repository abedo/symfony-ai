<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\AI\Agent\Agent;
use Symfony\AI\Agent\Toolbox\AgentProcessor;
use Symfony\AI\Agent\Toolbox\Tool\Atlassian;
use Symfony\AI\Agent\Toolbox\Toolbox;
use Symfony\AI\Platform\Bridge\Gemini\PlatformFactory;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\HttpClient\HttpClient;

require_once dirname(__DIR__).'/bootstrap.php';

$platform = PlatformFactory::create(env('GEMINI_API_KEY'), http_client());

$confluenceClient = HttpClient::create([
    'auth_basic' => [env('CONFLUENCE_USERNAME'), env('CONFLUENCE_API_TOKEN')],
]);

$confluence = new Atlassian(
    $confluenceClient,
    env('CONFLUENCE_BASE_URL'),
    env('CONFLUENCE_API_TOKEN'),
);
$toolbox = new Toolbox([$confluence], logger: logger());
$processor = new AgentProcessor($toolbox);
$agent = new Agent($platform, 'gemini-2.5-flash', [$processor], [$processor]);
$messages = new MessageBag(Message::ofUser(<<<TXT
        Find all documents related to Finance Backend Chapter

        Note: When searching Confluence, use proper CQL syntax like: text ~ "maintenance mode"

        Note: When searching Jira, use proper JQL syntax
    TXT));
//$result = $agent->call($messages, [
//    'stream' => true, // enable streaming of response text
//]);
$result = $agent->call($messages);

echo $result->getContent().\PHP_EOL;
